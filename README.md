# Bear

**Biblioteca PHP para manipulação e análise de dados inspirada no Pandas (Python) e no R.**

**Bear** é uma biblioteca para manipulação e análise de dados tabulares, inspirada no Pandas/Python e na linguagem R.

Ela utiliza internamente ```array``` para armazenar os dados e, por isso, tenha em mente que, pelo menos por enquanto, não há uma preocupação com uso de memória ou com desempenho.

## Instalação

O método de instalação recomendado é o do Composer:

```sh
composer require everton3x/bear
```

Caso deseje fazer uma instalação manual, todos os arquivos necessários estão no diretório ```src```.

## Utilização

**Bear** está organizada em torno de uma classe chamada ```DataFrame```, que representa um objeto de dados tabular e seus métodos de manipulação.

Com exceções, todos os métodos não alteram o data frame da instãncia, mas devolvem uma nova instância de ```DataFrame``` com os dados manipualdos.

Um data frame pode ser criado a partir de um ```array``` diretamente no cosntrutor de ```DataFrame```, ou através de um reader ara algum dos formatos suportados.

Para detalhes da API, leia a documentação da biblioteca.

Para exemplos, por enquanto você pode consultar o código-fonte dos testes no diretório ```tests```.

## To-do

- [ ] sort;
- [ ] reader/writer para PDO;
- [ ] exemplos de utilização;
- [ ] integração com o Github Actions;

## Changelog

Para consultar as modificações emc ada versão, por favor veja a descrição de cada release.

## Como contribuir

Contribuições são bem-vindas sempre.

Caso deseje contribuir:

- faça um fork;
- crie um branch para sua contribuição;
- faça as alterações;
- escreva testes;
- escreva/atualize a documentação;
- utilize os comandos personalizados do Composer:
    * ```composer mess```
    * ```composer stan```
    * ```composer format```
    * ```composer check```
    * ```composer test```
- assim que todos os comandos acima passarem, faça um pull request do seu branch;

Tenha em mente que a maior cobertura de código (code coverage) possível é incentivada.

Se existir uma issue relacionada a sua contribuição, por favor faça referência a isso.
