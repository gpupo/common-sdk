## Árvore de dependências (libraries)
```
codeclimate/php-test-reporter v0.3.2 PHP client for reporting test coverage to Code Climate
|--ext-curl *
|--php >=5.3
Warning: This development build of composer is over 60 days old. It is recommended to update it by running "composer.phar self-update" to get the latest version.
|--satooshi/php-coveralls 1.0.*
|  |--ext-json *
|  |--ext-simplexml *
|  |--guzzle/guzzle ^2.8 || ^3.0
|  |  |--ext-curl *
|  |  |--php >=5.3.3
|  |  `--symfony/event-dispatcher ~2.1
|  |     `--php >=5.3.9
|  |--php ^5.3.3 || ^7.0
|  |--psr/log ^1.0
|  |  `--php >=5.3.0
|  |--symfony/config ^2.1 || ^3.0 || ^4.0
|  |  |--php ^7.1.3
|  |  `--symfony/filesystem ~3.4|~4.0
|  |     `--php ^7.1.3
|  |--symfony/console ^2.1 || ^3.0 || ^4.0
|  |  |--php ^7.1.3
|  |  `--symfony/polyfill-mbstring ~1.0
|  |     `--php >=5.3.3
|  |--symfony/stopwatch ^2.0 || ^3.0 || ^4.0
|  |  `--php ^7.1.3
|  `--symfony/yaml ^2.0 || ^3.0 || ^4.0
|     `--php ^7.1.3
`--symfony/console >=2.0
   |--php ^7.1.3
   `--symfony/polyfill-mbstring ~1.0
      `--php >=5.3.3
gpupo/cache 1.3.2 Caching library that implements PSR-6
|--fig/cache-util dev-master as 1.0.x-dev
|  |--php >=5.4.0
|  `--psr/cache ^1.0.0
|     `--php >=5.3.0
|--gpupo/common *
|  `--php ^5.6 || ^7.0
|--php ^5.6 || ^7.0
`--psr/cache ^1.0
   `--php >=5.3.0
gpupo/common v1.7.8 Common Objects
`--php ^5.6 || ^7.0
monolog/monolog 1.23.0 Sends your logs to files, sockets, inboxes, databases and various web services
|--php >=5.3.0
`--psr/log ~1.0
   `--php >=5.3.0
phpunit/phpunit 7.0.2 The PHP Unit Testing framework.
|--ext-dom *
|--ext-json *
|--ext-libxml *
|--ext-mbstring *
|--ext-xml *
|--myclabs/deep-copy ^1.6.1
|  `--php ^5.6 || ^7.0
|--phar-io/manifest ^1.0.1
|  |--ext-dom *
|  |--ext-phar *
|  |--phar-io/version ^1.0.1
|  |  `--php ^5.6 || ^7.0
|  `--php ^5.6 || ^7.0
|--phar-io/version ^1.0
|  `--php ^5.6 || ^7.0
|--php ^7.1
|--phpspec/prophecy ^1.7
|  |--doctrine/instantiator ^1.0.2
|  |  `--php ^7.1
|  |--php ^5.3|^7.0
|  |--phpdocumentor/reflection-docblock ^2.0|^3.0.2|^4.0
|  |  |--php ^7.0
|  |  |--phpdocumentor/reflection-common ^1.0.0
|  |  |  `--php >=5.5
|  |  |--phpdocumentor/type-resolver ^0.4.0
|  |  |  |--php ^5.5 || ^7.0
|  |  |  `--phpdocumentor/reflection-common ^1.0
|  |  |     `--php >=5.5
|  |  `--webmozart/assert ^1.0
|  |     `--php ^5.3.3 || ^7.0
|  |--sebastian/comparator ^1.1|^2.0
|  |  |--php ^7.0
|  |  |--sebastian/diff ^2.0 || ^3.0
|  |  |  `--php ^7.1
|  |  `--sebastian/exporter ^3.1
|  |     |--php ^7.0
|  |     `--sebastian/recursion-context ^3.0
|  |        `--php ^7.0
|  `--sebastian/recursion-context ^1.0|^2.0|^3.0
|     `--php ^7.0
|--phpunit/php-code-coverage ^6.0
|  |--ext-dom *
|  |--ext-xmlwriter *
|  |--php ^7.1
|  |--phpunit/php-file-iterator ^1.4.2
|  |  `--php >=5.3.3
|  |--phpunit/php-text-template ^1.2.1
|  |  `--php >=5.3.3
|  |--phpunit/php-token-stream ^3.0
|  |  |--ext-tokenizer *
|  |  `--php ^7.1
|  |--sebastian/code-unit-reverse-lookup ^1.0.1
|  |  `--php ^5.6 || ^7.0
|  |--sebastian/environment ^3.0
|  |  `--php ^7.0
|  |--sebastian/version ^2.0.1
|  |  `--php >=5.6
|  `--theseer/tokenizer ^1.1
|     |--ext-dom *
|     |--ext-tokenizer *
|     |--ext-xmlwriter *
|     `--php ^7.0
|--phpunit/php-file-iterator ^1.4.3
|  `--php >=5.3.3
|--phpunit/php-text-template ^1.2.1
|  `--php >=5.3.3
|--phpunit/php-timer ^2.0
|  `--php ^7.1
|--phpunit/phpunit-mock-objects ^6.0
|  |--doctrine/instantiator ^1.0.5
|  |  `--php ^7.1
|  |--php ^7.1
|  |--phpunit/php-text-template ^1.2.1
|  |  `--php >=5.3.3
|  `--sebastian/exporter ^3.1
|     |--php ^7.0
|     `--sebastian/recursion-context ^3.0
|        `--php ^7.0
|--sebastian/comparator ^2.1
|  |--php ^7.0
|  |--sebastian/diff ^2.0 || ^3.0
|  |  `--php ^7.1
|  `--sebastian/exporter ^3.1
|     |--php ^7.0
|     `--sebastian/recursion-context ^3.0
|        `--php ^7.0
|--sebastian/diff ^3.0
|  `--php ^7.1
|--sebastian/environment ^3.1
|  `--php ^7.0
|--sebastian/exporter ^3.1
|  |--php ^7.0
|  `--sebastian/recursion-context ^3.0
|     `--php ^7.0
|--sebastian/global-state ^2.0
|  `--php ^7.0
|--sebastian/object-enumerator ^3.0.3
|  |--php ^7.0
|  |--sebastian/object-reflector ^1.1.1
|  |  `--php ^7.0
|  `--sebastian/recursion-context ^3.0
|     `--php ^7.0
|--sebastian/resource-operations ^1.0
|  `--php >=5.6.0
`--sebastian/version ^2.0.1
   `--php >=5.6
psr/log 1.0.2 Common interface for logging libraries
`--php >=5.3.0
sebastian/peek-and-poke 1.0.x-dev Proxy for accessing non-public attributes and methods of an object
`--php >=5.6.0
sebastian/peek-and-poke dev-master Proxy for accessing non-public attributes and methods of an object
`--php >=5.6.0
symfony/console v4.0.6 Symfony Console Component
|--php ^7.1.3
`--symfony/polyfill-mbstring ~1.0
   `--php >=5.3.3
twig/twig v2.4.7 Twig, the flexible, fast, and secure template language for PHP
|--php ^7.0
`--symfony/polyfill-mbstring ~1.0
   `--php >=5.3.3

```
---
