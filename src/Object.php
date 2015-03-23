<?php

namespace Table;

/**
 * Class Object
 * @package Table
 */
abstract class Object
{
    private $data = [];

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        if ($key == 'name') $value = trim(strval($value));
        if ($key == 'row') $value = (int)$value;
        if ($key == 'column') $value = (int)$value;

        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}