<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For more information, see
 * <http://www.g1mr.com/common-sdk/>.
 */

namespace Gpupo\CommonSdk\Transport\Driver;

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSdk\Exception\RuntimeException;

class CurlDriver extends DriverAbstract
{
    protected $header = [];

    protected $curl;

    protected $lastTransfer;

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

    /**
     * @see http://php.net/manual/pt_BR/function.curl-setopt.php
     */
    public function __construct(Collection $options)
    {
        $this->curl = curl_init();

        $sslVersion = $options->get('sslVersion', 'SecureTransport');
        $this->setOption(CURLOPT_SSLVERSION, $sslVersion);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLINFO_HEADER_OUT, true);
        $this->setOption(CURLOPT_VERBOSE, $options->get('verbose'));
        $this->setOption(CURLOPT_SSL_VERIFYPEER, $options->get('sslVerifyPeer', true));

        parent::__construct([]);
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

    public function getMethod()
    {
        return strtoupper($this->get('method', 'GET'));
    }

    protected function execPost()
    {
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $this->getBody());

        return $this;
    }

    protected function execPut()
    {
        $this->setOption(CURLOPT_PUT, true);
        $pointer = fopen('php://temp/maxmemory:512000', 'w+');

        if (!$pointer) {
            throw new RuntimeException('Could not open temp memory data');
        }

        fwrite($pointer, $this->getBody());
        fseek($pointer, 0);

        $this->setOption(CURLOPT_BINARYTRANSFER, true);
        $this->setOption(CURLOPT_INFILE, $pointer);
        $this->setOption(CURLOPT_INFILESIZE, strlen($this->getBody()));

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
        }

        $data = [
            'responseRaw'       => curl_exec($this->curl),
            'httpStatusCode'    => $this->getInfo(CURLINFO_HTTP_CODE),
        ];

        $this->close();

        return $data;
    }

    protected function close()
    {
        $this->lastTransfer = new Collection(curl_getinfo($this->curl));
        $this->register();

        return curl_close($this->curl);
    }

    protected function registerSaveToFile()
    {
        $filename = $this->getRegisterFilename();
        $data = "\n\n#===\n".$this->registerEncode('cUrl', $this->getLastTransfer());
        $data .= $this->registerEncode('url', $this->get('url'), false);
        $data .= $this->registerEncode('header', implode("\n", $this->header), false);
        $body = $this->getBody();
        if (!empty($body)) {
            $data .= $this->registerEncode('Body', $body, false);
        }

        return file_put_contents($filename, $data, FILE_TEXT);
    }
}
