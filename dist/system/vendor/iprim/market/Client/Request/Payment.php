<?php
namespace iprim\market\Client\Request;

use iprim\market\common\Model;

/**
 * Class Payment
 * @package iprim\market\Client
 */
class Payment extends Model
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
     * @var integer
     */
    public $created_at;
}