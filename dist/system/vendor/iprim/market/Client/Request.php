<?php
namespace iprim\market\Client;

use iprim\market\common\Exception;
use iprim\market\common\ForbiddenHttpException;
use iprim\market\common\InvalidConfigException;
use iprim\market\common\Model;

/**
 * @property string $uid
 * @property boolean $debug
 * @property Request\Shop $shop
 * @property Request\Order $order
 * @property Request\Basket $basket
 * @property Request\Delivery $delivery
 * @property Request\Outlet $outlet
 */
class Request extends Model
{
    /**
     * @var string
     */
    protected $_uid;

    /**
     * @var integer
     */
    //protected $_created_at;

    /**
     * @var boolean
     */
    protected $_debug = false;

    /**
     * @var Request\Payment
     */
    protected $_payment;

    /**
     * @var Request\Shop
     */
    protected $_shop;

    /**
     * @var Request\Order
     */
    protected $_order;

    /**
     * @var Request\Basket[]
     */
    protected $_basket;

    /**
     * @var Request\Delivery
     */
    protected $_delivery;

    /**
     * @var Request\Outlet
     */
    protected $_outlet;

    /**
     * @param array $bodyParams
     * @param array $config
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws InvalidConfigException
     */
    public function __construct($bodyParams, $config = [])
    {
        if (empty($bodyParams) or !is_array($bodyParams)) {
            throw new InvalidConfigException('Запрос пустой или содержит некорректный тип данных');
        }
        if (!isset($bodyParams['request']) or !is_array($bodyParams['request'])) {
            throw new InvalidConfigException('Запрос должен содержать параметр "request"');
        }
        if (!Helper::checkCrc($bodyParams['request'], Config::$secretKey)) {
            throw new ForbiddenHttpException('Подпись запроса не прошла проверку');
        }
        try {
            $this->populateRequest($bodyParams['request']);
        } catch (Exception $e) {
            throw $e;
        }

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function fields()
    {
        return ['uid', 'debug', 'payment', 'shop', 'order', 'basket', 'delivery', 'outlet'];
    }

    public function populateRequest($data)
    {
        $this->_uid = $data['request']['uid'];
        $this->_debug = $data['debug'];
        $this->_shop = (new Request\Shop($data['shop']))->load($data['shop']);
        $this->_order = (new Request\Order())->load($data['order']);
        $this->_delivery = (new Request\Delivery())->load($data['delivery']);
        $this->_outlet = (new Request\Outlet())->load($data['outlet']);

        $basket = [];
        foreach ($data['basket'] as $item) {
            $item['offer'] = (new Request\Offer())->load($item['offer']);
            $basket[] = (new Request\Basket())->load($item);
        }
        $this->_basket = $basket;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->_uid;
    }

    /**
     * @return boolean
     */
    public function getDebug()
    {
        return $this->_debug;
    }

    /**
     * @return Request\Payment
     */
    public function getPayment()
    {
        return $this->_payment;
    }

    /**
     * @return Request\Shop
     */
    public function getShop()
    {
        return $this->_shop;
    }

    /**
     * @return Request\Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return Request\Basket[]
     */
    public function getBasket()
    {
        return $this->_basket;
    }

    /**
     * @return Request\Delivery
     */
    public function getDelivery()
    {
        return $this->_delivery;
    }

    /**
     * @return Request\Outlet
     */
    public function getOutlet()
    {
        return $this->_outlet;
    }
}
