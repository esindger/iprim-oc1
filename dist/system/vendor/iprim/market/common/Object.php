<?php
namespace iprim\market\common;

class Object
{
    /**
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            foreach ($config as $name => $value) {
                $this->$name = $value;
            }
        }
        $this->init();
    }

    /**
     *
     */
    public function init()
    {
    }

    /**
     * @param $name
     * @return mixed
     * @throws InvalidConfigException
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new InvalidConfigException('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new InvalidConfigException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * @param $name
     * @param $value
     * @throws InvalidConfigException
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidConfigException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new InvalidConfigException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        } else {
            return false;
        }
    }

    /**
     * @param $name
     * @throws InvalidConfigException
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidConfigException('Unsetting read-only property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * @param $name
     * @param $params
     * @throws InvalidConfigException
     */
    public function __call($name, $params)
    {
        throw new InvalidConfigException('Calling unknown method: ' . get_class($this) . "::$name()");
    }

    /**
     * @param $name
     * @param bool|true $check_vars
     * @return bool
     */
    public function hasProperty($name, $check_vars = true)
    {
        return $this->canGetProperty($name, $check_vars) || $this->canSetProperty($name, false);
    }

    /**
     * @param $name
     * @param bool|true $check_vars
     * @return bool
     */
    public function canGetProperty($name, $check_vars = true)
    {
        return method_exists($this, 'get' . $name) || $check_vars && property_exists($this, $name);
    }

    /**
     * @param $name
     * @param bool|true $check_vars
     * @return bool
     */
    public function canSetProperty($name, $check_vars = true)
    {
        return method_exists($this, 'set' . $name) || $check_vars && property_exists($this, $name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }
}
