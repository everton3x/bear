<?php
namespace Bear\Reader;

use Bear\Base\CsvAbstract;
use Bear\DataFrame;
use Bear\Exception\InvalidResourceException;

/**
 * Carrega um data frame a partir de um arquivo CSV
 *
 * @author Everton
 */
class CsvReader extends CsvAbstract implements ReaderInterface
{
    protected $handle = null;
    
    public function __construct(string $filename)
    {
        $this->handle = @fopen($filename, 'r');
        
        if($this->handle === false){
            throw new InvalidResourceException($filename);
        }
        
    }

    public function read(): DataFrame
    {
        
    }
}
