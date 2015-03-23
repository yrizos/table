<?php

namespace Table;

/**
 * Class Cell
 * @package Table
 *
 * @property string $name
 * @property mixed  $value
 * @property int    $row
 * @property int    $column
 */
class Cell extends Object
{

    /**
     * @param mixed       $value
     * @param string|null $name
     */
    public function __construct($value = null, $name = null)
    {
        $this->value  = $value;
        $this->name   = null === $name ? 'cell' : $name;
        $this->row    = 0;
        $this->column = 0;
    }
}