<?php

namespace Table;

class Table extends Object implements \Countable
{

    private $rows = [];

    public function __construct($name = null)
    {
        $this->name = null === $name ? 'table' : $name;
    }

    public function addRow($row)
    {
        if (is_array($row)) {
            $cells = $row;
            $row   = new Row();

            foreach ($cells as $key => $value) {
                $name = is_int($key) ? null : $key;
                $row->addCell(new Cell($value, $name));
            }
        }

        if (!($row instanceof Row)) throw new \InvalidArgumentException();

        $row->position = count($this);
        $this->rows[]  = $row;

        return $this;
    }

    public function get($row_position, $cell_position = null)
    {
        $row = isset($this->rows[$row_position]) ? $this->rows[$row_position] : null;

        return $row && $cell_position ? $row->get($cell_position) : $row;
    }

    public function count()
    {
        return count($this->rows);
    }

    public function toArray()
    {
        $array         = parent::toArray();
        $array['rows'] = array_map(function ($row) {
            return $row->toArray();
        }, $this->rows);

        return $array;
    }

}