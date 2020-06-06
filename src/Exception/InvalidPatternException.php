<?php
namespace Bear\Exception;

use Exception;

/**
 * Disparado quando um pattern de filtragem é inválido.
 *
 * @author everton
 */
class InvalidPatternException extends Exception
{

    /**
     *
     * @var mixed Pattern inválido solicitado.
     */
    protected $invalidPattern;

    /**
     * 
     * @param mixed $invalidPattern Pattern inválido.
     * @return Exception
     */
    public function __construct($invalidPattern)
    {
        parent::__construct("Pattern is invalid $invalidPattern.");

        $this->invalidPattern = $invalidPattern;
    }

    public function getInvalidPattern(): int
    {
        return $this->invalidPattern;
    }
}
