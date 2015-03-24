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

    /** @var Row */
    private $header;

    /**
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $this->name = null === $name ? 'table' : $name;
    }

    /**
     * @param Cell|string $cell
     * @param null|string $name
     *
     * @return $this
     */
    public function addHeader($cell, $name = null)
    {
        $this->getHeader()->addCell(self::buildCell($cell, $name));

        return $this;
    }

    /**
     * @return Row
     */
    public function getHeader()
    {
        if (null == $this->header) $this->header = new Row();

        return $this->header;
    }

    /**
     * @param Row|array $row
     *
     * @return $this
     */
    public function addRow($row)
    {
        $row          = self::buildRow($row);
        $row->row     = count($this);
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
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
        $header   = $this->getHeader();
        $rows     = $this->getRows();
        $c_header = count($header);
        $c_rows   = !empty($rows) ? max(array_map('count', $rows)) : 0;
        $c_max    = max($c_header, $c_rows);

        $header = self::fillRow($header, $c_max)->toArray();
        $rows   = array_map(function ($row) use ($c_max) {
            return self::fillRow($row, $c_max)->toArray();
        }, $rows);

        $array           = parent::toArray();
        $array['header'] = $header;
        $array['rows']   = $rows;

        return $array;
    }

    public static function fillRow(Row $row, $length)
    {
        $diff = $length - count($row);
        if ($diff > 0) {
            for ($i = 0; $i < $diff; $i++) $row->addCell('');
        }

        return $row;
    }


    /**
     * @param Cell|string $cell
     * @param null|string $name
     *
     * @return Cell
     */
    public static function buildCell($cell, $name = null)
    {
        if (!($cell instanceof Cell)) $cell = new Cell($cell);
        if (null !== $name) $cell->name = $name;

        return $cell;
    }

    /**
     * @param Row|array $row
     *
     * @return Row
     */
    public static function buildRow($row)
    {
        if (is_array($row)) $row = self::populateRow(new Row(), $row);
        if (!($row instanceof Row)) throw new \InvalidArgumentException();

        return $row;
    }

    /**
     * @param Row   $row
     * @param array $cells
     *
     * @return Row
     */
    public static function populateRow(Row $row, array $cells)
    {
        foreach ($cells as $name => $cell) {
            $name = is_numeric($name) ? null : $name;
            $cell = self::buildCell($cell, $name);

            $row->addCell($cell);
        }

        return $row;
    }

}