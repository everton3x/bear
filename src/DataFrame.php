<?php
namespace Bear;

use Bear\Exception\InvalidDataStructureException;

class DataFrame
{

    /**
     *
     * @var array Os dados do dataframe.
     */
    protected array $data;

    /**
     *
     * @var array Lista com os nomes das colunas.
     */
    protected array $columnNames;

    /**
     * Construtor do dataframe.
     *
     * @param array $data Os dados, composto por array multidimensional no formato linhas, colunas, células.
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        $this->columnNames = $this->readColumnNames();
    }

    /**
     * Detecta os nomes de campos.
     *
     * @return array Retorna um ArrayObject com os nomes de campos.
     */
    protected function readColumnNames(): array
    {
        return array_keys($this->data[0]);
    }

    /**
     * Pega os nomes de campos do dataframe.
     *
     * @return array Retorna um array com os nomes dos campos.
     */
    public function getColumnNames(): array
    {
        return $this->columnNames;
    }

    /**
     * Verifica se o dataframe obedece à estrutura correta.
     *
     * @return bool
     */
    public function checkStructure(): bool
    {
        foreach($this->data as $rowIndex => $row ) {

            if ($this->checkColumnNames($row) === false) {
                throw new InvalidDataStructureException($rowIndex, array_keys($row));
            }
        }

        return true;
    }

    /**
     * Testa se $dataToCheck é uma linha com todas as colunas necessárias.
     * 
     * @param array Uma linha do dataframe.
     * @return bool
     */
    protected function checkColumnNames(array $dataToCheck): bool
    {
        //verifica se existe diferença entre os campos detectados e os campos passados
        if (sizeof(array_diff_assoc(array_keys($dataToCheck), $this->getColumnNames())) !== 0) {
            return false;
        }

        return true;
    }

    /**
     * Soma as linhas de uma coluna.
     *
     * @param mixed $col Indica a coluna, pelo nome ou pela ordem.
     * @return float Retorna o valor da soma de todas as linhas da coluna indicada em $column.
     */
    public function sum($column): float
    {
        
    }
    
    /**
     * Pega uma coluna inteira.
     * 
     * @param type $column
     * @return array
     */
    protected function getColumn($column): array
    {
        if(is_int($column)){
            $column = $this->getColumnNameByIndex($column);
        }
    }
    
    /**
     * Pega o nome da coluna de acordo com o seu index.
     * 
     * @param int $index
     * @return string
     */
    protected function getColumnNameByIndex(int $index): string
    {
        return $this->columnNames[$index];
    }

    /**
     * Seleciona linhas em um dataframe.
     *
     * @param mixed $lines Indica uma ou mais linhas ou um intervalo de linhas. As linhas sempre começam em 0. Por exemplo: use 10, para retornar a 11ª linha; use um array com os números das linhas desejadas para um conjunto de linhas, não sequenciais; ou use 0:20 para as linhas de 0 a 19; ou use 15:21, para as linhas de 15 a 20.
     * @return DataFrame Retorna um dataframe com as linhas desejadas.
     */
    public function line($lines): DataFrame
    {
        
    }

    /**
     * Seleciona colunas em um dataframe.
     *
     * @param mixed $columns Indica uma ou mais colunas para retornar. As colunas podem ser informadas pelos seus rótulos ou pela sua posição, começando em 0. Por exemplo: use 2 para a 3ª coluna; use colName para a coluna rotulada colName; use um array com os índices ou nomes das colunas desejadas, ou use uma sequência como 0:2 para as colunas 1, 2 e 3, ou colname1:colname3 para as colunas de rótulo colnam1 até colname3.
     * @return DataFrame Retorna um novo dataframe com as colunas selecionadas.
     */
    public function columns($columns): DataFrame
    {
        
    }

    /**
     * Seleciona uma célula específica no dataset.
     *
     * @param mixed $column O índice ou rótulo da coluna.
     * @param int $line O índice da linha.
     * @return mixed Retorna o conteúdo da célula.
     */
    public function cell($column, int $line)
    {
        
    }

    /**
     * Sumariza o dataframe com informações relevantes e de estatística descritiva
     *
     * @return array Retorna um array com informações sumarizadas do dataframe.
     */
    public function summary(): array
    {
        
    }
}
