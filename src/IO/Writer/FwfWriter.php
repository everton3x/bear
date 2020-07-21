<?php

namespace Bear\IO\Writer;

use Exception;
use Bear\DataFrame;
use Bear\IO\FwfAbstract;

/**
 * Writer para arquivos de campos de largura fixa
 *
 * @author Everton
 */
class FwfWriter extends FwfAbstract implements WriterInterface
{

    /**
     *
     * @var resource Ponteiro para o arquivo csv.
     */
    protected $handle = null;
    
    /**
     *
     * @var string String padrão para preencher espaços. Pode ser substituída
     * pela especificação de coluna `pad_str` no modelo de colunas.
     */
    protected string $defaultPadString = ' ';
    
    /**
     *
     * @var int String padrão para a direção do preenchimento de espaços.
     * Pode ser substituída pela especificação de coluna `pad_type` no modelo
     * de colunas.
     */
    protected int $defaultPadType = \STR_PAD_RIGHT;
    
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
     * Escreve o data frame para o arquivos csv.
     *
     * @param DataFrame $df
     * @return void
     */
    public function write(DataFrame $df): void
    {
        
        $data = $df->get();
        foreach ($data as $index => $row) {
            $writed = @fputs($this->handle, $this->buildDataLine($row));
            // @codeCoverageIgnoreStart
            if ($writed === false) {
                throw new Exception(sprintf('Falha ao escrever a linha [%d].', $index));
            }
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Monta a linha de dados.
     *
     * @param array<mixed> $data
     * @return string
     */
    protected function buildDataLine(array $data): string
    {
        $line = '';
        foreach ($this->model as $colName => $spec) {
            $len = $spec['len'];
            $padStr = $spec['pad_str'] ?? $this->defaultPadString;
            $padType = $spec['pad_type'] ?? $this->defaultPadType;
            $line .= str_pad($data[$colName], $len, $padStr, $padType);
        }

        return $line . PHP_EOL;
    }
    
    /**
     * Fecha o ponteiro do arquivo.
     */
    public function __destruct()
    {
        @fclose($this->handle);
    }
}
