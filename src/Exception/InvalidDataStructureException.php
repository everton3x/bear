<?php
namespace Bear\Exception;

use Exception;
/**
 * Diparado quando ocorre os dados tem uma estrutura inválida.
 *
 * @author everton
 */
class InvalidDataStructureException extends Exception
{
    /**
     *
     * @var int Índice da linha com estrutura inválida.
     */
    protected int $rowIndex;
    
    /**
     *
     * @var array lista de colunas na linha com etrutura inválida.
     */
    protected array $columns;
    
    /**
     * 
     * @param int $rowIndex Índice da linha inválida.
     * @param array $columns Lista de colunas da linha inválida.
     * @return Exception
     */
    public function __construct(int $rowIndex, array $columns)
    {
        parent::__construct("Invalid data structure detected on row $rowIndex.");
        
        $this->rowIndex = $rowIndex;
        $this->columns = $columns;
    }
    
    public function getRowIndex(): int
    {
        return $this->rowIndex;
    }
    
    public function getColumns(): array
    {
        return $this->columns;
    }
}
