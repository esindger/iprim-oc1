<?php
namespace iprim\market\Client\Request;

use iprim\market\common\Model;

/**
 * Class Delivery
 * @package iprim\market\Client
 */
class Delivery extends Model
{
    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $address;

    /**
     * @var integer
     */
    public $price;
}