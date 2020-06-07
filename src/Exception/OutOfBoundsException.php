<?php
namespace Bear\Exception;

use Exception;

/**
 * Disparado quando um valor não é uma chave válida.
 *
 * @author everton
 */
class OutOfBoundsException extends Exception
{

    /**
     *
     * @var mixed Chave inválida solicitado.
     */
    protected $invalidKey;

    /**
     * 
     * @param mixed $invalidKey Índice inválido solicitado.
     * @return Exception
     */
    public function __construct($invalidKey)
    {
        parent::__construct("Key is invalid $invalidKey.");

        $this->invalidKey = $invalidKey;
    }

    public function getInvalidKey(): int
    {
        return $this->invalidKey;
    }
}
