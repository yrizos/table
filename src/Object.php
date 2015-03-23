<?php

namespace Table;

abstract class Object
{
    private $data = [];

    public function __set($key, $value)
    {
        if ($key == 'name') $value = trim(strval($value));
        if ($key == 'position') $value = (int)$value;

        $this->data[$key] = $value;
    }

    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public function toArray()
    {
        return $this->data;
    }
}