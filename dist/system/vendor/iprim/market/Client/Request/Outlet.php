<?php
namespace iprim\market\Client\Request;

use iprim\market\common\Model;

/**
 * Class Outlet
 * @package iprim\market\Client
 */
class Outlet extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $address;
}