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

namespace Gpupo\CommonSdk\Traits;

trait ResourcesTrait
{
        public static function getResourcesPath()
        {
            return 'Resources/';
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
            }

            if (false !== strpos($file, 'private')) {
                return $this->getResourceFilePath(str_replace('private', 'public', $file), $create);
            }

            if ($create) {
                touch($path);

                return $this->getResourceFilePath($file);
            }

            throw new \InvalidArgumentException('File '.$path.' Not Exist');
        }
}
