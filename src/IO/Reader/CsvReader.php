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
     * @var resource Ponteiro para o arquivo csv.
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
     * @var int Número máximo de linhas a serem lidas.
     */
    protected int $readLength = 0;

    /**
     *
     * @var int Tamanho da linha para ser usado em fgetcsv()
     */
    protected int $length = 0;

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
     * Configura por qual linha a importação deve começar (incluindo a linha de
     * cabeçalho, se houver).
     *
     * @param int $startIn
     * @return CsvReader
     * @see CsvReader::getStartIn()
     */
    public function setStartIn(int $startIn): CsvReader
    {
        $this->startIn = $startIn;
        return $this;
    }

    /**
     * Indica qual linha o reader começará a leitura, incluindo linha de
     * cabeçalho, se houver.
     *
     * @return int
     * @see CsvReader::setStartIn()
     */
    public function getStartIn(): int
    {
        return $this->startIn;
    }

    /**
     * Configura o tamanho da linha que será lido. Se não configurado, a leitura
     * para na primeira quebra de linha encontrada.
     *
     * @param int $readLength
     * @return CsvReader
     * @see CsvReader::getReadLength()
     */
    public function setReadLength(int $readLength): CsvReader
    {
        $this->readLength = $readLength;
        return $this;
    }

    /**
     * Indica o tamanho de linha que será lido. Se zero, a leitura para na
     * primeira quebra de linha encontrada.
     *
     * @return int
     * @see CsvReader::setReadLength()
     */
    public function getReadLength(): int
    {
        return $this->readLength;
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
