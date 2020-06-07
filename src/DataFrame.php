<?php
namespace Bear;

use Bear\Exception\InvalidColumnNameException;
use Bear\Exception\InvalidDataStructureException;
use Bear\Exception\InvalidPatternException;
use Bear\Exception\OutOfRangeException;
use InvalidArgumentException;

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
        foreach ($this->data as $rowIndex => $row) {

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
        if (is_int($column)) {
            $column = $this->getColumnNameByIndex($column);
        }

        $sum = 0.0;
        foreach ($this->getColumn([$column]) as $row) {
            $sum += $row[$column];
        }
        return $sum;
    }

    /**
     * Filtra as colunas de $columnNames.
     * 
     * @param array $columnNames
     * @return array
     */
    protected function getColumn(array $columnNames): array
    {
        $dataFiltered = [];

        foreach ($this->data as $rowIndex => $row) {
            foreach ($columnNames as $column) {
                $dataFiltered[$rowIndex][$column] = $row[$column];
            }
        }

        return $dataFiltered;
    }

    /**
     * Pega o nome da coluna de acordo com o seu index.
     * 
     * @param int $index
     * @return string
     */
    public function getColumnNameByIndex(int $index): string
    {
        if (key_exists($index, $this->columnNames) === false) {
            throw new OutOfRangeException($index);
        }

        return $this->columnNames[$index];
    }

    /**
     * Seleciona linhas em um dataframe.
     *
     * @param mixed $lines Indica uma ou mais linhas ou um intervalo de linhas. As linhas sempre começam em 0. Por exemplo: use 10, para retornar a 11ª linha; use um array com os números das linhas desejadas para um conjunto de linhas, não sequenciais; ou use 0:20 para as linhas de 0 a 19; ou use 15:21, para as linhas de 15 a 20.
     * @return DataFrame Retorna um dataframe com as linhas desejadas.
     */
    public function lines($lines): DataFrame
    {
        if (is_string($lines)) {
            if (preg_match('/^(\d)(:)(\d)$/', $lines) === 1) {//sequência
                $range = explode(':', $lines);
                if ($range[0] < $range[1]) {
                    $min = $range[0];
                    $max = $range[1];
                } else {
                    $min = $range[1];
                    $max = $range[0];
                }
                $lines = range($min, $max);
            } elseif (preg_match('/^(\d)(,\d){0,}$/', $lines) === 1) {//conjunto
                $lines = explode(',', $lines);
            } else {//erro
                throw new InvalidPatternException($lines);
            }
        } elseif (is_array($lines)) {
            //não faz nada
        } elseif (is_int($lines)) {
            $lines = [$lines];
        } else {//erro
            throw new InvalidArgumentException('Invalid argument detected.');
        }

        foreach ($lines as $index) {
            if (key_exists($index, $this->data) === false) {
                throw new OutOfRangeException($index);
            }

            $linesFiltered[] = $this->data[$index];
        }
        return new DataFrame($linesFiltered);
    }

    /**
     * Pega o índex da coluna para um dado nome de coluna.
     * 
     * @param string $columnName
     * @return int
     * @throws InvalidColumnNameException
     */
    protected function getColumnIndexFor(string $columnName): int
    {
        $index = array_search($columnName, $this->getColumnNames());

        if (is_null($index)) {
            throw new InvalidColumnNameException($columnName);
        }

        return $index;
    }

    /**
     * Seleciona colunas em um dataframe.
     *
     * @param mixed $columns Indica uma ou mais colunas para retornar. As colunas podem ser informadas pelos seus rótulos ou pela sua posição, começando em 0. Por exemplo: use 2 para a 3ª coluna; use colName para a coluna rotulada colName; use um array com os índices ou nomes das colunas desejadas, ou use uma sequência como 0:2 para as colunas 1, 2 e 3, ou colname1:colname3 para as colunas de rótulo colnam1 até colname3.
     * @return DataFrame Retorna um novo dataframe com as colunas selecionadas.
     */
    public function columns($columns): DataFrame
    {
        if (is_string($columns)) {
            if (preg_match('/^[:,]/', $columns) === 1) {
                $data = $this->getColumn([$columns]);
            } elseif (preg_match('/^(\d)(:)(\d)$/', $columns) === 1) {
                $columns = explode(':', $columns);
                if ($columns[0] < $columns[1]) {
                    $min = $columns[0];
                    $max = $columns[1];
                } else {
                    $min = $columns[1];
                    $max = $columns[0];
                }

                $range = range($min, $max);
                foreach ($range as $index) {
                    $columnNames[] = $this->getColumnNameByIndex($index);
                }

                $data = $this->getColumn($columnNames);
            } elseif (preg_match('/^([A-Za-z0-9_]+)(:)([A-Za-z0-9_]+)$/', $columns) === 1) {
                $columns = explode(':', $columns);
                $column1 = $this->getColumnIndexFor($columns[0]);
                $column2 = $this->getColumnIndexFor($columns[1]);

                if ($column1 < $column2) {
                    $min = $column1;
                    $max = $column2;
                } else {
                    $min = $column2;
                    $max = $column1;
                }

                $range = range($min, $max);

                foreach ($range as $index) {
                    $columnNames[] = $this->getColumnNameByIndex($index);
                }

                $data = $this->getColumn($columnNames);
            } elseif (preg_match('/^(\d)(,\d){0,}$/', $columns) === 1) {
                $range = explode(',', $columns);
                foreach ($range as $index) {
                    $columnNames[] = $this->getColumnNameByIndex($index);
                }
                $data = $this->getColumn($columnNames);
            } elseif (preg_match('/^([A-Za-z0-9_]+)(,[A-Za-z0-9_]+){0,}$/', $columns) === 1) {
                $columnNames = explode(',', $columns);
                $data = $this->getColumn($columnNames);
            } else {
                throw new InvalidPatternException($columns);
            }
        } elseif (is_array($columns)) {
            if (is_int($columns[0])) {
                foreach ($columns as $index) {
                    $columnNames[] = $this->getColumnNameByIndex($index);
                }
                $data = $this->getColumn($columnNames);
            } elseif (is_string($columns[0])) {
                $data = $this->getColumn($columns);
            } else {
                throw new InvalidArgumentException('Invalid argument detected.');
            }
        } elseif (is_int($columns)) {
            $columnNames[] = $this->getColumnNameByIndex($columns);
            $data = $this->getColumn($columnNames);
        } else {
            throw new InvalidArgumentException('Invalid argument detected.');
        }

        return new DataFrame($data);
    }

    /**
     * Seleciona uma célula específica no dataset.
     *
     * @param int $line O índice da linha.
     * @param mixed $column O índice ou rótulo da coluna.
     * @return mixed Retorna o conteúdo da célula.
     */
    public function cell(int $line, $column)
    {
        if (key_exists($line, $this->data) === false) {
            throw new OutOfRangeException($line);
        }

        if (is_numeric($column)) {
            $column = $this->getColumnNameByIndex($column);
        }

        if (key_exists($column, $this->data[$line]) === false) {
            throw new OutOfBoundsException($column);
        }

        return $this->data[$line][$column];
    }

    /**
     * Sumariza o dataframe com informações relevantes e de estatística descritiva
     *
     * @return array Retorna um array com informações sumarizadas do dataframe.
     */
    public function summary(): array
    {
        
    }

    /**
     * Conta quantas linhas tem o dataframe.
     * 
     * @return int
     */
    public function size(): int
    {
        return sizeof($this->data);
    }
}
