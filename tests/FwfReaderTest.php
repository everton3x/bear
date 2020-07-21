<?php

use Bear\DataFrame;
use Bear\IO\Reader\FwfReader;
use PHPUnit\Framework\TestCase;

/**
 * Teste para FwfReader
 *
 * @author Everton
 */
class FwfReaderTest extends TestCase
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
            'start' => 18,
            'len' => 2,
            'type' => 'int'
        ]
    ];

    public function testContruct()
    {
        $reader = new FwfReader('./tests/assets/sample6.fwf');
        $this->assertInstanceOf(FwfReader::class, $reader);
    }

    public function testContructFail()
    {
        $this->expectException(Exception::class);
        $reader = new FwfReader('./tests/assets/unknow.fwf');
    }

    public function testSetGetModel()
    {
        $reader = new FwfReader('./tests/assets/sample6.fwf');
        $this->assertInstanceOf(FwfReader::class, $reader->setModel($this->model_1));
        $this->assertEquals($this->model_1, $reader->getModel());
    }

    public function testSetModelFailNoStart()
    {
        $this->expectException(Exception::class);
        $reader = new FwfReader('./tests/assets/sample6.fwf');
        $reader->setModel([
            'id' => [
                'start' => 0,
                'len' => 4
            ],
            'name' => [
                'len' => 13
            ],
            'age' => [
                'start' => 18,
                'len' => 2
            ]
        ]);
    }

    public function testSetModelFailNoLen()
    {
        $this->expectException(Exception::class);
        $reader = new FwfReader('./tests/assets/sample6.fwf');
        $reader->setModel([
            'id' => [
                'start' => 0,
                'len' => 4
            ],
            'name' => [
                'start' => 0,
                'len' => 13
            ],
            'age' => [
                'start' => 18
            ]
        ]);
    }
    
    public function testSetModelFailStartNotInt()
    {
        $this->expectException(Exception::class);
        $reader = new FwfReader('./tests/assets/sample6.fwf');
        $reader->setModel([
            'id' => [
                'start' => 0,
                'len' => 4
            ],
            'name' => [
                'start' => 'abc',
                'len' => 13
            ],
            'age' => [
                'start' => 18,
                'len' => 2
            ]
        ]);
    }
    
    public function testSetModelFailLenNotInt()
    {
        $this->expectException(Exception::class);
        $reader = new FwfReader('./tests/assets/sample6.fwf');
        $reader->setModel([
            'id' => [
                'start' => 0,
                'len' => 4
            ],
            'name' => [
                'start' => 4,
                'len' => 'abc'
            ],
            'age' => [
                'start' => 18,
                'len' => 2
            ]
        ]);
    }
    
    public function testSetGetFilter()
    {
        $reader = new FwfReader('./tests/assets/sample6.fwf');
        $filter = function(string $line): bool {
            return true;
        };
        $this->assertInstanceOf(FwfReader::class, $reader->setFilter($filter));
        $this->assertEquals($filter, $reader->getFilter());
    }
    
    public function testRead()
    {
        $reader = new FwfReader('./tests/assets/sample6.fwf');
        $reader->setModel($this->model_1);
        $df = $reader->read();
        $this->assertInstanceOf(DataFrame::class, $df);
        $this->assertEquals(
            [
                ['id'=>1, 'name'=>'John', 'age'=>39],
                ['id'=>2, 'name'=>'Mary', 'age'=>37],
                ['id'=>3, 'name'=>'Paul', 'age'=>12]
            ],
            $df->get()
        );
    }
    
    public function testReadWithFilter()
    {
        $reader = new FwfReader('./tests/assets/sample6.fwf');
        $reader->setModel($this->model_1);
        $reader->setFilter(function(string $row): bool {
            if(substr($row, 0, 4) === '0003'){return false;}else{return true;}
        });
        $df = $reader->read();
        $this->assertInstanceOf(DataFrame::class, $df);
        $this->assertEquals(
            [
                ['id'=>1, 'name'=>'John', 'age'=>39],
                ['id'=>2, 'name'=>'Mary', 'age'=>37]
            ],
            $df->get()
        );
    }
    
    public function testReadSkipLines()
    {
        $reader = new FwfReader('./tests/assets/sample7.fwf');
        $reader->setModel($this->model_1);
        $reader->setStartIn(2);
        $df = $reader->read();
        $this->assertInstanceOf(DataFrame::class, $df);
        $this->assertEquals(
            [
                ['id'=>1, 'name'=>'John', 'age'=>39],
                ['id'=>2, 'name'=>'Mary', 'age'=>37],
                ['id'=>3, 'name'=>'Paul', 'age'=>12]
            ],
            $df->get()
        );
    }
    
    public function testReadNoModelFail()
    {
        $reader = new FwfReader('./tests/assets/sample7.fwf');
        $this->expectException(Exception::class);
        $df = $reader->read();
    }
}
