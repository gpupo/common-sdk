## Árvore de dependências (libraries)
```
sebastian/peek-and-poke 1.0.x-dev Proxy for accessing non-public attributes and methods of an object
`--php >=5.6.0
sebastian/peek-and-poke dev-master Proxy for accessing non-public attributes and methods of an object
`--php >=5.6.0
phpunit/phpunit 5.4.6 The PHP Unit Testing framework.
|--ext-dom *
|--ext-json *
|--ext-pcre *
|--ext-reflection *
|--ext-spl *
|--myclabs/deep-copy ~1.3
|  `--php >=5.4.0
|--php ^5.6 || ^7.0
|--phpspec/prophecy ^1.3.1
|  |--doctrine/instantiator ^1.0.2
|  |  `--php >=5.3,<8.0-DEV
|  |--php ^5.3|^7.0
|  |--phpdocumentor/reflection-docblock ^2.0|^3.0.2
|  |  |--php >=5.5
|  |  |--phpdocumentor/reflection-common ^1.0@dev
|  |  |  `--php >=5.5
|  |  |--phpdocumentor/type-resolver ^0.2.0
|  |  |  |--php >=5.5
|  |  |  `--phpdocumentor/reflection-common ^1.0
|  |  |     `--php >=5.5
|  |  `--webmozart/assert ^1.0
|  |     `--php >=5.3.3
|  |--sebastian/comparator ^1.1
|  |  |--php >=5.3.3
|  |  |--sebastian/diff ~1.2
|  |  |  `--php >=5.3.3
|  |  `--sebastian/exporter ~1.2
|  |     |--php >=5.3.3
|  |     `--sebastian/recursion-context ~1.0
|  |        `--php >=5.3.3
|  `--sebastian/recursion-context ^1.0
|     `--php >=5.3.3
|--phpunit/php-code-coverage ^4.0
|  |--php ^5.6 || ^7.0
|  |--phpunit/php-file-iterator ~1.3
|  |  `--php >=5.3.3
|  |--phpunit/php-text-template ~1.2
|  |  `--php >=5.3.3
|  |--phpunit/php-token-stream ^1.4.2
|  |  |--ext-tokenizer *
|  |  `--php >=5.3.3
|  |--sebastian/code-unit-reverse-lookup ~1.0
|  |  `--php >=5.6
|  |--sebastian/environment ^1.3.2
|  |  `--php >=5.3.3
|  `--sebastian/version ~1.0|~2.0
|     `--php >=5.6
|--phpunit/php-file-iterator ~1.4
|  `--php >=5.3.3
|--phpunit/php-text-template ~1.2
|  `--php >=5.3.3
|--phpunit/php-timer ^1.0.6
|  `--php >=5.3.3
|--phpunit/phpunit-mock-objects ^3.2
|  |--doctrine/instantiator ^1.0.2
|  |  `--php >=5.3,<8.0-DEV
|  |--php ^5.6 || ^7.0
|  |--phpunit/php-text-template ^1.2
|  |  `--php >=5.3.3
|  `--sebastian/exporter ^1.2
|     |--php >=5.3.3
|     `--sebastian/recursion-context ~1.0
|        `--php >=5.3.3
|--sebastian/comparator ~1.1
|  |--php >=5.3.3
|  |--sebastian/diff ~1.2
|  |  `--php >=5.3.3
|  `--sebastian/exporter ~1.2
|     |--php >=5.3.3
|     `--sebastian/recursion-context ~1.0
|        `--php >=5.3.3
|--sebastian/diff ~1.2
|  `--php >=5.3.3
|--sebastian/environment ^1.3 || ^2.0
|  `--php >=5.3.3
|--sebastian/exporter ~1.2
|  |--php >=5.3.3
|  `--sebastian/recursion-context ~1.0
|     `--php >=5.3.3
|--sebastian/global-state ~1.0
|  `--php >=5.3.3
|--sebastian/object-enumerator ~1.0
|  |--php >=5.6
|  `--sebastian/recursion-context ~1.0
|     `--php >=5.3.3
|--sebastian/resource-operations ~1.0
|  `--php >=5.6.0
|--sebastian/version ~1.0|~2.0
|  `--php >=5.6
`--symfony/yaml ~2.1|~3.0
   `--php >=5.5.9
gpupo/common 1.7.3 Common Objects
`--php ^5.6 || ^7.0
gpupo/cache 1.3.0 Caching library that implements PSR-6
|--gpupo/common *
|  `--php ^5.6 || ^7.0
|--php ^5.6 || ^7.0
`--psr/cache 1.0.0
   `--php >=5.3.0
twig/twig v1.24.1 Twig, the flexible, fast, and secure template language for PHP
`--php >=5.2.7
psr/log 1.0.0 Common interface for logging libraries
monolog/monolog 1.20.0 Sends your logs to files, sockets, inboxes, databases and various web services
|--php >=5.3.0
`--psr/log ~1.0
symfony/console v3.1.2 Symfony Console Component
|--php >=5.5.9
`--symfony/polyfill-mbstring ~1.0
   `--php >=5.3.3
codeclimate/php-test-reporter v0.3.2 PHP client for reporting test coverage to Code Climate
|--ext-curl *
|--php >=5.3
|--satooshi/php-coveralls 1.0.*
|  |--ext-json *
|  |--ext-simplexml *
|  |--guzzle/guzzle ^2.8|^3.0
|  |  |--ext-curl *
|  |  |--php >=5.3.3
|  |  `--symfony/event-dispatcher ~2.1
|  |     `--php >=5.3.9
|  |--php >=5.3.3
|  |--psr/log ^1.0
|  |--symfony/config ^2.1|^3.0
|  |  |--php >=5.5.9
|  |  `--symfony/filesystem ~2.8|~3.0
|  |     `--php >=5.5.9
|  |--symfony/console ^2.1|^3.0
|  |  |--php >=5.5.9
|  |  `--symfony/polyfill-mbstring ~1.0
|  |     `--php >=5.3.3
|  |--symfony/stopwatch ^2.0|^3.0
|  |  `--php >=5.5.9
|  `--symfony/yaml ^2.0|^3.0
|     `--php >=5.5.9
`--symfony/console >=2.0
   |--php >=5.5.9
   `--symfony/polyfill-mbstring ~1.0
      `--php >=5.3.3

```
---
