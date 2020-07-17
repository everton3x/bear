<?php

namespace Bear\IO\Reader;

use Bear\DataFrame;

/**
 * Interface para classes que Lêem conteúdo de algum recurso e devolvem um data frame.
 *
 * @author Everton
 */
interface ReaderInterface
{
    public function read(): DataFrame;
}
