<?php
namespace Bear\IO\Writer;

use Bear\DataFrame;
use Bear\IO\CsvAbstract;

/**
 * Escreve um data frame para arquivo csv.
 *
 * @author Everton
 */
class CsvWriter extends CsvAbstract implements WriterInterface
{
    /**
     *
     * @var resource Ponteiro para o arquivo csv.
     */
    protected $handle = null;
    
    /**
     *
     * @var bool Indica se o data frame será salvo com os nomes das colunas 
     * na primeira linha.
     */
    protected bool $hasHead = true;


    /**
     * Cria uma instância do writer.
     * 
     * @param string $filename
     * @throws Exception
     */
    public function __construct(string $filename)
    {
        $handle = @fopen($filename, 'r');
        if ($handle === false) {
            throw new Exception(sprintf('Arquivo [%s] inacessível.', $filename));
        }
        
        $this->handle = $handle;
    }
    
    /**
     * Informa se o writer irá colocar uma linha de cabeçalho ou não.
     *
     * @return bool
     * @see CsvWriter::toggleHasHead()
     */
    public function hasHead(): bool
    {
        return $this->hasHead;
    }
    
    /**
     * Consfigura se o writer irá colocar uma linha de cabeçalho ou não.
     *
     * @param bool $toggle
     * @return CsvReader
     * @see CsvWriter::hasHead()
     */
    public function toggleHasHead(bool $toggle): CsvReader
    {
        $this->hasHead = $toggle;
        return $this;
    }

    /**
     * Escreve o data frame para o arquivos csv.
     * 
     * @param DataFrame $df
     * @return void
     */
    public function write(DataFrame $df): void
    {
        //escreve o cabeçalho
        if($this->hasHead){
            $writed = @fputcsv(
                $this->handle,
                $df->getColumnNames(),
                $this->delimiter,
                $this->enclosure,
                $this->escape
            );
            
            if($writed === false){
                throw new \Exception(sprintf('Falha ao escrever cabeçalho [%s].', join(', ', $df->getColumnNames())));
            }
        }
        
        $data = $df->get();
        
        foreach ($data as $index => $row){
            $writed = @fputcsv(
                $this->handle,
                $row,
                $this->delimiter,
                $this->enclosure,
                $this->escape
            );
            
            if($writed === false){
                throw new \Exception(sprintf('Falha ao escrever a linha [%d].', $index));
            }
        }
    }
    
    /**
     * Fecha o ponteiro do arquivo.
     */
    public function __destruct()
    {
        @fclose($this->handle);
    }
}
