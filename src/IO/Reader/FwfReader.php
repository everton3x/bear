<?php

namespace Bear\IO\Reader;

use Exception;
use Bear\DataFrame;
use Bear\IO\FwfAbstract;

/**
 * Reader para arquivos com campos de largura fixa.
 *
 * @author Everton
 */
class FwfReader extends FwfAbstract implements ReaderInterface
{

    /**
     *
     * @var callable|null Uma função que recebe a linha do arquivo de texto e
     * retorna true ou false conforme a linha deva ou não ser importada para o
     * data frame. A lógica de decisão sobre o retorno true|false fica a cargo
     * do programador.
     */
    protected $filter = null;
    public function __construct(string $filename)
    {
        $handle = @fopen($filename, 'r');
        if ($handle === false) {
            throw new Exception(sprintf('Arquivo [%s] inacessível.', $filename));
        }

        $this->handle = $handle;
    }

    public function setFilter(callable $filter): FwfReader
    {
        $this->filter = $filter;
        return $this;
    }

    public function getFilter(): ?callable
    {
        return $this->filter;
    }

    public function read(): DataFrame
    {
        if ($this->model === []) {
            throw new Exception('Um modelo de dados precisa ser definido.');
        }

        $data = [];

        //pula as linhas do início
        if ($this->startIn !== 0) {
            for ($i = 0; $i < $this->startIn; $i++) {
            //pula as linhas
                if ($this->readLength === 0) {
                    fgets($this->handle);
                }

                //não coberto por teste porque não sei (ainda) como causar um erro na leitura da linha neste ponto
                // @codeCoverageIgnoreStart
                if ($this->readLength > 0) {
                    fgets($this->handle, $this->readLength);
                }
                // @codeCoverageIgnoreEnd
            }
        }

        /**
         * @todo Adaptar para possibilitar leitura pelo comprimento de linha também.
         */
        while (false !== ($buffer = fgets($this->handle))) {
            $buffer = trim($buffer);
            if ($buffer === '') {
            //pula linhas em branco
                continue;
            }

            if (is_callable($this->filter)) {
                $filter = $this->filter;
                if ($filter($buffer) === false) {
                    continue;
                }
            }

            $row = [];
            foreach ($this->model as $key => $spec) {
                $start = $spec['start'];
                $len = $spec['len'];
                $value = substr($buffer, $start, $len);
                if (key_exists('transform', $spec)) {
                    $value = $spec['transform']($value);
                }
                
                if (key_exists('type', $spec)) {
                    settype($value, $spec['type']);
                }
                
                $row[$key] = $value;
            }
            
            $data[] = $row;
        }

        $df = new DataFrame($data);
//cria as colunas
        $colNames = [];
        foreach ($this->model as $key => $spec) {
            $colNames[] = $key;
        }
        $df->setColumnNames($colNames);
        return $df;
    }
}
