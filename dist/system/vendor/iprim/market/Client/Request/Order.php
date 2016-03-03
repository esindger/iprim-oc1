<?php
namespace iprim\market\Client\Request;

use iprim\market\common\Model;

/**
 * Class Order
 * @package iprim\market\Client
 */
class Order extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $uid;

    /**
     * @var string
     */
    public $shortUid;

    /**
     * @var integer
     */
    public $number;

    /**
     * @var integer
     */
    public $amount;

    /**
     * @var string
     */
    public $customer_name;

    /**
     * @var string
     */
    public $customer_phone;

    /**
     * @var string
     */
    public $customer_email;

    /**
     * @var string
     */
    public $customer_comment;

    /**
     * @var integer
     */
    public $created_at;
}