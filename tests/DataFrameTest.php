<?php

use Bear\DataFrame;
use Bear\Exception\InvalidColumnNameException;
use Bear\Exception\InvalidDataStructureException;
use Bear\Exception\InvalidRowCountException;
use Bear\Exception\OutOfBoundsException;
use PHPUnit\Framework\TestCase;

/**
 * Testes para DataFrame class
 *
 * @author everton
 */
class DataFrameTest extends TestCase
{

    protected array $df_1 = [
        [1, 'John', 39],
        [2, 'Mary', 37],
        [3, 'Paul', 12]
    ];
    protected array $df_2 = [
        [4, 'Jack', 30],
        [5, 'Mart', 20],
        [6, 'Phill', 10]
    ];
    protected array $df_3 = [
        ['m'],
        ['f'],
        ['m']
    ];

    public function testConstructEmpty()
    {
        $this->assertInstanceOf(DataFrame::class, new DataFrame([]));
    }

    public function testConstructor()
    {
        $df = new DataFrame($this->df_1);
        $this->assertInstanceOf(DataFrame::class, $df);
        $this->assertEquals($this->df_1, $df->get());
    }

    public function testConstructBadStructure()
    {
        $this->expectException(InvalidDataStructureException::class);
        $dfFail = new DataFrame([
            [1, 'John', 39],
            [2, 'Mary'], //esta linha tem menos colunas
            [3, 'Paul', 12]
        ]);
    }

    public function testCountRows()
    {
        $df = new DataFrame($this->df_1);
        $this->assertEquals(3, $df->countRows());
    }

    public function testCountRowsDataFrameEmpty()
    {
        $df = new DataFrame([]);
        $this->assertEquals(0, $df->countRows());
    }

    public function testCountColumns()
    {
        $df = new DataFrame($this->df_1);
        $this->assertEquals(3, $df->countColumns());
    }

    public function testCountColumnsDataFrameEmpty()
    {
        $df = new DataFrame([]);
        $this->assertEquals(0, $df->countColumns());
    }

    public function testGetColumnsNames()
    {
        $df = new DataFrame($this->df_1);
        $colNames = ['id', 'name', 'age'];
        $df->setColumnNames($colNames);
        $this->assertEquals($colNames, $df->getColumnNames());
    }

    public function testGetColumnsNamesDataFrameEmpty()
    {
        $df = new DataFrame([]);
        $this->assertEquals([], $df->getColumnNames());
    }

    public function testGet()
    {
        $df = new DataFrame($this->df_1);
        $this->assertEquals($this->df_1, $df->get());
    }

    public function testGetDataFrameEmpty()
    {
        $df = new DataFrame([]);
        $this->assertEquals([], $df->get());
    }

    public function testGetAndSetColNames()
    {
        $df = new DataFrame($this->df_1);
        $colNames = ['cod', 'nome', 'idade'];
        $this->assertInstanceOf(DataFrame::class, $df->setColumnNames($colNames));
        $this->assertEquals($colNames, $df->getColumnNames());
    }

    public function testGetColumnsByIndex()
    {
        $df = new DataFrame($this->df_1);
//        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getColumnsByIndex([0, 2]);
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            [0 => 1, 2 => 39],
            [0 => 2, 2 => 37],
            [0 => 3, 2 => 12]
            ], $df2->get());
    }

    public function testGetColumnsByIndexFail()
    {
        $df = new DataFrame($this->df_1);
        $this->expectException(OutOfBoundsException::class);
        $df2 = $df->getColumnsByIndex([0, 3]);
    }

    public function testGetColumnsByName()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getColumnsByName(['id', 'age']);
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['id' => 1, 'age' => 39],
            ['id' => 2, 'age' => 37],
            ['id' => 3, 'age' => 12]
            ], $df2->get());
    }

    public function testGetColumnsByNameFail()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(OutOfBoundsException::class);
        $df2 = $df->getColumnsByName(['id', 'unknow']);
    }

    public function testGetColumnsRangeByIndex()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getColumnsRangeByIndex(1, 2);
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['name' => 'John', 'age' => 39],
            ['name' => 'Mary', 'age' => 37],
            ['name' => 'Paul', 'age' => 12]
            ], $df2->get());
    }

    public function testGetColumnsRangeByIndexEndStartOrderFail()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(InvalidArgumentException::class);
        $df2 = $df->getColumnsRangeByIndex(2, 1);
    }

    public function testGetColumnsRangeByIndexStartFail()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(OutOfBoundsException::class);
        $df2 = $df->getColumnsRangeByIndex(4, 2);
    }

    public function testGetColumnsRangeByIndexEndFail()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(OutOfBoundsException::class);
        $df2 = $df->getColumnsRangeByIndex(0, 4);
    }

    public function testGetColumnsRangeByIndexNotEndKey()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getColumnsRangeByIndex(1);
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['name' => 'John', 'age' => 39],
            ['name' => 'Mary', 'age' => 37],
            ['name' => 'Paul', 'age' => 12]
            ], $df2->get());
    }

    public function testGetColumnsRangeByIndexNotStartKey()
    {
        $df = new DataFrame($this->df_1);
        $df2 = $df->getColumnsRangeByIndex(null, 1);
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            [1, 'John'],
            [2, 'Mary'],
            [3, 'Paul']
            ], $df2->get());
    }

    public function testGetColumnsRangeByName()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getColumnsRangeByName('name', 'age');
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['name' => 'John', 'age' => 39],
            ['name' => 'Mary', 'age' => 37],
            ['name' => 'Paul', 'age' => 12]
            ], $df2->get());
    }

    public function testGetColumnsRangeByNameNotEndKey()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getColumnsRangeByName('name');
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['name' => 'John', 'age' => 39],
            ['name' => 'Mary', 'age' => 37],
            ['name' => 'Paul', 'age' => 12]
            ], $df2->get());
    }

    public function testGetColumnsRangeByNameNotStartKey()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getColumnsRangeByName(null, 'name');
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Mary'],
            ['id' => 3, 'name' => 'Paul']
            ], $df2->get());
    }

    public function testGetColumnsRangeByNameStartFail()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(OutOfBoundsException::class);
        $df2 = $df->getColumnsRangeByName('unknow', 'age');
    }

    public function testGetColumnsRangeByNameEndFail()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(OutOfBoundsException::class);
        $df2 = $df->getColumnsRangeByName('name', 'unknow');
    }

    public function testSetColumnNamesFail()
    {
        $df = new DataFrame($this->df_1);
        $this->expectException(InvalidArgumentException::class);
        $df->setColumnNames(['id', 'name']);
    }

    public function testGetRows()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getRows([1, 2]);
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['id' => 2, 'name' => 'Mary', 'age' => 37],
            ['id' => 3, 'name' => 'Paul', 'age' => 12]
            ], $df2->get());
    }

    public function testGetRowsFails()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(OutOfBoundsException::class);
        $df2 = $df->getRows([1, 4]);
    }

    public function testGetRowRange()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getRowRange(1, 2);
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['id' => 2, 'name' => 'Mary', 'age' => 37],
            ['id' => 3, 'name' => 'Paul', 'age' => 12]
            ], $df2->get());
    }

    public function testGetRowRangeNoStart()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getRowRange(null, 1);
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['id' => 1, 'name' => 'John', 'age' => 39],
            ['id' => 2, 'name' => 'Mary', 'age' => 37]
            ], $df2->get());
    }

    public function testGetRowRangeNoEnd()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $df2 = $df->getRowRange(1, null);
        $this->assertInstanceOf(DataFrame::class, $df2);
        $this->assertEquals([
            ['id' => 2, 'name' => 'Mary', 'age' => 37],
            ['id' => 3, 'name' => 'Paul', 'age' => 12]
            ], $df2->get());
    }

    public function testGetRowRangeFailStart()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(OutOfBoundsException::class);
        $df2 = $df->getRowRange(4, 8);
    }

    public function testGetRowRangeFailEnd()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(OutOfBoundsException::class);
        $df2 = $df->getRowRange(0, 8);
    }

    public function testGetRowRangeFailEndStart()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $this->expectException(InvalidArgumentException::class);
        $df2 = $df->getRowRange(2, 1);
    }

    public function testMergeByRows()
    {
        $df = new DataFrame($this->df_1);
        $df2 = new DataFrame($this->df_2);
        $merged = $df->mergeByRows($df2);
        $this->assertInstanceOf(DataFrame::class, $merged);
        $this->assertEquals(array_merge($this->df_1, $this->df_2), $merged->get());
    }

    public function testMergeByRowsNumColsFail()
    {
        $df = new DataFrame($this->df_1);
        $df2 = new DataFrame([
            [4, 'Jack'],
            [5, 'Mart'],
            [6, 'Phill']
        ]);
        $this->expectException(InvalidColumnNameException::class);
        $merged = $df->mergeByRows($df2);
    }

    public function testMergeByRowsColNamesFail()
    {
        $df = new DataFrame($this->df_1);
        $df2 = new DataFrame($this->df_2);
        $df2->setColumnNames(['cod', 'nome', 'idade']);
        $this->expectException(InvalidColumnNameException::class);
        $merged = $df->mergeByRows($df2);
    }

    public function testMergeByColumns()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['cod', 'nome', 'idade']);
        $df2 = new DataFrame($this->df_3);
        $df2->setColumnNames(['sexo']);
        $merged = $df->mergeByColumns($df2);
        $this->assertInstanceOf(DataFrame::class, $merged);
        $this->assertEquals(
            [
                ['cod' => 1, 'nome' => 'John', 'idade' => 39, 'sexo' => 'm'],
                ['cod' => 2, 'nome' => 'Mary', 'idade' => 37, 'sexo' => 'f'],
                ['cod' => 3, 'nome' => 'Paul', 'idade' => 12, 'sexo' => 'm']
            ],
            $merged->get());
    }
    
    public function testMergeByColumnsNoColNames()
    {
        $df = new DataFrame($this->df_1);
        $df2 = new DataFrame($this->df_3);
        $this->expectException(InvalidColumnNameException::class);
        $merged = $df->mergeByColumns($df2);
    }

    public function testMergeByColumnsFail()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['cod', 'nome', 'idade']);
        $df2 = new DataFrame([
            ['m'],
            ['m']
        ]);
        $df2->setColumnNames(['sexo']);
        $this->expectException(InvalidRowCountException::class);
        $merged = $df->mergeByColumns($df2);
    }
}
