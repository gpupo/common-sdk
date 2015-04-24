<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\CommonSdk;

use Gpupo\Common\Entity\Collection;
use Gpupo\CommonSdk\Exception\RuntimeException;

/**
 * @method setMethod(string $string)
 * @method string getBody()
 */
class Transport extends Collection
{
    protected $curl;

    protected $registerPath;

    protected $containerLog = [];

    public function setOption($option, $value)
    {
        return curl_setopt($this->curl, $option, $value);
    }

    public function getInfo($option)
    {
        return curl_getinfo($this->curl, $option);
    }

    public function __construct(Collection $options)
    {
        $this->curl = curl_init();

        $sslVersion =  $options->get('sslVersion', 'SecureTransport');
        $this->setOption(CURLOPT_SSLVERSION, $sslVersion);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_VERBOSE, $options->get('verbose'));

        parent::__construct([]);
    }

    public function setHeader(array $list)
    {
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

        $this->register();

        curl_close($this->curl);

        return $data;
    }

    /**
     * Permite o registro de cada requisição em arquivo.
     *
     * @param string $path Caminho completo do diretório para gravação de arquivos
     */
    public function setRegisterPath($path)
    {
        $this->registerPath = $path;
    }

    protected function getRegisterFilename()
    {
        $filename = $this->registerPath.'/request-'.date('Y-m-d-h-i-s').'.txt';
        touch($filename);

        if (file_exists($filename)) {
            return $filename;
        }

        throw new Exception\RuntimeException('Impossivel registrar em '.$path);
    }

    public function register()
    {
        $encode = function ($data) {
            return json_encode($data, JSON_PRETTY_PRINT)."\n\n";
        };

        if (!empty($this->registerPath)) {
            try {
                $filename = $this->getRegisterFilename();
                $data = $encode(curl_getinfo($this->curl));

                $body = $this->getBody();
                if (!empty($body)) {
                    $data .= $encode(json_decode($body));
                }

                @file_put_contents($filename, $data, FILE_TEXT);
            } catch (\Exception $e) {
                $this->containerLog['err'][] = $e->getMessage();
            }
        }

        return $this;
    }

    public function toLog()
    {
        return $this->containerLog;
    }
}
