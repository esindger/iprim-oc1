<?php
namespace iprim\market\Client\Request;

use iprim\market\common\Model;

/**
 * Class Basket
 * @package iprim\market\Client
 */
class Basket extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var Offer
     */
    public $offer;

    /**
     * @var integer
     */
    public $price;

    /**
     * @var integer
     */
    public $number;

    /**
     * @var integer
     */
    public $amount;

    /**
     * @var integer
     */
    public $created_at;
}