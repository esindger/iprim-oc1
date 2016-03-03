<?php

require_once __DIR__ . '../autoload.php';

// Секретный ключ магазина
\iprim\market\Client\Config::$secretKey = '123456';

$client = new \iprim\market\Client();

// Принимаем заказ с market.iprim.ru
try {
    $request = $client->request;
} catch (\iprim\market\common\Exception $e) {
    $response = $client->response;
    $response->uid = @$_POST['request']['request']['uid'];
    $response->exception = $e;
    $response->send();
    exit;
}

try {

    // TODO: Обработка заказа
    // ...
    //
    $orderId = 1;
    $paid = 1;
    $comment = 'Заказ оформлен, скоро мы с Вами свяжемся для подтверждения данных.';

     // Заказ зарегистрирован
    $response = $client->response;
    $response->uid = $request->uid; // UID запроса
    $response->status = 'process';
    $response->internal_id = $orderId;
    $response->paid = $paid;
    $response->comment = $comment;

    /**
     * ПРИМЕРЫ:
     *
     * ~~~
     * // Ошибка приема заказа
     * $response->status = 'error';
     * $response->comment = 'Товара нет в наличии';
     *
     * // Заказ отменен
     * $response->status = 'cancel';
     * $response->comment = 'Данные пользователя не корректны';
     *
     * // Заказ выполнен
     * $response->status = 'complete';
     * $response->comment = 'Заказ выполнен';
     * ~~~
     *
     * Примечание: при изменении статуса заказа или других его параметров (оплачен или нет),
     * необходимо отправить повторный ответ на market.iprim.ru с тем же UID запроса
     * и указанием свойства `background` в значении 1:
     *
     * ~~~
     * $response->paid = 1;
     * $response->background = 1;
     * ~~~
     */

    $response->send();

} catch (\iprim\market\common\Exception $e) {
    $response = $client->response;
    $response->uid = $request->uid;
    $response->exception = $e;
    $response->send();
}