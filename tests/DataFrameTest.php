<?php

/**
 * TEstes para DataFrame class
 *
 * @author everton
 */
class DataFrameTest extends PHPUnit\Framework\TestCase
{

    protected array $data = [
        [
            'id' => 1,
            'name' => 'Fulano',
            'age' => 20
        ],
        [
            'id' => 2,
            'name' => 'Sicrano',
            'age' => 30
        ],
        [
            'id' => 3,
            'name' => 'Beltrano',
            'age' => 40
        ]
    ];

    public function testGetColumnNames()
    {
        $df = new \Bear\DataFrame($this->data);

        $this->assertEquals(['id', 'name', 'age'], $df->getColumnNames());
    }

    public function testCheckStructure()
    {
        $df = new \Bear\DataFrame($this->data);

        $this->assertTrue($df->checkStructure());
    }

    public function testSum()
    {
        $df = new \Bear\DataFrame($this->data);

        $this->assertEquals(90, $df->sum('age'));
        $this->assertEquals(90, $df->sum(2));
    }

    public function testGetColumnNameByIndex()
    {
        $df = new \Bear\DataFrame($this->data);
        $this->assertEquals('id', $df->getColumnNameByIndex(0));
        $this->assertEquals('name', $df->getColumnNameByIndex(1));
        $this->assertEquals('age', $df->getColumnNameByIndex(2));
    }

    public function testLines()
    {
        
        $line0 = [
            'id' => 1,
            'name' => 'Fulano',
            'age' => 20
        ];
        $line1 = [
            'id' => 2,
            'name' => 'Sicrano',
            'age' => 30
        ];
        $line2 = [
            'id' => 3,
            'name' => 'Beltrano',
            'age' => 40
        ];
        
        $df = new \Bear\DataFrame($this->data);
        
        $df1 = new \Bear\DataFrame([$line1]);
        $this->assertEquals($df1, $df->lines(1));
        
        $df2 = new \Bear\DataFrame([$line1, $line2]);
        $this->assertEquals($df2, $df->lines([1,2]));
        
        $df3 = new \Bear\DataFrame([$line0, $line1]);
        $this->assertEquals($df3, $df->lines('0:1'));
        
        $df4 = new \Bear\DataFrame([$line0, $line2]);
        $this->assertEquals($df4, $df->lines('0,2'));
    }
    
    public function testColumns()
    {
        
    }
    
    public function testCell()
    {
        
    }
    
    public function testSize()
    {
        
    }
    
    
}
