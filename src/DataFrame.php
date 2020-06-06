<?php
namespace Bear;

use ArrayObject;

class DataFrame
{

	/**
	 * Os dados do dataframe.
	 */
	protected ArrayObject $data;

	/**
	 * Os nomes de campos
	 */
	protected ArrayObject $fieldNames;

	/**
	 * Construtor do dataframe.
	 *
	 * @param ArrayObject $data Objeto com os dados, composto por ArrayObject com seus itens representando as linhas e um ArrayObject para cada linha, representando as cada colunas.
	 */
	public function __construct(ArrayObject $data)
	{
		$this->data = $data;

		$this->fieldNames = $this->readFieldNames();
	}

	/**
	 * Detecta os nomes de campos.
	 *
	 * @return ArrayObject Retorna um ArrayObject com os nomes de campos.
	 */
	protected function readFieldNames(): ArrayObject
	{
		$iterator = $this->data->getIterator();
		return new ArrayObject(array_keys($iterator->current()));
	}

	/**
	 * Pega os nomes de campos do dataframe.
	 *
	 * @return array Retorna um array com os nomes dos campos.
	 */
	public function getFieldNames(): array
	{
		return $this->fieldNames->getArrayCopy();
	}

	/**
	 * Verifica se o dataframe obedece à estrutura correta.
	 *
	 * @return bool
	 */
	public function checkStructure(): bool
	{
		for($lineIterator = $this->data->getIterator();$lineIterator->valid();$lineIterator->next()) {
			
			if($this->checkFieldNames($lineIterator->current()) === false){
				print_r($lineIterator->current());
				throw new \Exception('asgaga');
			}
				
		}

		return true;
	}

	/**
	 * Testa se $dataToCheck é uma linha com todas as colunas necessárias.
	 * @param array Uma linha do dataframe.
	 * @return bool
	 */
	protected function checkFieldNames(array $dataToCheck): bool
	{
		//verifica se existe diferença entre os campos detectados e os campos passados
		if(sizeof(array_diff_assoc(array_keys($dataToCheck), $this->getFieldNames())) !== 0){
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

	/**
	 * Executa uma iteração com cada linha do dataframe.
	 *
	 * @return ArrayObject Retorna um ArrayObject com uma linha do dataframe.
	 */
	public function iterate(): ArrayObject
	{
	}

}
