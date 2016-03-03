<?php

$loadList = [
    'common/Object',
    'common/Model',
    'common/Exception',
    'common/HttpException',
    'common/ForbiddenHttpException',
    'common/InvalidConfigException',
    'Client',
    'Client/Config',
    'Client/Helper',
    'Client/Request',
    'Client/Request/Basket',
    'Client/Request/Delivery',
    'Client/Request/Offer',
    'Client/Request/Order',
    'Client/Request/Outlet',
    'Client/Request/Payment',
    'Client/Request/Shop',
    'Client/Response',
];


foreach ($loadList as $item) {
    require_once __DIR__ . '/' . $item . '.php';
}
