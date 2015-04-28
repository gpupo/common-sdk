<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\CommonSdk;

use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Traits\LoggerTrait;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class TestCaseAbstract extends \PHPUnit_Framework_TestCase
{
    use LoggerTrait;

    public function assertHttpStatusCodeSuccess($code, $context = null)
    {
        $this->assertContains($code, [200, 204], $context);
    }

    public function getLogger()
    {
        if (!$this->logger) {
            $channel = str_replace('\\', '.', get_called_class());
            $logger = new Logger($channel);
            $filePath = $this->getResourceFilePath('logs/tests.log', true);
            $logger->pushHandler(new StreamHandler($filePath, Logger::DEBUG));
            $this->setLogger($logger);
        }

        return $this->logger;
    }

    protected function logMark($message, array $callers, $mode = 'skipped')
    {
        $context = [
            'test'      => $callers[1]['function'],
            'message'   => $message,
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
        $path =  getcwd().'/Resources/'.$file;

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
            'httpStatusCode'    => $httpStatusCode,
            'responseRaw'       => $this->getResourceContent($file),
        ]);

        return $response;
    }
}
