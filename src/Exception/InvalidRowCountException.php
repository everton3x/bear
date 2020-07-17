<?php
namespace Bear\Exception;

use Exception;

/**
 * Disparado quando um erro de quantidade de linhas ocorre.
 *
 * @author everton
 */
class InvalidRowCountException extends Exception
{

    /**
     *
     * @var int Quantidade de linhas.
     */
    protected int $numRows = 0;

    /**
     * 
     * @param int $numRows
     */
    public function __construct(int $numRows)
    {
        parent::__construct("Num of rows is invalid $numRows.");

        $this->numRows = $numRows;
    }

    public function getNumRows(): int
    {
        return $this->numRows;
    }
}
