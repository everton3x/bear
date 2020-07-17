<?php

namespace Bear\IO\Writer;

use Bear\DataFrame;
use Bear\IO\CsvAbstract;
use Bear\IO\Reader\CsvReader;
use Exception;

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
        $handle = @fopen($filename, 'w');
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
     * @return CsvWriter
     * @see CsvWriter::hasHead()
     */
    public function toggleHasHead(bool $toggle): CsvWriter
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
        if ($this->hasHead) {
            $writed = @fputcsv($this->handle, $df->getColumnNames(), $this->delimiter, $this->enclosure, $this->escape);
            // @codeCoverageIgnoreStart
            if ($writed === false) {
                throw new Exception(sprintf('Falha ao escrever cabeçalho [%s].', join(', ', $df->getColumnNames())));
            }
            // @codeCoverageIgnoreEnd
        }
        
        $data = $df->get();
        foreach ($data as $index => $row) {
            $writed = @fputcsv($this->handle, $row, $this->delimiter, $this->enclosure, $this->escape);
            // @codeCoverageIgnoreStart
            if ($writed === false) {
                throw new Exception(sprintf('Falha ao escrever a linha [%d].', $index));
            }
            // @codeCoverageIgnoreEnd
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
