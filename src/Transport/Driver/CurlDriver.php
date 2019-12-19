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

namespace Gpupo\CommonSdk\Transport\Driver;

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSdk\Exception\RuntimeException;

class CurlDriver extends DriverAbstract
{
    protected $header = [];

    protected $curl;

    protected $lastTransfer;

    /**
     * @see http://php.net/manual/pt_BR/function.curl-setopt.php
     */
    public function __construct(Collection $options)
    {
        $this->curl = \curl_init();

        $sslVersion = $options->get('sslVersion', 'SecureTransport');
        $this->setOption(CURLOPT_SSLVERSION, $sslVersion);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLINFO_HEADER_OUT, true);
        $this->setOption(CURLOPT_VERBOSE, $options->get('verbose'));
        $this->setOption(CURLOPT_SSL_VERIFYPEER, $options->get('sslVerifyPeer', true));

        parent::__construct([]);
    }

    public function getLastTransfer()
    {
        return $this->lastTransfer;
    }

    public function setOption($option, $value)
    {
        return curl_setopt($this->curl, $option, $value);
    }

    public function getInfo($option = 0)
    {
        return curl_getinfo($this->curl, $option);
    }

    public function setHeader(array $list)
    {
        $this->header = $list;

        $this->setOption(CURLOPT_HTTPHEADER, $list);

        return $this;
    }

    public function setUrl($url)
    {
        $this->set('url', $url);
        $this->setOption(CURLOPT_URL, $url);

        return $this;
    }

    public function exec()
    {
        switch ($this->getMethod()) {
            case 'POST':
                $this->execPost();

                break;
            case 'PUT':
                $this->execPut();

                break;
            case 'PATCH':
                $this->execPatch();

                break;
            case 'DELETE':
                $this->execDelete();

                break;
        }

        $data = [
            'responseRaw' => curl_exec($this->curl),
            'httpStatusCode' => $this->getInfo(CURLINFO_HTTP_CODE),
        ];

        $this->close($data);

        return $data;
    }

    protected function execPost()
    {
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $this->getBody());

        return $this;
    }

    protected function writeBody()
    {
        $pointer = fopen('php://temp/maxmemory:512000', 'w+');

        if (!$pointer) {
            throw new RuntimeException('Could not open temp memory data');
        }

        fwrite($pointer, $this->getBody());
        fseek($pointer, 0);

        $this->setOption(CURLOPT_BINARYTRANSFER, true);
        $this->setOption(CURLOPT_INFILE, $pointer);
        $this->setOption(CURLOPT_INFILESIZE, \mb_strlen($this->getBody()));

        return $this;
    }

    protected function execPut()
    {
        $this->setOption(CURLOPT_PUT, true);

        return $this->writeBody();
    }

    protected function execPatch()
    {
        $this->execPut()->setOption(CURLOPT_CUSTOMREQUEST, 'PATCH');

        return $this;
    }

    protected function execDelete()
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this;
    }

    protected function close($data = null)
    {
        $this->lastTransfer = new Collection(curl_getinfo($this->curl));
        $this->register($this->dataToRegister($data));

        return curl_close($this->curl);
    }

    protected function dataToRegister($array)
    {
        $data = "\n.......start.......\n".$this->registerEncode('at', date('Y-m-d H:i:s'), false).$this->registerEncode('url', $this->get('url'), false).$this->registerEncode('method', $this->getMethod(), false).$this->registerEncode('header', implode("\n", $this->header), false);

        $body = $this->getBody();

        if (!empty($body)) {
            $data .= $this->registerEncode('Body', $body, false);
        }

        $data .= $this->registerEncode('transfer', $this->getLastTransfer(), false)."---\n\n* Response\n";

        foreach ($array as $k => $v) {
            $data .= $this->registerEncode($k, $v, false);
        }
        $data .= "\n.......end.......\n";

        return $data;
    }
}
