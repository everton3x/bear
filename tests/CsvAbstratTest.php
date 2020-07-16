<?php

use Bear\Base\CsvAbstract;
use Bear\Reader\CsvReader;
use PHPUnit\Framework\TestCase;

/**
 * Testes para CsvAbstrat
 *
 * @author Everton
 */
class CsvAbstratTest extends TestCase
{
    public function testSetGetDelimiter()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $this->assertInstanceOf(CsvAbstract::class, $reader->setDelimiter(','));
        $this->assertEquals(',', $reader->getDelimiter());
    }
    
    public function testSetGetEnclosure()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $this->assertInstanceOf(CsvAbstract::class, $reader->setEnclosure('\''));
        $this->assertEquals('\'', $reader->getEnclosure());
    }
    
    public function testSetGetEscape()
    {
        $reader = new CsvReader('./tests/assets/sample1.csv');
        $this->assertInstanceOf(CsvAbstract::class, $reader->setEscape(':'));
        $this->assertEquals(':', $reader->getEscape());
    }
}
