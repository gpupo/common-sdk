[![Build Status](https://secure.travis-ci.org/gpupo/common-sdk.png?branch=master)](http://travis-ci.org/gpupo/common-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gpupo/common-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gpupo/common-sdk/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/97bf5441-1b04-4f1d-a946-c547c61a90f0/mini.png)](https://insight.sensiolabs.com/projects/97bf5441-1b04-4f1d-a946-c547c61a90f0)
[![Code Climate](https://codeclimate.com/repos/554b625fe30ba01e88003d2f/badges/3e5baa4786d35478823a/gpa.svg)](https://codeclimate.com/repos/554b625fe30ba01e88003d2f/feed)

Componente de uso comum entre SDKs para integração a partir de aplicações PHP com as APIs de Marketplaces

Veja:

* [Composer Package](https://packagist.org/packages/gpupo/) on packagist.org
* [gpupo/submarino-sdk](https://github.com/gpupo/submarino-sdk)  - B2W Marketplace ![Build Status](https://secure.travis-ci.org/gpupo/submarino-sdk.png?branch=master)
* [gpupo/cnova-sdk](https://github.com/gpupo/cnova-sdk)  - Cnova Marketplace ![Build Status](https://secure.travis-ci.org/gpupo/cnova-sdk.png?branch=master)
* [marketplace-bundle Composer Package](https://packagist.org/packages/gpupo/marketplace-bundle) - Integração com Symfony2

## Install

    composer require gpupo/common-sdk

## Contributors

- [@gpupo](https://github.com/gpupo)
- [All Contributors](https://github.com/gpupo/common/contributors)

## License

MIT, see LICENSE.

## Coisas para fazer:

- [ ] Objeto ``Transport`` deve adotar padrão Driver, suportando outras bibliotecas além de ``cUrl``
- [x] Testes devem suportar Mockups
- [ ] ``Client\Oauth2`` deve oferecer funcionalidades para possibilitar a aquisição e renovação de tokens em processo antes de ``Request->exec()``;
- [ ] Melhor a documentação dos objetos - utilizar [lista apontada pelo Scrutinizer](https://scrutinizer-ci.com/g/gpupo/common-sdk/issues/master)


---

# Propriedades dos objetos (Testdox)

<!--
Comando para geração da lista:

phpunit --testdox | grep -vi php |  sed "s/.*\[/-&/" | sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/Gpupo\\Tests\\CommonSdk\\/### /g'

-->
A lista abaixo é gerada a partir da saída da execução dos testes:

### Client\Client


- [x] Url independente de configuracao
- [x] Url baseado em configuracao

### Client\Oauth2\Provider\GenericProvider


- [x] Acesso a url de autorizacao

### Entity\EntityTools


- [x] Valida tipos de informacao
- [x] Normaliza tipos de informacao
- [x] Aborta com uso de dados invalidos
- [x] Sucesso com uso de dados validos

### Entity\Manager


- [x] Factory collection
- [x] Nao encontra diferenca entre entidades iguais
- [x] Encontra diferenca entre entidades diferentes
- [x] Encontra diferenca entre entidades diferentes a partir de chaves selecionadas
- [x] Falha ao tentar encontrar diferenca usando propriedade inexistente
