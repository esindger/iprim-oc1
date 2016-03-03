<?php
namespace iprim\market\Client\Request;

use iprim\market\common\Model;

/**
 * Class Shop
 * @package iprim\market\Client
 */
class Shop extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $alias;

    /**
     * @var string
     */
    public $name;
}