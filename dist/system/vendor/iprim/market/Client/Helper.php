<?php
namespace iprim\market\Client;

use iprim\market\common\InvalidConfigException;

class Helper
{
    /**
     * @param array $array
     * @param string $secretKey
     * @return string
     */
    public static function createCrc($array, $secretKey)
    {
        self::_serialize($array, $str);
        return md5($secretKey . $str);
    }

    /**
     * @param array $array
     * @param string $secretKey
     * @return bool
     */
    public static function checkCrc($array, $secretKey)
    {
        if (!isset($array['crc']) or !is_string($array['crc'])) {
            return false;
        }
        $crc = $array['crc'];
        unset($array['crc']);
        self::_serialize($array, $str);

        return $crc == self::createCrc($array, $secretKey);
    }

    /**
     * @param $array
     * @param $str
     */
    public static function _serialize($array, &$str)
    {
        ksort($array);
        foreach ($array as $key => $value) {
            if($value === null || $value === '') {
                continue;
            }
            $str .= $key;
            if (is_array($value)) {
                self::_serialize($value, $str);
            } else {
                $str .= preg_replace('~[^a-zа-яё0-9]+~', '', $value);
            }
        }
    }

    /**
     * @return string
     */
    public static function getAbsoluteUrl()
    {
        return self::getHostInfo() . self::getRequestUri();
    }

    /**
     * @return string
     */
    public static function getHostInfo()
    {
        $secure = self::_getIsSecureConnection();
        $http = $secure ? 'https' : 'http';
        if (isset($_SERVER['HTTP_HOST'])) {
            $hostInfo = $http . '://' . $_SERVER['HTTP_HOST'];
        } else {
            $hostInfo = $http . '://' . $_SERVER['SERVER_NAME'];
            $port = $secure ? self::getSecurePort() : self::getPort();
            if (($port !== 80 && !$secure) || ($port !== 443 && $secure)) {
                $hostInfo .= ':' . $port;
            }
        }
        
        return $hostInfo;
    }

    /**
     * @return int
     */
    public static function getSecurePort()
    {
        return self::_getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : 443;
    }

    /**
     * @return int
     */
    public static function getPort()
    {
        return !self::_getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : 80;
    }

    /**
     * @return mixed|string
     * @throws InvalidConfigException
     */
    public static function getRequestUri()
    {
        if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // IIS
            $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            if ($requestUri !== '' && $requestUri[0] !== '/') {
                $requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $requestUri);
            }
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0 CGI
            $requestUri = $_SERVER['ORIG_PATH_INFO'];
            if (!empty($_SERVER['QUERY_STRING'])) {
                $requestUri .= '?' . $_SERVER['QUERY_STRING'];
            }
        } else {
            throw new InvalidConfigException('Unable to determine the request URI.');
        }

        return $requestUri;
    }

    /**
     * @return bool
     */
    public static function _getIsSecureConnection()
    {
        return isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1)
        || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
    }

    /**
     * @return null
     */
    public static function getReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    /**
     * @return null
     */
    public static function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * @return null
     */
    public static function getUserIP()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }
}