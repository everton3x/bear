<?php
namespace Bear\Exception;

use Exception;

/**
 * Disparado quando um erro acontece em algum parser.
 * 
 * @author everton
 */
class ParseException extends Exception
{

    /**
     *
     * @var string O parser
     */
    protected string $parser;

    /**
     * 
     * @param string $parser O nome do parser.
     */
    public function __construct(string $parser)
    {
        parent::__construct("Fail on parser [$parser].");

        $this->parser = $parser;
    }

    public function getParser(): string
    {
        return $this->parser;
    }
}
