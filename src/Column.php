<?php

namespace Table;

/**
 * Class Column
 * @package Table
 *
 * @property string $name
 * @property int    $column
 */
class Column extends Row
{

    /**
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $this->name   = null === $name ? 'column' : $name;
        $this->column = 0;
    }

    public function __set($key, $value)
    {
        if ($key == 'column') {
            $value = (int)$value;

            foreach ($this->cells as $row => $cell) $this->cells[$row]->column = $value;
        }

        parent::__set($key, $value);
    }

    /**
     * @param Cell|string $cell
     * @param null|string $name
     *
     * @return $this
     */
    public function addCell($cell, $name = null)
    {
        $cell = Table::buildCell($cell, $name);
        $cell->column  = $this->column;
        $cell->row     = count($this);
        $this->cells[] = $cell;

        return $this;
    }
}