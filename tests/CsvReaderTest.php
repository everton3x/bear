<?php

use Bear\Exception\InvalidResourceException;
use Bear\Reader\CsvReader;
use PHPUnit\Framework\TestCase;
/**
 * Testes para CsvREader
 *
 * @author Everton
 * @todo Testes para CsvReader::read()
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
    
    public function testSetGetFilter()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $filter = function(string $row): bool {return true;};
        $this->assertInstanceOf(CsvReader::class, $reader->setFilter($filter));
        $this->assertEquals($filter, $reader->getFilter());
    }
}
