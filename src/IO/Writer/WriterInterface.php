<?php
namespace Bear\IO\Writer;

use Bear\DataFrame;

/**
 * Interface para writer de data frame.
 * 
 * @author Everton
 */
interface WriterInterface
{
    /**
     * Escreve um data frame em algum lugar (arquivo, anco de dados, etc).
     * 
     * @param DataFrame $df
     * @return void
     */
    public function write(DataFrame $df): void;
}
