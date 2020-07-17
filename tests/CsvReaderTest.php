<?php

use Bear\Exception\InvalidResourceException;
use Bear\IO\Reader\CsvReader;
use PHPUnit\Framework\TestCase;

/**
 * Testes para CsvREader
 *
 * @author Everton
 */
class CsvReaderTest extends TestCase
{

    public function testConstruct()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $this->assertInstanceOf(CsvReader::class, $reader);
    }

    public function testConstructInvalidFile()
    {
        $this->expectException(InvalidResourceException::class);
        $reader = new CsvReader('./tests/assets/unknow.csv');
    }

    public function testHasHead()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $this->assertTrue($reader->hasHead());
    }

    public function testToggleHasHead()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $this->assertInstanceOf(CsvReader::class, $reader->toggleHasHead(false));
        $this->assertFalse($reader->hasHead());
    }

    public function testSetGetStartIn()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $this->assertInstanceOf(CsvReader::class, $reader->setStartIn(1));
        $this->assertEquals(1, $reader->getStartIn());
    }

    public function testSetGetReadLength()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $this->assertInstanceOf(CsvReader::class, $reader->setReadLength(1));
        $this->assertEquals(1, $reader->getReadLength());
    }

    public function testRead()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $df = $reader->read();
        $this->assertInstanceOf(\Bear\DataFrame::class, $df);
        $this->assertEquals([
                ['id' => 1, 'name' => 'John', 'age' => 39],
                ['id' => 2, 'name' => 'Mary', 'age' => 37],
                ['id' => 3, 'name' => 'Paul', 'age' => 12]
            ],
            $df->get());
    }
    
    public function testReadStartIn()
    {
        $reader = new CsvReader('./tests/assets/sample2.csv');
        $reader->setStartIn(2);
        $df = $reader->read();
        $this->assertInstanceOf(\Bear\DataFrame::class, $df);
        $this->assertEquals([
                ['id' => 1, 'name' => 'John', 'age' => 39],
                ['id' => 2, 'name' => 'Mary', 'age' => 37],
                ['id' => 3, 'name' => 'Paul', 'age' => 12]
            ],
            $df->get());
    }
    
    public function testReadNoHead()
    {
        $reader = new CsvReader('./tests/assets/sample3.csv');
        $reader->toggleHasHead(false);
        $df = $reader->read();
        $this->assertInstanceOf(\Bear\DataFrame::class, $df);
        $this->assertEquals([
                [0 => 1, 1 => 'John', 2 => 39],
                [0 => 2, 1 => 'Mary', 2 => 37],
                [0 => 3, 1 => 'Paul', 2 => 12]
            ],
            $df->get());
    }
}
