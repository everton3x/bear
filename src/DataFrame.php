<?php
namespace Bear;

class DataFrame
{
	public function __construct()
	{
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
}
