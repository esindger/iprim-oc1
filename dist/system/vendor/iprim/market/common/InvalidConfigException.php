<?php
namespace iprim\market\common;

/**
 * Class InvalidConfigException
 * @package iprim\market\Client
 */
class InvalidConfigException extends Exception
{
    public function getName()
    {
        return 'Ошибка конфигурации';
    }
}
