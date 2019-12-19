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

/**
 * @method        setMethod(string $string)
 * @method string getBody()
 */
abstract class DriverAbstract extends Collection
{
    protected $registerPath;

    protected $containerLog = [];

    public function toLog(): array
    {
        return $this->containerLog;
    }

    abstract public function exec();

    abstract public function setUrl($url);

    abstract public function setHeader(array $list);

    abstract public function setOption($option, $value);

    public function getMethod()
    {
        $string = $this->get('method');

        return mb_strtoupper(empty($string) ? 'GET' : $string);
    }

    public function getBody()
    {
        return $this->get('body');
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

    public function register($data = null)
    {
        if (!empty($this->registerPath)) {
            try {
                return $this->registerSaveToFile($data);
            } catch (\Exception $e) {
                $this->containerLog['err'][] = $e->getMessage();

                return false;
            }
        }
    }

    protected function getRegisterFilename()
    {
        $filename = $this->registerPath.'/requests-'.$this->getMethod().'.log';
        touch($filename);

        if (file_exists($filename)) {
            return $filename;
        }

        throw new RuntimeException('Impossivel registrar em '.$this->registerPath);
    }

    protected function registerSaveToFile($data = null)
    {
        if (empty($data)) {
            return;
        }

        $filename = $this->getRegisterFilename();

        return file_put_contents($filename, $data, FILE_APPEND | FILE_TEXT);
    }

    /**
     * @param string $title
     * @param mixed  $data
     * @param mixed  $encode
     */
    protected function registerEncode($title, $data, $encode = true)
    {
        if (true === $encode) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        return '* '.$title.':'.$data."\n";
    }
}
