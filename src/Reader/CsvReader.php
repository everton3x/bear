<?php
namespace Bear\Reader;

use Bear\Base\CsvAbstract;
use Bear\DataFrame;
use Bear\Exception\InvalidResourceException;
use Bear\Exception\ParseException;

/**
 * Carrega um data frame a partir de um arquivo CSV
 *
 * @author Everton
 */
class CsvReader extends CsvAbstract implements ReaderInterface
{
    /**
     *
     * @var handle Ponteiro para o arquivo csv.
     */
    protected $handle = null;
    
    /**
     *
     * @var bool Indica se o arquivo possui cabeçalhos.
     */
    protected bool $hasHead = true;
    
    /**
     *
     * @var int Indica a primeira linha para importar.
     */
    protected int $startIn = 0;
    
    /**
     *
     * @var callable Função para filtro de linhas.
     */
    protected $filter = null;
    
    /**
     *
     * @var int Número máximo de linhas a serem lidas.
     */
    protected int $readLength = 0;
    
    /**
     *
     * @var int Tamanho da linha para ser usado em fgetcsv()
     */
    protected int $length = 0;


    public function __construct(string $filename)
    {
        $this->handle = @fopen($filename, 'r');
        
        if($this->handle === false){
            throw new InvalidResourceException($filename);
        }
        
    }

    public function hasHead(): bool
    {
        return $this->hasHead;
    }
    public function toggleHasHead(bool $toggle): CsvReader
    {
        $this->hasHead = $toggle;
        return $this;
    }
    
    public function setStartIn(int $startIn): CsvReader
    {
        $this->startIn = $startIn;
        return $this;
    }
    
    public function getStartIn(): int
    {
        return $this->startIn;
    }
    
    public function setReadLength(int $readLength): CsvReader
    {
        $this->readLength = $readLength;
        return $this;
    }
    
    public function getReadLength(): int
    {
        return $this->readLength;
    }
    
    public function setFilter(callable $filter): CsvReader
    {
        $this->filter = $filter;
        return $this;
    }
    
    public function getFilter(): callable
    {
        return $this->filter;
    }
    
    public function read(): DataFrame
    {
        $data = [];
        $colNames = [];
        
        //pula as linhas do início
        if($this->startIn !== 0){
            for ($i = 0; $i < $this->startIn; $i++) {
                fgets($this->handle, $this->readLength);//pula as linhas
            }
        }
        
        if($this->hasHead){
            $colNames = fgetcsv($this->handle, $this->readLength, $this->delimiter, $this->enclosure, $this->escape);
            if($colNames === false){
                throw new ParseException(sprintf('%s::%s on %s', __CLASS__, __METHOD__, 'csv header'));
            }
        }
        
        while (false !== ($buffer = fgetcsv($this->handle, $this->readLength, $this->delimiter, $this->enclosure, $this->escape))) {
            if(true !== $this->filter(join($this->delimiter, $buffer))){
                $data[] = $buffer;
            }
        }
        
        $df = new DataFrame($data);
        
        if($colNames === []){
            $df->setColumnNames($colNames);
        }
        
        return $df;
    }
}
