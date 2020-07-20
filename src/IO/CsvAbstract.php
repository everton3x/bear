<?php

namespace Bear\IO;

/**
 * Classe base para trabalhar com arquivos csv
 *
 * @author Everton
 */
abstract class CsvAbstract extends TextFileAbstract
{
    protected string $delimiter = ';';
    
    protected string $enclosure = '"';
    
    protected string $escape = '\\';
    
    public function setDelimiter(string $delimiter): CsvAbstract
    {
        $this->delimiter = $delimiter;
        return $this;
    }
    
    public function setEnclosure(string $enclosure): CsvAbstract
    {
        $this->enclosure = $enclosure;
        return $this;
    }
    
    public function setEscape(string $escape): CsvAbstract
    {
        $this->escape = $escape;
        return $this;
    }
    
    public function getDelimiter(): string
    {
        return $this->delimiter;
    }
    
    public function getEnclosure(): string
    {
        return $this->enclosure;
    }
    
    public function getEscape(): string
    {
        return $this->escape;
    }
}
