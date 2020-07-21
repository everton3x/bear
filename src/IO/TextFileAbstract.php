<?php

namespace Bear\IO;

/**
 * Classe base para fontes de dados de arquivo texto.
 *
 * @author Everton
 */
abstract class TextFileAbstract
{
    /**
     *
     * @var int Tamanho da linha para ser usado em fgetcsv()
     * @todo Eu não sei onde isto está sendo usado. Ver no futuro.
     */
    protected int $length = 0;
    
    /**
     *
     * @var resource Ponteiro para o arquivo csv.
     */
    protected $handle = null;
    
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
     * Configura por qual linha a importação deve começar (incluindo a linha de
     * cabeçalho, se houver).
     *
     * @param int $startIn
     * @return TextFileAbstract
     * @see TextFileAbstract::getStartIn()
     */
    public function setStartIn(int $startIn): TextFileAbstract
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
     * @return TextFileAbstract
     * @see TextFileAbstract::getReadLength()
     */
    public function setReadLength(int $readLength): TextFileAbstract
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
    
    abstract public function __construct(string $filename);
}
