<?php

namespace Bear\IO\Reader;

use Exception;
use Bear\IO\CsvAbstract;
use Bear\DataFrame;

/**
 * Carrega um data frame a partir de um arquivo CSV
 *
 * @author Everton
 */
class CsvReader extends CsvAbstract implements ReaderInterface
{

    /**
     *
     * @var bool Indica se o arquivo possui cabeçalhos.
     */
    protected bool $hasHead = true;

    /**
     * Cria uma nova instância do reader.
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
     * Informa se o reader está configurado para considerar o arquivo csv com
     * uma linha de cabeçalho ou não.
     *
     * @return bool
     * @see CsvReader::toggleHasHead()
     */
    public function hasHead(): bool
    {
        return $this->hasHead;
    }

    /**
     * consfigura o reader para considerar ou não o arquivo csv com uma linha de
     * cabeçalho.
     *
     * @param bool $toggle
     * @return CsvReader
     * @see CsvReader::hasHead()
     */
    public function toggleHasHead(bool $toggle): CsvReader
    {
        $this->hasHead = $toggle;
        return $this;
    }

    /**
     * Faz a leitura do arquivo CSV conforme as configurações do reader.
     *
     * @return DataFrame
     * @throws Exception
     */
    public function read(): DataFrame
    {
        $data = [];
        $colNames = [];

        //pula as linhas do início
        if ($this->startIn !== 0) {
            for ($i = 0; $i < $this->startIn; $i++) {
                //pula as linhas
                if ($this->readLength === 0) {
                    fgets($this->handle);
                }

                if ($this->readLength > 0) {
                    fgets($this->handle, $this->readLength);
                }
            }
        }

        if ($this->hasHead) {
            $colNames = (array) fgetcsv(
                $this->handle,
                $this->readLength,
                $this->delimiter,
                $this->enclosure,
                $this->escape
            );

            //não coberto por teste porque não sei (ainda) como causar um erro na leitura da linha neste ponto
            // @codeCoverageIgnoreStart
            if ($colNames === []) {
                throw new Exception('Falha ao ler o cabeçalho dos dados.');
            }// @codeCoverageIgnoreEnd
        }

        while (
            false !== ($buffer = fgetcsv(
                $this->handle,
                $this->readLength,
                $this->delimiter,
                $this->enclosure,
                $this->escape
            ))
        ) {
            $data[] = (array) $buffer;
        }

        $df = new DataFrame($data);
        if ($colNames !== [] && $data !== []) {
            $df->setColumnNames($colNames);
        }

        return $df;
    }

    /**
     * Fecha o ponteiro do arquivo.
     */
    public function __destruct()
    {
        @fclose($this->handle);
    }
}
