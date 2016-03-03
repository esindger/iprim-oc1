<?php
namespace iprim\market\Client\Request;

use iprim\market\common\Model;

/**
 * Class Offer
 * @package iprim\market\Client
 */
class Offer extends Model
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
     * @var integer
     */
    public $price;

    /**
     * @var string
     */
    public $source_id;

    /**
     * @var string
     */
    public $source_url;

    /**
     * @var integer
     */
    public $created_at;
}