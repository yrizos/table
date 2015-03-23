<?php

namespace Table;

class Cell extends Object
{

    public function __construct($value = null, $name = null)
    {
        $this->value    = $value;
        $this->name     = null === $name ? 'cell' : $name;
        $this->position = 0;
    }

}