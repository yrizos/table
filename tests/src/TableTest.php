<?php
namespace TableTest;

use Table\Cell;
use Table\Row;
use Table\Table;

class TableTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructors()
    {
        $cell = new Cell();

        $this->assertEquals('cell', $cell->name);
        $this->assertEquals(0, $cell->position);
        $this->assertNull($cell->value);

        $cell = new Cell('value', '  name  ');

        $this->assertEquals('value', $cell->value);
        $this->assertEquals('name', $cell->name);

        $cell->position = '5';

        $this->assertSame(5, $cell->position);
    }

    public function testRowConstructor()
    {
        $row = new Row();

        $this->assertEquals('row', $row->name);
        $this->assertEquals(0, $row->position);

        $row = new Row(' name ');

        $this->assertEquals('name', $row->name);

        $row->position = '5';

        $this->assertSame(5, $row->position);
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

        foreach ($array['cells'] as $position => $cell) {
            $this->assertEquals('cell-value-' . $position, $cell['value']);
            $this->assertEquals($position, $cell['position']);
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

        foreach ($data as $row) {
            $table1->addRow($row);

            $r = new Row();
            foreach ($row as $cell) {
                $r->addCell($cell);
            }

            $table2->addRow($r);
        }

        $this->assertEquals(count($data), count($table1));
        $this->assertEquals(count($data), count($table2));
        $this->assertEquals($table1->toArray(), $table2->toArray());

        foreach ($data as $row_positon => $row) {
            $r = $table1->get($row_positon);
            $this->assertEquals(count($row), count($r));

            foreach ($row as $cell_position => $cell) {
                $c1 = $table1->get($row_positon, $cell_position);
                $c2 = $r->get($cell_position);

                $this->assertEquals($cell, $c1->value);
                $this->assertEquals($cell_position, $c1->position);
                $this->assertEquals($c1->toArray(), $c2->toArray());
            }
        }
    }
}