<?php
namespace iprim\market\Client;

class Config
{
    const CALLBACK_URL = 'http://market.iprim.dev/market/client/callback';

    /**
     * @var bool
     */
    public static $debug = true;

    /**
     * Секретный ключ
     * @See https://market.iprim.ru/SHOP_ID/admin/config/index?tab=client
     * @var string $secretKey
     */
    public static $secretKey;
}