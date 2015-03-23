<?php
namespace TableTest;

use Table\Cell;
use Table\Column;
use Table\Row;
use Table\Table;

class TableTest extends \PHPUnit_Framework_TestCase
{
    public function testCellConstructor()
    {
        $cell = new Cell();

        $this->assertEquals('cell', $cell->name);
        $this->assertEquals(0, $cell->column);
        $this->assertEquals(0, $cell->row);
        $this->assertNull($cell->value);

        $cell = new Cell('value', '  name  ');

        $this->assertEquals('value', $cell->value);
        $this->assertEquals('name', $cell->name);

        $cell->row    = '5';
        $cell->column = '5';

        $this->assertEquals(5, $cell->column);
        $this->assertEquals(5, $cell->row);
    }

    public function testRowConstructor()
    {
        $row = new Row();

        $this->assertEquals('row', $row->name);
        $this->assertEquals(0, $row->row);

        $row = new Row(' name ');

        $this->assertEquals('name', $row->name);

        $row->row = '5';

        $this->assertSame(5, $row->row);
    }

    public function testTableConstructor()
    {
        $table = new Table();

        $this->assertEquals('table', $table->name);

        $table = new Table(' name ');

        $this->assertEquals('name', $table->name);
    }

    public function testRowAddCell()
    {
        $row1  = new Row();
        $row2  = new Row();
        $cells = [];

        for ($i = 0; $i < 5; $i++) $cells[] = 'cell-value-' . $i;

        foreach ($cells as $cell) {
            $row1->addCell($cell);
            $row2->addCell(new Cell($cell));
        }

        $this->assertEquals(count($cells), count($row1));
        $this->assertEquals(count($cells), count($row2));
        $this->assertEquals($row1->toArray(), $row2->toArray());

        $array = $row1->toArray();
        $cells = $array['cells'];


        foreach ($cells as $column_number => $cell) {
            $this->assertEquals($column_number, $cell['column']);
            $this->assertEquals('cell-value-' . $column_number, $cell['value']);
        }
    }

    public function testColumnAddCell()
    {
        $column1 = new Column();
        $column2 = new Column();
        $cells   = [];

        for ($i = 0; $i < 5; $i++) $cells[] = 'cell-value-' . $i;

        foreach ($cells as $cell) {
            $column1->addCell($cell);
            $column2->addCell(new Cell($cell));
        }

        $this->assertEquals(count($cells), count($column1));
        $this->assertEquals(count($cells), count($column2));
        $this->assertEquals($column1->toArray(), $column2->toArray());

        $array = $column1->toArray();
        $cells = $array['cells'];

        foreach ($cells as $row_number => $cell) {
            $this->assertEquals($row_number, $cell['row']);
            $this->assertEquals('cell-value-' . $row_number, $cell['value']);
        }
    }

    public function testTableAddRow()
    {
        $table1 = new Table();
        $table2 = new Table();
        $data   = [];

        for ($r = 0; $r < 5; $r++) {
            $data[$r] = [];

            for ($c = 0; $c < 5; $c++) {
                $data[$r][$c] = 'cell-' . $r . '-' . $c;
            }
        }

        foreach ($data as $row1) {
            $table1->addRow($row1);

            $row2 = new Row();

            foreach ($row1 as $cell) $row2->addCell($cell);

            $table2->addRow($row2);
        }

        $this->assertEquals(count($data), count($table1));
        $this->assertEquals(count($data), count($table2));
        $this->assertEquals($table1->toArray(), $table2->toArray());


        foreach ($data as $row_number => $row) {
            $r = $table1->get($row_number);
            $this->assertEquals(count($row), count($r));

            foreach ($row as $column_number => $cell) {
                $c1 = $table1->get($row_number, $column_number);
                $c2 = $r->get($column_number);

                $this->assertEquals($c1->toArray(), $c2->toArray());
                $this->assertEquals($cell, $c1->value);
                $this->assertEquals($column_number, $c1->column);
                $this->assertEquals($row_number, $c1->row);
            }
        }
    }

}