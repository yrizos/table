<?php

namespace Table;

/**
 * Class Row
 * @package Table
 *
 * @property string $name
 * @property int    $row
 */
class Row extends Object implements \Countable
{

    /** @var array */
    protected $cells = [];

    /**
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $this->name = null === $name ? 'row' : $name;
        $this->row  = 0;
    }

    public function __set($key, $value)
    {
        if ($key == 'row') {
            $value = (int)$value;

            foreach ($this->cells as $column => $cell) $this->cells[$column]->row = $value;
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
        if (!($cell instanceof $cell)) $cell = new Cell($cell, $name);

        $cell->column  = count($this);
        $cell->row     = $this->row;
        $this->cells[] = $cell;

        return $this;
    }

    /**
     * @param int $position
     *
     * @return null
     */
    public function get($position)
    {
        return isset($this->cells[$position]) ? $this->cells[$position] : null;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->cells);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array          = parent::toArray();
        $array['cells'] = array_map(function ($cell) {
            return $cell->toArray();
        }, $this->cells);

        return $array;
    }
}