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

    protected function resourceDecodeSerializedFile(string $file)
    {
        $content = $this->resourceReadFile($file);

        return unserialize($content);
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

        if (false !== mb_strpos($file, 'private')) {
            return $this->getResourceFilePath(str_replace('private', 'public', $file), $create);
        }

        if ($create) {
            touch($path);

            return $this->getResourceFilePath($file);
        }

        throw new \InvalidArgumentException('File '.$path.' Not Exist');
    }

    protected function saveResourceToYamlFile(string $filename, array $array): void
    {
        $content = Yaml::dump($array, 6, 2);
        $this->_file_put_contents($filename, $content);
    }

    protected function saveResourceToSerializedFile(string $filename, $data): void
    {
        $content = serialize($data);
        $this->_file_put_contents($filename, $content);
    }

    protected function saveResourceToCsvFile(string $filename, array $array): void
    {
        $file = fopen($filename, 'w');

        $i = 0;
        foreach ($array as $data) {
            if (0 === $i) {
                fputcsv($file, array_keys($data));
            }
            ++$i;

            fputcsv($file, $data);
        }

        fclose($file);
    }

    private function _file_put_contents(string $filename, string $content): void
    {
        file_put_contents($filename, $content);
    }
}
