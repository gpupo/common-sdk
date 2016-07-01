<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\Tests\CommonSdk\Documentor;

use Gpupo\Common\Traits\SingletonTrait;
use Twig_Environment;
use Twig_Loader_String;

class Docblock
{
    use SingletonTrait;

    protected $resourcesPath;

    public function setResourcesPath($path)
    {
        $this->resourcesPath = $path;

        return $this;
    }

    public function getResourcesDestinationPath($file)
    {
        if (!empty($this->resourcesPath)) {
            $file = str_replace('\\', '_', $file);
            $dir = "{$this->resourcesPath}Documentation";
            $path = "$dir/{$file}";
            touch($path);

            return $path;
        }
    }

    protected function render($data, $template)
    {
        $loader = new Twig_Loader_String();
        $twig = new Twig_Environment($loader);

        return $twig->render(file_get_contents(__DIR__.'/'.$template.'.twig'), $data);
    }

    public function generate(array $data, $json = null)
    {
        foreach ($data['schema'] as $item) {
            $case = $this->camelCase($item['name']);
            $data['methods'][] = [
                'getter' => 'get'.$case,
                'setter' => 'set'.$case,
                'return' => $item['return'],
                'name'   => $item['name'],
                'type'   => $item['type'],
                'case'   => $case,
            ];
        }

        $data['block'] = $this->renderDocBlock($data);

        $this->renderTest($data);

        if (!empty($json)) {
            $this->renderJson($data, $json);
        }
    }

    protected function camelCase($name)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', ucfirst($name))));
    }

    protected function renderTest(array $data)
    {
        $array = explode('\\', $data['class']);
        $data['classShortName'] = end($array);
        $data['objectShortName'] = lcFirst($data['classShortName']);
        array_pop($array);
        $data['classNamespace'] = implode('\\', $array);
        $data['mainNamespace'] = $array[1];
        $array[2] = 'Tests\\'.$array[2];
        $data['testNamespace'] = implode('\\', $array);
        $dest = $this->getResourcesDestinationPath("testCase_{$data['class']}.php");
        $data['asserts'] = $this->renderAsserts($data);
        $data['expected'] = $this->renderExpected($data);

        if ($dest) {
            echo 'Test Case file generated: '.$dest."\n";
            file_put_contents($dest, $this->render($data, 'testCase'));
        }
    }

    protected function renderJson(array $data, $json)
    {
        $dest = $this->getResourcesDestinationPath("{$data['class']}.json");

        if ($dest) {
            file_put_contents($dest, $json);
            echo 'Json file generated: '.$dest."\n";
        }
    }

    protected function renderAsserts(array $data)
    {
        return $this->render($data, 'asserts');
    }

    protected function renderDocBlock(array $data)
    {
        return $this->render($data, 'methods');
    }

    protected function renderExpected(array $data)
    {
        return $this->render($data, 'schema');
    }
}
