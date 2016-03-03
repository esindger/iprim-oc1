<?php

require_once(__DIR__ . '/iprim/market/autoload.php');
require_once(__DIR__ . '/iprim/BaseModelIprimRequest.php');
require_once(__DIR__ . '/iprim/BaseModelIprimResponse.php');

class Iprim {

    static private $marketClient;

	static public function marketClient() {
		return self::$marketClient ?: self::$marketClient = new \iprim\market\Client();
	}
}
