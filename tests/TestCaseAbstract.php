<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\Tests\CommonSdk;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Traits\LoggerTrait;
use Gpupo\Tests\CommonSdk\Documentor\Docblock;
use Gpupo\Tests\CommonSdk\Traits\AssertTrait;
use Gpupo\Tests\CommonSdk\Traits\ProxyTrait;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class TestCaseAbstract extends \PHPUnit_Framework_TestCase
{
    use LoggerTrait;
    use ProxyTrait;
    use AssertTrait;

    protected function getLoggerFilePath()
    {
        return $this->getVarPath().'logs/tests.log';
    }

    public function getLogger()
    {
        if (!$this->logger) {
            $channel = str_replace('\\', '.', get_called_class());
            $logger = new Logger($channel);
            $logger->pushHandler(new StreamHandler($this->getLoggerFilePath(), Logger::DEBUG));

            $verbose = $this->getConstant('VERBOSE');

            if (!empty($verbose)) {
                $logger->pushHandler(new ErrorLogHandler(0, Logger::INFO));
            }

            $this->setLogger($logger);
        }

        return $this->logger;
    }

    protected function logMark($message, array $callers, $mode = 'skipped')
    {
        $context = [
            'test'    => $callers[1]['function'],
            'message' => $message,
        ];

        return $this->log('info', 'Test '.$mode, $context);
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

    protected function hasToken()
    {
        return $this->hasConstant('API_TOKEN');
    }

    protected function getConstant($name, $default = false)
    {
        if (defined($name)) {
            return constant($name);
        }

        return $default;
    }

    protected function hasConstant($name)
    {
        $value = $this->getConstant($name);

        return empty($value) ? false : true;
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

    protected function getResourceContent($file)
    {
        return file_get_contents($this->getResourceFilePath($file));
    }

    protected function getResourceJson($file)
    {
        return json_decode($this->getResourceContent($file), true);
    }

    protected function getResourceFilePath($file, $create = false)
    {
        $path = static::getResourcesPath().$file;

        if (file_exists($path)) {
            return $path;
        } elseif ($create) {
            touch($path);

            return $this->getResourceFilePath($file);
        }

        throw new \InvalidArgumentException('File '.$path.' Not Exist');
    }

    protected function factoryResponseFromFixture($file, $httpStatusCode = 200)
    {
        $response = new Response([
            'httpStatusCode' => $httpStatusCode,
            'responseRaw'    => $this->getResourceContent($file),
        ]);

        return $response;
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

        if (count($argv) <= 1 || $argv[1] !== '--stderr') {
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
}
