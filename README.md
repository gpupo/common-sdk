[![Build Status](https://secure.travis-ci.org/gpupo/common-sdk.png?branch=master)](http://travis-ci.org/gpupo/common-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gpupo/common-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gpupo/common-sdk/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/gpupo/common-sdk/v/stable.svg)](https://packagist.org/packages/gpupo/common-sdk) [![Total Downloads](https://poser.pugx.org/gpupo/common-sdk/downloads.svg)](https://packagist.org/packages/gpupo/common-sdk) [![Latest Unstable Version](https://poser.pugx.org/gpupo/common-sdk/v/unstable.svg)](https://packagist.org/packages/gpupo/common-sdk) [![License](https://poser.pugx.org/gpupo/common-sdk/license.svg)](https://packagist.org/packages/gpupo/common-sdk)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/97bf5441-1b04-4f1d-a946-c547c61a90f0/small.png)](https://insight.sensiolabs.com/projects/97bf5441-1b04-4f1d-a946-c547c61a90f0)

Componente de uso comum entre SDKs para integração a partir de aplicações PHP com as APIs de Marketplaces

Veja:

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
- [ ] Testes devem suportar Mockups
- [ ] ``Client\Oauth2`` deve oferecer funcionalidades para possibilitar a aquisição e renovação de tokens em processo antes de ``Request->exec()``;
