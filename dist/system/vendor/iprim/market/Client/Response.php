<?php
namespace iprim\market\Client;

use iprim\market\common\InvalidConfigException;
use iprim\market\common\Model;

/**
 * Class Response
 * @property array $exception
 * @package iprim\market\Client
 */
class Response extends Model
{
    const STATUS_PROCESS  = 'process';
    const STATUS_COMPLETE = 'complete';
    const STATUS_ERROR    = 'error';
    const STATUS_CANCEL   = 'cancel';

    /**
     * Идентификатор запроса на market.iprim.ru
     * @See: \iprim\market\Client\Request::uid
     * @var integer
     */
    public $uid;

    /**
     * Внутренний ID заказа
     * @var string
     */
    public $internal_id;

    /**
     * Оплачено или нет
     * По умолчанию: не определено
     * @var integer 1|0
     */
    public $paid;

    /**
     * Комментарий об успешном приеме заказа или об ошибке
     * @var string
     */
    public $comment;

    /**
     * Статус заказа
     * @var string process|complete|error|cancel
     */
    public $status;

    /**
     * Текст статуса заказа
     * @var string
     */
    public $status_text;

    /**
     * Переправить клиента на market.iprim.ru или отправить запрос в фоновом режиме
     * Фоновый режим нужно использовать только для повторных запросов, таких как уточнение статуса заказа
     * @var integer 1|0
     */
    public $background = 0;

    /**
     * Уведомить клиента об изменении статуса заказа
     * @var integer 1|0
     */
    public $notify = 1;

    /**
     * Исключение,
     * нужно для отслеживания возможных проблем
     * @var \Exception
     */
    protected $_exception;

    /**
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'exception';

        return $fields;
    }

    /**
     * Редирект в Маркет
     */
    public function send()
    {
        if (!$this->exception and !$this->uid) {
            throw new InvalidConfigException('Необходимо указать ID заказа в параметре "order_id"');
        }

        $response = $this->toArray();
        $response['crc'] = Helper::createCrc($response, Config::$secretKey);

        if($this->background) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, Config::CALLBACK_URL);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(['request' => $response]));
            $content = curl_exec($curl);
            curl_close($curl);

            return json_decode($content, true);
        }

        $this->renderForm($response, $formBody);
        echo $this->wrapForm($formBody);
        exit;
    }

    /**
     * @return array
     */
    public function getException()
    {
        return $this->_exception;
    }

    /**
     * @param \Exception $exception
     */
    public function setException(\Exception $exception)
    {
        $this->_exception = [
            'url'         => Helper::getAbsoluteUrl(),
            'referer'     => Helper::getReferrer(),
            'ip_address'  => Helper::getUserIP(),
            'user_agent'  => Helper::getUserAgent(),
            'body_params' => $_POST,
            'error'       => $exception->getFile() . '::' . $exception->getLine() . ' - ' . $exception->getMessage(),
        ];
    }

    protected function renderForm($array, &$output = '', $prefix = 'request')
    {
        foreach ($array as $key => $value) {
            $name = $prefix . '[' . $key . ']';
            if (is_array($value)) {
                $this->renderForm($value, $output, $name);
            } else {
                $output .= '<input type="hidden" name="' . $name . '" value="' . ($value) . '" />';
            }
        }
    }

    protected function wrapForm($formBody)
    {
        return '<!DOCTYPE html>
        <html>
        <body>
            <form action="' . Config::CALLBACK_URL . '" name="redirect" method="post">
                ' . $formBody . '
                Возвращаемся на market.iprim.ru...<br><br>
                <button type="submit">Вернуться</button>
            </form>
            <script language="JavaScript">
                document.redirect.submit();
            </script>
        </body>
        </html>';
    }
}