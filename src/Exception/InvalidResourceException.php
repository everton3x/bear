<?php
namespace Bear\Exception;

use Exception;

/**
 * Disparado quando um recurso inválido, inexistente ou inacessível é requerido.
 * 
 * Recursos são arquivos, bancos de dados, stremas, etc.
 *
 * @author everton
 */
class InvalidResourceException extends Exception
{

    /**
     *
     * @var string O recurso requerido.
     */
    protected string $resource;

    /**
     * 
     * @param string $resource O recurso (nome) requerido.
     */
    public function __construct(string $resource)
    {
        parent::__construct("Resource [$resource] is invalid.");

        $this->resource = $resource;
    }

    public function getInvalidResource(): string
    {
        return $this->resource;
    }
}
