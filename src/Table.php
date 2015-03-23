<?php

namespace Table;

/**
 * Class Table
 * @package Table
 *
 * @property string $name
 * @property int    $position
 */
class Table extends Object implements \Countable
{

    /** @var array */
    private $rows = [];

    /**
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $this->name = null === $name ? 'table' : $name;
    }

    /**
     * @param Row|array $row
     *
     * @return $this
     */
    public function addRow($row)
    {
        if (is_array($row)) {
            $cells    = $row;
            $row      = new Row();

            foreach ($cells as $key => $value) {
                $name = is_int($key) ? null : $key;
                $row->addCell(new Cell($value, $name));
            }
        }

        if (!($row instanceof Row)) throw new \InvalidArgumentException();

        $row->row     = count($this);
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @param int      $row_position
     * @param int|null $cell_position
     *
     * @return Row|Cell|null
     */
    public function get($row_position, $cell_position = null)
    {
        return null === $cell_position ? $this->getRow($row_position) : $this->getCell($row_position, $cell_position);
    }

    /**
     * @param int $row_position
     *
     * @return Row|null
     */
    public function getRow($row_position)
    {
        return isset($this->rows[$row_position]) ? $this->rows[$row_position] : null;
    }

    /**
     * @param int $row_position
     * @param int $cell_position
     *
     * @return Cell|null
     */
    public function getCell($row_position, $cell_position)
    {
        $row = $this->getRow($row_position);

        return $row ? $row->get($cell_position) : null;
    }

    /**
     * @param int $column_position
     *
     * @return Column
     */
    public function getColumn($column_position)
    {
        $column = new Column;

        foreach ($this->rows as $row) {
            $cell = $row->get($column_position);

            if ($cell) $column->addCell($cell);
        }

        return $column;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->rows);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array         = parent::toArray();
        $array['rows'] = array_map(function ($row) {
            return $row->toArray();
        }, $this->rows);

        return $array;
    }

}