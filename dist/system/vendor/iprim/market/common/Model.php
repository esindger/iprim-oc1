<?php
namespace iprim\market\common;

use iprim\market\Client\Config;

class Model extends Object
{
    /**
     * @param array $data
     * @return $this
     * @throws InvalidConfigException
     */
    public function load($data = [])
    {
        if (!empty($data)) {
            foreach ($data as $name => $value) {
                if ($this->canSetProperty($name)) {
                    $this->$name = $value;
                } elseif (Config::$debug) {
                    throw new InvalidConfigException('Setting unknown property: ' . get_class($this) . '::' . $name);
                }
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $class = new \ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

    /**
     * @return array
     */
    public function fields()
    {
        return $this->attributes();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [];
        foreach ($this->fields() as $name) {
            $value = $this->$name;
            if($value instanceof Model) {
                $value = $value->toArray();
            }
            $data[$name] = $value;
        }

        return $data;
    }
}
