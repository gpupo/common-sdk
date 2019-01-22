<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\CommonSdk\Tests;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Tests\Documentor\Docblock;
use Gpupo\CommonSdk\Tests\Traits\AssertTrait;
use Gpupo\CommonSdk\Tests\Traits\ProxyTrait;
use Gpupo\CommonSdk\Traits\LoggerTrait;
use Gpupo\CommonSdk\Traits\ResourcesTrait;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase as TestCaseCore;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class TestCaseAbstract extends TestCaseCore
{
    use LoggerTrait;
    use ProxyTrait;
    use AssertTrait;
    use ResourcesTrait;

    private $output;

    public function getLogger()
    {
        if (!$this->logger) {
            $channel = str_replace('\\', '.', \get_called_class());
            $logger = new Logger($channel);
            $logger->pushHandler(new StreamHandler($this->getLoggerFilePath(), Logger::DEBUG));
            $this->setLogger($logger);
        }

        return $this->logger;
    }

    public function markSkipped($message = '')
    {
        $this->logMark($message, debug_backtrace());

        return $this->markTestSkipped($message);
    }

    public function markIncomplete($message = '')
    {
        $this->logMark($message, debug_backtrace(), 'incomplete');

        return $this->markTestIncomplete($message);
    }

    /**
     * Caminho para o diretório de recursos.
     */
    public static function getResourcesPath()
    {
        return getcwd().'/Resources/';
    }

    public static function getVarPath()
    {
        return getcwd().'/var/';
    }

    /**
     * Exibe a documentação automática para Entidades.
     *
     * Contém os métodos mágicos e é exibida quando o segundo parâmetro enviados ao
     * phpunit é --stderr desde que o método setUpBeforeClass() do teste seja
     * implementado conforme exemplo a seguir
     *
     * <code>
     *     //...
     *     public static function setUpBeforeClass()
     *     {
     *          self::displayClassDocumentation(new Product());
     *     }
     *     //...
     * </code>
     *
     * @param EntityInterface $entity [description]
     */
    public static function displayClassDocumentation($entity)
    {
        global $argv;

        if (\count($argv) <= 1 || '--stderr' !== $argv[1]) {
            return false;
        }

        $docblock = Docblock::getInstance();
        $docblock->setResourcesPath(static::getVarPath());

        if ($entity instanceof EntityAbstract) {
            $json = json_encode($entity->toArray(), JSON_PRETTY_PRINT);
            echo $docblock->generate($entity->toDocBLock(), $json);
        } else {
            echo $docblock->generate();
        }
    }

    protected function getOutput(): ConsoleOutput
    {
        if (empty($this->output)) {
            $this->output = new ConsoleOutput();
        }

        return $this->output;
    }

    protected function output(string $string)
    {
        $verbose = $this->getConstant('VERBOSE');

        if (!empty($verbose)) {
            return $this->getOutput()->writeln($string);
        }
    }

    protected function getLoggerFilePath()
    {
        return $this->getVarPath().'logs/tests.log';
    }

    protected function logMark($message, array $callers, $mode = 'skipped')
    {
        $context = [
            'test' => $callers[1]['function'],
            'message' => $message,
        ];

        return $this->log('info', 'Test '.$mode, $context);
    }

    protected function hasToken()
    {
        return $this->hasConstant('API_TOKEN');
    }

    protected function getConstant($name, $default = false)
    {
        if (\defined($name)) {
            return \constant($name);
        }

        return $default;
    }

    protected function hasConstant($name)
    {
        $value = $this->getConstant($name);

        return !empty($value);
    }

    protected function factoryResponseFromArray(array $array, $httpStatusCode = 200)
    {
        return $this->factoryResponseFromRawJson(json_encode($array), $httpStatusCode);
    }

    protected function factoryResponseFromFixture($file, $httpStatusCode = 200)
    {
        return $this->factoryResponseFromRawJson($this->getResourceContent($file), $httpStatusCode);
    }

    protected function factoryResponseFromRawJson($string, $httpStatusCode = 200)
    {
        $response = new Response([
            'httpStatusCode' => $httpStatusCode,
            'responseRaw' => $string,
        ]);

        return $response;
    }
}
