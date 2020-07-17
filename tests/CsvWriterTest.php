<?php

use Bear\DataFrame;
use Bear\IO\Reader\CsvReader;
use Bear\IO\Writer\CsvWriter;
use PHPUnit\Framework\TestCase;
/**
 * TEstes para CsvWriter
 *
 * @author Everton
 */
class CsvWriterTest extends TestCase
{
    protected array $df_1 = [
        [1, 'John', 39],
        [2, 'Mary', 37],
        [3, 'Paul', 12]
    ];
    
    public function testConstruct()
    {
        $filename = './tests/assets/output1.csv';
        $writer = new CsvWriter($filename);
        $this->assertInstanceOf(CsvWriter::class, $writer);
        unset($writer);
        $this->assertFileExists($filename);
    }
    
    public function testConstructFail()
    {
        $filename = './tests/asset/output1.csv';
        $this->expectException(Exception::class);
        $writer = new CsvWriter($filename);
    }
    
    public function testSetGetHasHead()
    {
        $filename = './tests/assets/output1.csv';
        $writer = new CsvWriter($filename);
        $this->assertInstanceOf(CsvWriter::class, $writer->toggleHasHead(false));
        $this->assertFalse($writer->hasHead());
    }
    
    public function testWrite()
    {
        $df = new DataFrame($this->df_1);
        $df->setColumnNames(['id', 'name', 'age']);
        $filename = './tests/assets/output1.csv';
        $writer = new CsvWriter($filename);
        $writer->write($df);
        $reader = new CsvReader($filename);
        $this->assertEquals($df->get(), $reader->read()->get());
    }
    
    public function testWriteNoHead()
    {
        $df = new DataFrame($this->df_1);
        $filename = './tests/assets/output1.csv';
        $writer = new CsvWriter($filename);
        $writer->toggleHasHead(false);
        $writer->write($df);
        $reader = new CsvReader($filename);
        $reader->toggleHasHead(false);
        $this->assertEquals($df->get(), $reader->read()->get());
    }
    
}
