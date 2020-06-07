<?php
namespace Bear\Exception;

use Exception;

/**
 * Disparado quando um nome de coluna inválido é requerido.
 *
 * @author everton
 */
class InvalidColumnNameException extends Exception
{

    /**
     *
     * @var mixed Nome inválido solicitado.
     */
    protected $invalidColumnName;

    /**
     * 
     * @param mixed $invalidColumnName Índice inválido solicitado.
     * @return Exception
     */
    public function __construct($invalidColumnName)
    {
        parent::__construct("Column name is invalid $invalidColumnName.");

        $this->invalidColumnName = $invalidColumnName;
    }

    public function getInvalidColumnName(): int
    {
        return $this->invalidColumnName;
    }
}
