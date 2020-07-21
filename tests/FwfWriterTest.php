<?php

use Bear\DataFrame;
use Bear\IO\Reader\FwfReader;
use Bear\IO\Writer\FwfWriter;
use PHPUnit\Framework\TestCase;
/**
 * Testes para FwfWriter
 *
 * @author Everton
 */
class FwfWriterTest extends TestCase
{
    protected array $df_1 = [
        [1, 'John', 39],
        [2, 'Mary', 37],
        [3, 'Paul', 12]
    ];
    protected array $model_1 = [
        'id' => [
            'start' => 0,
            'len' => 4,
            'type' => 'int'
        ],
        'name' => [
            'start' => 4,
            'len' => 13,
            'transform' => 'trim'
        ],
        'age' => [
            'start' => 17,
            'len' => 2,
            'type' => 'int'
        ]
    ];
    
    public function testContruct()
    {
        $writer = new FwfWriter('./tests/assets/output1.fwf');
        $this->assertInstanceOf(FwfWriter::class, $writer);
    }

    public function testContructFail()
    {
        $this->expectException(Exception::class);
        $writer= new FwfWriter('./tests/unknow/unknow.fwf');
    }
    
    public function testWrite()
    {
        $filename = './tests/assets/output1.fwf';
        $writer = new FwfWriter($filename);
        $writer->setModel($this->model_1);
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        
        $writer->write($df);
        
        $reader = new FwfReader($filename);
        $reader->setModel($this->model_1);
        
        $df2 = $reader->read();

        $this->assertEquals($df->get(), $df2->get());
        
    }
}
