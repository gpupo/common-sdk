
<!-- main -->

# common-sdk

Componente de uso comum entre SDKs

<!-- require -->

## Requisitos para uso

* PHP >= *7.1*
* [curl extension](http://php.net/manual/en/intro.curl.php)
* [Composer Dependency Manager](http://getcomposer.org)

Este componente **não é uma aplicação Stand Alone** e seu objetivo é ser utilizado como biblioteca.
Sua implantação deve ser feita por desenvolvedores experientes.

**Isto não é um Plugin!**

As opções que funcionam no modo de comando apenas servem para depuração em modo de
desenvolvimento.

A documentação mais importante está nos testes unitários. Se você não consegue ler os testes unitários, eu recomendo que não utilize esta biblioteca.

<!-- license -->

## Direitos autorais e de licença

Este componente está sob a [licença MIT](https://github.com/gpupo/common-sdk/blob/master/LICENSE)

Para a informação dos direitos autorais e de licença você deve ler o arquivo
de [licença](https://github.com/gpupo/common-sdk/blob/master/LICENSE) que é distribuído com este código-fonte.

### Resumo da licença

Exigido:

- Aviso de licença e direitos autorais

Permitido:

- Uso comercial
- Modificação
- Distribuição
- Sublicenciamento

Proibido:

- Responsabilidade Assegurada

<!-- QA -->

## Indicadores de qualidade

[![Build Status](https://secure.travis-ci.org/gpupo/common-sdk.png?branch=master)](http://travis-ci.org/gpupo/common-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gpupo/common-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gpupo/common-sdk/?branch=master)
[![Codacy Badge](https://www.codacy.com/project/badge/323afbd6d88f4c4dbc4dec27810c70b9)](https://www.codacy.com/app/g/common-sdk)
[![Code Climate](https://codeclimate.com/github/gpupo/common-sdk/badges/gpa.svg)](https://codeclimate.com/github/gpupo/common-sdk)
[![Test Coverage](https://codeclimate.com/github/gpupo/common-sdk/badges/coverage.svg)](https://codeclimate.com/github/gpupo/common-sdk/coverage)


[![SensioLabsInsight](https://insight.sensiolabs.com/projects/97bf5441-1b04-4f1d-a946-c547c61a90f0/big.png)](https://insight.sensiolabs.com/projects/97bf5441-1b04-4f1d-a946-c547c61a90f0)

<!-- thanks -->

---

## Agradecimentos

* A todos os que [contribuiram com patchs](https://github.com/gpupo/common-sdk/contributors);
* Aos que [fizeram sugestões importantes](https://github.com/gpupo/common-sdk/issues);
* Aos desenvolvedores que criaram as [bibliotecas utilizadas neste componente](https://github.com/gpupo/common-sdk/blob/master/Resources/doc/libraries-list.md).

 _- [Gilmar Pupo](https://opensource.gpupo.com/)_

<!-- install -->

---

## Instalação

Adicione o pacote ``common-sdk`` ao seu projeto utilizando [composer](http://getcomposer.org):

    composer require gpupo/common-sdk "^3.0"

<!-- console -->

## Ferramentas de Console

### Test Generator

    vendor/bin/common-sdk tests:implement --class {FQN}

exemplo:

    vendor/bin/common-sdk tests:implement --class "App\Entity\Acl\OAuth\Client"


<!-- links -->

---

## Links

* [Composer Package](https://packagist.org/packages/gpupo/) on packagist.org
* [SDKs para o Ecommerce do Brasil](https://opensource.gpupo.com/common-sdk/)
