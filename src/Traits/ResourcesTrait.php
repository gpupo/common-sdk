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

use Symfony\Component\Yaml\Yaml;

trait ResourcesTrait
{
    public static function getResourcesPath()
    {
        return 'Resources/';
    }

    protected function resourceReadFile(string $file): string
    {
        return file_get_contents($file);
    }

    protected function resourceDecodeJsonFile(string $file): array
    {
        return json_decode($this->resourceReadFile($file), true);
    }

    protected function resourceDecodeYamlFile(string $file): array
    {
        return Yaml::parseFile($file);
    }

    protected function getResourceContent(string $file): string
    {
        return $this->resourceReadFile($this->getResourceFilePath($file));
    }

    protected function getResourceJson(string $file): array
    {
        return $this->resourceDecodeJsonFile($this->getResourceFilePath($file));
    }

    protected function getResourceYaml(string $file): array
    {
        return $this->resourceDecodeYamlFile($this->getResourceFilePath($file));
    }

    protected function getResourceFilePath(string $file, bool $create = false): string
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
