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
        $this->assertEquals($df2, $df->lines([1, 2]));

        $df3 = new \Bear\DataFrame([$line0, $line1]);
        $this->assertEquals($df3, $df->lines('0:1'));

        $df4 = new \Bear\DataFrame([$line0, $line2]);
        $this->assertEquals($df4, $df->lines('0,2'));
    }

    public function testColumns()
    {
        $df = new \Bear\DataFrame($this->data);

        $df1 = new \Bear\DataFrame([
            [
                'name' => 'Fulano',
                'age' => 20
            ],
            [
                'name' => 'Sicrano',
                'age' => 30
            ],
            [
                'name' => 'Beltrano',
                'age' => 40
            ]
        ]);
        $this->assertEquals($df1, $df->columns('1:2'));

        $df2 = new \Bear\DataFrame([
            [
                'name' => 'Fulano',
                'age' => 20
            ],
            [
                'name' => 'Sicrano',
                'age' => 30
            ],
            [
                'name' => 'Beltrano',
                'age' => 40
            ]
        ]);
        $this->assertEquals($df2, $df->columns('name:age'));

        $df3 = new \Bear\DataFrame([
            [
                'id' => 1,
                'age' => 20
            ],
            [
                'id' => 2,
                'age' => 30
            ],
            [
                'id' => 3,
                'age' => 40
            ]
        ]);
        $this->assertEquals($df3, $df->columns('0,2'));

        $df4 = new \Bear\DataFrame([
            [
                'id' => 1,
                'age' => 20
            ],
            [
                'id' => 2,
                'age' => 30
            ],
            [
                'id' => 3,
                'age' => 40
            ]
        ]);
        $this->assertEquals($df4, $df->columns('id,age'));

        $df5 = new \Bear\DataFrame([
            [
                'id' => 1,
                'age' => 20
            ],
            [
                'id' => 2,
                'age' => 30
            ],
            [
                'id' => 3,
                'age' => 40
            ]
        ]);
        $this->assertEquals($df5, $df->columns([0, 2]));

        $df6 = new \Bear\DataFrame([
            [
                'id' => 1,
                'age' => 20
            ],
            [
                'id' => 2,
                'age' => 30
            ],
            [
                'id' => 3,
                'age' => 40
            ]
        ]);
        $this->assertEquals($df6, $df->columns(['id', 'age']));

        $df7 = new \Bear\DataFrame([
            [
                'age' => 20
            ],
            [
                'age' => 30
            ],
            [
                'age' => 40
            ]
        ]);
        $this->assertEquals($df7, $df->columns(2));

        $df8 = new \Bear\DataFrame([
            [
                'age' => 20
            ],
            [
                'age' => 30
            ],
            [
                'age' => 40
            ]
        ]);
        $this->assertEquals($df8, $df->columns('age'));
    }

    public function testCell()
    {
        $df = new \Bear\DataFrame($this->data);

        $this->assertEquals('Sicrano', $df->cell(1, 'name'));
    }

    public function testSize()
    {
        $df = new \Bear\DataFrame($this->data);

        $this->assertEquals(3, $df->size());
    }

    public function testGetIterator()
    {
        $df = new \Bear\DataFrame($this->data);

        $this->assertInstanceOf(ArrayIterator::class, $df->getIterator());
    }

    public function testIterateArray()
    {
        $df = new \Bear\DataFrame($this->data);

        $this->assertEquals([
            'id' => 1,
            'name' => 'Fulano',
            'age' => 20
            ],
            $df->iterate(false)
        );
    }
    
    public function testIterateObject()
    {
        $df = new \Bear\DataFrame($this->data);

        $this->assertEquals(1, $df->iterate(true)->id);
    }
}
