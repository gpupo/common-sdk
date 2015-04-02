<?php

/*
 * This file is part of common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\CommonSdk;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class TestCaseAbstract extends \PHPUnit_Framework_TestCase
{    
    public function assertHttpStatusCodeSuccess($code, $context = null)
    {
        $this->assertContains($code, array(200, 204), $context);
    }

    public function getLogger()
    {
        $channel = str_replace('\\', '.', get_called_class());
        $log = new Logger($channel);
        $filePath = $this->getResourceFilePath('logs/tests.log', true);
        
        $log->pushHandler(new StreamHandler($filePath, Logger::DEBUG));

        return $log;
    }

    protected function hasToken()
    {
        $token = $this->getConstant('API_TOKEN');

        return empty($token) ? false : true;
    }

    protected function getConstant($name, $default = false)
    {
        if (defined($name)) {
            return constant($name);
        }

        return $default;
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
}
