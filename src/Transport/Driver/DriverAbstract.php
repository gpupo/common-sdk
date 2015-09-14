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

/**
 * @method setMethod(string $string)
 * @method string getBody()
 */
abstract class DriverAbstract extends Collection
{
    protected $registerPath;

    protected $containerLog = [];

    public function toLog()
    {
        return $this->containerLog;
    }

    abstract public function exec();
    abstract public function getMethod();
    abstract public function setUrl($url);
    abstract public function setHeader(array $list);
    abstract public function setOption($option, $value);

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

        throw new RuntimeException('Impossivel registrar em '.$this->registerPath);
    }

    /**
     * @param string $title
     */
    protected function registerEncode($title, $data, $encode = true)
    {
        if ($encode === true) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return '## '.$title.':'."\n".$data."\n";
    }

    abstract protected function registerSaveToFile();

    public function register()
    {
        if (!empty($this->registerPath)) {
            try {
                return $this->registerSaveToFile();
            } catch (\Exception $e) {
                $this->containerLog['err'][] = $e->getMessage();

                return false;
            }
        }
    }
}
