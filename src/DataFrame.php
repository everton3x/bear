<?php
namespace Bear;

use Bear\Exception\InvalidColumnNameException;
use Bear\Exception\InvalidDataStructureException;
use Bear\Exception\InvalidRowCountException;
use Bear\Exception\OutOfBoundsException;
use InvalidArgumentException;

/**
 * Abstração de dados tabulares.
 */
class DataFrame
{

    /**
     *
     * @var array A matriz de dados, organizada como array bidimensional no formato [linha][coluna] = valor
     */
    protected array $data = [];

    /**
     *
     * @var array Nomes das colunas.
     */
    protected array $colNames = [];

    /**
     * Nova instância de um dataframe.
     * 
     * @param array $data Os dados no formato [linha][coluna] = valor, como 
     * neste exemplo:
     * 
     *      $data = [
     *  
     *          [//início da linha 0
     *              'id' => 1,//campo id com valor 1
     *              'name' => 'John',//campo name com valor John
     *              'age' => 39//campo age com valor 39
     *          ],//fim da linha 0
     *          [//início da linha 1
     *              'id' => 2,//campo id com valor 2
     *              'name' => 'Mary',//campo name com valor Mary
     *              'age' => 37//campo age com valor 37
     *          ]//fim da linha 1
     *      ];
     * 
     * Observe que as chaves das colunas da primeira linha são automaticamente 
     * usadas como nomes de colunas. Os nomes de colunas podem ser modificados 
     * por DataFrame::setColumnNames().
     * 
     * Também é possível fornecer um array vazio. Neste caso, o data frame não
     * terá nomes de colunas nem dados e DataFrame::get() e 
     * DataFrame::getColumnNames() retornam um array vazio.
     * 
     * @throws InvalidDataStructureException
     * @see DataFrame::setColumnNames()
     * @see DataFrame::getColumnNames()
     * @see DataFrame::$colNames
     * @see DataFrame::$data
     * @see DataFrame::checkDataStructure()
     * @see DataFrame::disassembleDataFrame()
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        if ($data !== []) {//possibilita a criação de df vazios
            $this->detectColNames();
            try {
                $this->checkDataStructure();
                $this->disassembleDataFrame();
            } catch (InvalidDataStructureException $ex) {
                throw $ex;
            }
        }
    }

    /**
     * Desmonta o array de dados fornecido no construtor.
     * 
     * Necessário para economizar memória (eu ainda não testei isso), já que, caso nomes de colunas sejam 
     * fornecidos no construtor, eles se repetiriam nas chaves de colunas em 
     * todas as linhas.
     * 
     * Desta forma, desmontando o array, os nomes de colunas são trocados por 
     * inteiros representando a ordem das colunas.
     * 
     * 
     * @return void
     * @see DataFrame::assembleDataFrame()
     */
    protected function disassembleDataFrame(): void
    {
        $rowId = 0;
        foreach ($this->data as $row) {
            $colId = 0;
            foreach ($row as $cell) {
                $newRow[$colId] = $cell;
                $colId++;
            }
            $this->data[$rowId] = $newRow;
            $rowId++;
        }
    }

    /**
     * Monta um array trocando as chaves numéricas das colunas pelos nomes de 
     * colunas em cada linha.
     * 
     * @return array
     * @see DataFrame::disassembleDataFrame()
     */
    protected function assembleDataFrame(): array
    {
        $data = [];
        foreach ($this->data as $rowId => $row) {
            $colId = 0;
            foreach ($this->colNames as $colName) {
                $newRow[$colName] = $row[$colId];
                $colId++;
            }
            $data[$rowId] = $newRow;
        }

        return $data;
    }

    /**
     * Descobre os nomes de colunas a partir da primeira linha do data frame.
     * 
     * @return void
     * @see DataFrame::$colNames
     */
    protected function detectColNames(): void
    {
        foreach ($this->data as $row) {
            $this->colNames = array_keys($row);
            break;
        }
    }

    /**
     * Verifica a estrutura do array fornecido em DataFrame::__construct().
     * 
     * A verificação conta o número de colunas em cada linha e dispara uma 
     * InvalidDataStructureException() se houver diferença.
     * 
     * @return void
     * @throws InvalidDataStructureException
     */
    protected function checkDataStructure(): void
    {
        $numCols = count($this->colNames);
        
        foreach ($this->data as $rowId => $row) {
            if (count($row) !== $numCols) {
                throw new InvalidDataStructureException($rowId, array_keys($row));
            }
        }
    }

    /**
     * Conta o número de linhas do data frame.
     * 
     * @return int
     */
    public function countRows(): int
    {
        return count($this->data);
    }

    /**
     * Conta o número de colunas do data frame.
     * 
     * @return int
     * @see DataFrame$colNames
     */
    public function countColumns(): int
    {
        return count($this->colNames);
    }

    /**
     * Fornece uma arrya com os nomes de colunas.
     * 
     * @return array
     * @see DataFrame::$colNames
     */
    public function getColumnNames(): array
    {
        return $this->colNames;
    }

    /**
     * Fornece um array com os dados do data frame no formato 
     * [linha][coluna] = valor
     * 
     * @return array
     * @see DataFrame::assembleDataFrame()
     */
    public function get(): array
    {
        if ($this->data === []) {
            return [];
        }

        return $this->assembleDataFrame();
    }

    /**
     * Configura os nomes das colunas.
     * 
     * Precisa respeitar a ordem e a quantidade das colunas no data frame.
     * 
     * @param array $colNames
     * @return DataFrame
     * @throws InvalidArgumentException
     * @see DataFrame::$colNames
     * @see DataFrame::getColumnNames()
     */
    public function setColumnNames(array $colNames): DataFrame
    {
        if (count($colNames) !== $this->countColumns()) {
            throw new InvalidArgumentException(sprintf('Invalid number of columns. %d columns were expected, but $d were provided.', $this->countColumns(), count($colNames)));
        }

        $this->colNames = $colNames;

        return $this;
    }

    /**
     * Fornece um data frame com as colunas selecionadas pelo nome.
     * 
     * @param array $columns
     * @return DataFrame
     * @throws OutOfBoundsException
     */
    public function getColumnsByName(array $columns): DataFrame
    {
        foreach ($columns as $colName) {
            $colIndex = array_search($colName, $this->colNames);
            if ($colIndex === false) {
                throw new OutOfBoundsException($colName);
            }

            $colList[] = $colIndex;
        }

        $df = $this->getColumnsByIndex($colList);
        return $df;
    }

    /**
     * Fornece um data frame com as colunas filtradas pelo seu index (sua ordem 
     * dentro do data frame).
     * 
     * @param array $columns
     * @return DataFrame
     * @throws OutOfBoundsException
     */
    public function getColumnsByIndex(array $columns): DataFrame
    {
        $data = [];

        foreach ($this->data as $rowId => $row) {
            foreach ($columns as $colId => $colIndex) {
                if (!key_exists($colIndex, $this->colNames)) {
                    throw new OutOfBoundsException($colIndex);
                }
                $colName = $this->colNames[$colIndex];

                $data[$rowId][$colName] = $row[$colIndex];
            }
        }

        $df = new DataFrame($data);

        return $df;
    }

    /**
     * Fornece um data frame com as colunas filtradas por uma coluna inicial e 
     * outra final (inclusive), segundo o seu índice.
     * 
     * @param int|null $start
     * @param int|null $end
     * @return DataFrame
     * @throws OutOfBoundsException
     * @throws InvalidArgumentException
     */
    public function getColumnsRangeByIndex(?int $start = null, ?int $end = null): DataFrame
    {
        $data = [];

        if (is_null($start)) {
            $start = array_key_first($this->colNames);
        }

        if (is_null($end)) {
            $end = array_key_last($this->colNames);
        }

        if (!key_exists($start, $this->colNames)) {
            throw new OutOfBoundsException($start);
        }

        if (!key_exists($end, $this->colNames)) {
            throw new OutOfBoundsException($end);
        }

        for ($i = $start; $i <= $end; $i++) {
            $colNames[] = $this->colNames[$i];
        }

        $length = $end - $start + 1;

        if ($start > $end) {
            throw new InvalidArgumentException("The start column $start cannot be later than the end column $end.");
        }

        foreach ($this->data as $row) {
            $data[] = array_slice($row, $start, $length);
        }

        $df = new DataFrame($data);
        $df->setColumnNames($colNames);

        return $df;
    }

    /**
     * Fornece um data frame com as colunas selecionadas dentro do intervalo dos 
     * nomes das colunas inicial e final (inclusive).
     * 
     * @param string|null $start
     * @param string|null $end
     * @return DataFrame
     * @throws OutOfBoundsException
     */
    public function getColumnsRangeByName(?string $start = null, ?string $end = null): DataFrame
    {
        if (!is_null($start)) {
            $start = array_search($start, $this->colNames);
            if ($start === false) {
                throw new OutOfBoundsException($start);
            }
        }

        if (!is_null($end)) {
            $end = array_search($end, $this->colNames);
            if ($end === false) {
                throw new OutOfBoundsException($end);
            }
        }

        $df = $this->getColumnsRangeByIndex($start, $end);

        return $df;
    }
    
    /**
     * Fornece um data frame com as linhas filtradas de acordo com a seleção 
     * fornecida.
     * 
     * @param array $rows
     * @return DataFrame
     * @throws OutOfBoundsException
     */
    public function getRows(array $rows): DataFrame
    {
        $data = [];
        foreach ($rows as $index){
            if(!key_exists($index, $this->data)){
                throw new OutOfBoundsException($index);
            }
            $data[] = $this->data[$index];
        }
        
        $df = new DataFrame($data);
        $df->setColumnNames($this->colNames);
        
        return $df;
    }
    
    /**
     * Retorna um data frame com uma faixa de linhas entre $start e $end.
     * 
     * @param int|null $start
     * @param int|null $end
     * @return DataFrame
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function getRowRange(?int $start = null, ?int $end = null): DataFrame
    {
        if(is_null($start)){
            $start = 0;
        }
        
        if(is_null($end)){
            $end = $this->countRows() - 1;
        }
        
        if($start > $end){
            throw new InvalidArgumentException("The start row $start cannot be later than the end row $end.");
        }
        
        if(!key_exists($start, $this->data)){
            throw new OutOfBoundsException($start);
        }
        
        if(!key_exists($end, $this->data)){
            throw new OutOfBoundsException($end);
        }
        
        return $this->getRows(range($start, $end, 1));
    }
    
    /**
     * Mescla um data frame ao data frame atual pelas linhas.
     * 
     * As colunas dos dois data frames precisam ser da mesma quantidade e com 
     * os mesmos nomes.
     * 
     * @param DataFrame $df
     * @return DataFrame
     * @throws InvalidColumnNameException
     */
    public function mergeByRows(DataFrame $df): DataFrame
    {
        //verifica se as colunas são as mesmas em tamanho e rótulo
        if($this->colNames !== $df->getColumnNames()){
            throw new InvalidColumnNameException(join(', ', $df->getColumnNames()));
        }
        
        $data = array_merge($this->get(), $df->get());
        $newdf = new DataFrame($data);
        $newdf->setColumnNames($this->colNames);
        return $newdf;
    }
    
    /**
     * Mescla um data frame com o data frame atual acrescentando colunas à 
     * esquerda.
     * 
     * É necessário que ambos os data frames tenham o mesmo número de linhas.
     * 
     * Não pode haver colunas com os mesmos nomes nos dois data frames.
     * 
     * @param \Bear\DataFrame $df
     * @return \Bear\DataFrame
     * @throws InvalidRowCountException
     * @throws InvalidColumnNameException
     */
    public function mergeByColumns(DataFrame $df): DataFrame
    {
        //verifica se os data frames tem o mesmo número de linhas
        if($this->countRows() !== $df->countRows()){
            throw new InvalidRowCountException($df->countRows());
        }
        
        //verifica se os nomes de colunas são iguais
        if(array_intersect($this->colNames, $df->getColumnNames())){
            throw new InvalidColumnNameException(join(', ', $df->getColumnNames()));
        }
        
        $data = $this->get();
        $new = $df->get();
        foreach ($data as $index => $row){
            $data[$index] = array_merge($row, $new[$index]);
        }
        
        $newdf = new DataFrame($data);
        $newdf->setColumnNames(array_merge($this->colNames, $df->getColumnNames()));
        return $newdf;
    }
}
