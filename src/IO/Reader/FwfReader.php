<?php
namespace Bear\IO\Reader;

use Exception;
use Bear\DataFrame;
use Bear\IO\FwfAbstract;

/**
 * Reader para arquivos com campos de largura fixa.
 *
 * @author Everton
 */
class FwfReader extends FwfAbstract implements ReaderInterface
{
    public function __construct(string $filename)
    {
        $handle = @fopen($filename, 'r');
        if ($handle === false) {
            throw new Exception(sprintf('Arquivo [%s] inacessível.', $filename));
        }

        $this->handle = $handle;
    }

    public function read(): DataFrame
    {
        
    }
}
