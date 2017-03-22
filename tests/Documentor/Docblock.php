<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
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
            $dir = "{$this->resourcesPath}autodoc";
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

    public function generate(array $data = [], $json = null)
    {
        foreach ($data['schema'] as $item) {
            $case = $this->camelCase($item['name']);
            $data['magic_methods'][] = [
                'getter' => 'get'.$case,
                'setter' => 'set'.$case,
                'return' => $item['return'],
                'name'   => $item['name'],
                'type'   => $item['type'],
                'case'   => $case,
            ];
        }

        $data['block'] = $this->renderDocBlock($data);

        if (!empty($json)) {
            $this->renderJson($data, $json);
        }

        return  $this->renderTest($data);
    }

    protected function camelCase($name)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', ucfirst($name))));
    }

    protected function renderTest(array $data)
    {
        $conf = $data['config']['namespace'];
        $mode = 'default';

        if (array_key_exists('mode', $conf)) {
            $mode = $conf['mode'];
        }

        $array = explode('\\', $data['class']);
        $data['classShortName'] = end($array);
        $data['objectShortName'] = lcFirst($data['classShortName']);
        array_pop($array);

        $data['classNamespace'] = implode('\\', $array);
        $data['mainNamespace'] = $array[1];

        $array[0] = $array[0].'\\Tests';
        if ('bundle' === $mode) {
            array_shift($array);
        } else {
            array_shift($array);
            array_shift($array);
        }
        $data['testDirectory'] = 'tests/'.implode('/', $array);
        $data['testNamespace'] = implode('\\', $array);
        $data['asserts'] = $this->renderAsserts($data);
        $data['expected'] = $this->renderExpected($data);
        $data['filename'] = $data['testDirectory'].'/'.$data['classShortName'].'Test.php~';
        $data['content'] = $this->render($data, 'testCase');

        if (array_key_exists('testcase', $conf)) {
            $tc = $conf['testcase'];
        }

        $data['testcase'] = empty($tc) ? '\PHPUnit_Framework_TestCase' : $tc;

        if (false === strpos($data['testcase'], 'TestCaseAbstract')) {
            $data['testcase'] .= ' as TestCaseAbstract';
        }

        return $data;
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
