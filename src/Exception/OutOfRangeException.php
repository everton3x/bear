<?php
namespace Bear\Exception;

use Exception;

/**
 * Disparado quando um índice inválido é requerido.
 *
 * @author everton
 */
class OutOfRangeException extends Exception
{

    /**
     *
     * @var mixed Índice inválido solicitado.
     */
    protected $invalidIndex;

    /**
     * 
     * @param mixed $invalidIndex Índice inválido solicitado.
     * @return Exception
     */
    public function __construct($invalidIndex)
    {
        parent::__construct("Index is invalid $invalidIndex.");

        $this->invalidIndex = $invalidIndex;
    }

    public function getInvalidIndex(): int
    {
        return $this->invalidIndex;
    }
}
