<?php

use Bear\IO\CsvAbstract;
use Bear\IO\Reader\CsvReader;
use PHPUnit\Framework\TestCase;

/**
 * Testes para CsvAbstrat
 *
 * @author Everton
 * @todo Desmembrar em classes extendíveis para contemplar os métodos das classes abstratas.
 */
class CsvAbstractTest extends TestCase
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
