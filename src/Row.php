<?php

namespace Table;

class Row extends Object implements \Countable
{

    private $cells = [];

    public function __construct($name = null)
    {
        $this->name     = null === $name ? 'row' : $name;
        $this->position = 0;
    }

    public function addCell($cell, $name = null)
    {
        if (!($cell instanceof $cell)) $cell = new Cell($cell, $name);

        $cell->position = count($this);
        $this->cells[]  = $cell;

        return $this;
    }

    public function get($position)
    {
        return isset($this->cells[$position]) ? $this->cells[$position] : null;
    }

    public function count()
    {
        return count($this->cells);
    }

    public function toArray()
    {
        $array          = parent::toArray();
        $array['cells'] = array_map(function ($cell) {
            return $cell->toArray();
        }, $this->cells);

        return $array;
    }

}