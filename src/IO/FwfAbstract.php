<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Bear\IO;

/**
 * Description of FwfAbstract
 *
 * @author Everton
 */
abstract class FwfAbstract extends TextFileAbstract
{
    protected array $model = [];
    
    public function setModel(array $model): FwfAbstract
    {
        $this->model = $model;
        
        return $this;
    }
    
    public function getModel(): array
    {
        return $this->model;
    }
}
