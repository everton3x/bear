<?php

namespace Bear\IO;

use Exception;

/**
 * Description of FwfAbstract
 *
 * @author Everton
 */
abstract class FwfAbstract extends TextFileAbstract
{
    /**
     *
     * @var array<array> Modelo de colunas no formato [nome]=>especificação onde
     * a especificação contém os seguintes valores:
     *
     * - start: inteiro representando o caracter de início da coluna, iniciando
     * em zero;
     * - len: inteiro representado o número de caracteres da coluna;
     * - transform: string|callable opcional indicando o nome de uma função para ser
     * chamada para transformar os dados. Recebe os dados brutos da célula e
     * retorna os dados transformados;
     * - type: opcional. string com um dos tipos suportados do php. Se fornecida
     * o valor será convertido para aquele tipo.
     *
     */
    protected array $model = [];
    
    /**
     * Configura o modelo de colunas a ser aplicado.
     *
     * @param array<array> $model
     * @return \Bear\IO\FwfAbstract
     * @throws Exception
     * @see FwfAbstract::$model
     */
    public function setModel(array $model): FwfAbstract
    {
        $this->model = $model;
        try {
            $this->checkModel();
        } catch (Exception $ex) {
            throw $ex;
        }

        return $this;
    }
    
    /**
     * Retorna o modelo de colunas.
     *
     * @return array<array>
     */
    public function getModel(): array
    {
        return $this->model;
    }
    
    /**
     * Testa se o modelo de colunas tem as esecificações obrigatórias.
     *
     * @return void
     * @throws Exception
     */
    protected function checkModel(): void
    {
        foreach ($this->model as $key => $field) {
            if (!key_exists('start', $field)) {
                throw new Exception("Especificação [start] não existe para o campo [$key]");
            }
            if (!key_exists('len', $field)) {
                throw new Exception("Especificação [len] não existe para o campo [$key]");
            }
            
            if (!is_int($field['start'])) {
                throw new Exception(sprintf(
                    'Valor de [start] é do tipo [%s], mas deveria ser [int]',
                    gettype($field['start'])
                ));
            }
            if (!is_int($field['len'])) {
                throw new Exception(sprintf(
                    'Valor de [len] é do tipo [%s], mas deveria ser [int]',
                    gettype($field['len'])
                ));
            }
        }
    }
}
