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
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;

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
        $loader = new \Twig_Loader_Filesystem(__DIR__);
        $twig = new \Twig_Environment($loader, []);

        return $twig->render($template.'.twig', $data);
    }

    public function discovery(ReflectionClass $reflect)
    {
        $props   = $reflect->getProperties();
        $data = [
            'class' => $reflect->getName(),
            'config' => [
                'namespace' => [
                    $reflect->getNamespaceName(),
                ],
            ]
        ];

        foreach($props as $prop) {
            $factory  = DocBlockFactory::createInstance();
            $docblock = $factory->create($prop->getDocComment());
            $var = $docblock->getTagsByName('var');
            $type = 'undefined';

            if (is_array($var)) {
                if (is_array($var)) {
                    $var = current($var);
                }

                if ($var instanceof \phpDocumentor\Reflection\DocBlock\Tags\Var_) {
                    $type = $var->__toString();
                }
            }

            $data['schema'][] = [
                'name' => $prop->name,
                'return' => $type,
                'type' => $type,
                'summary' => $docblock->getSummary(),
            ];
        }

        return $data;
    }

    public function generate(array $data = [], $json = null)
    {
        foreach ($data['schema'] as $item) {
            $case = $this->camelCase($item['name']);
            $getter = 'get'.$case;
            $setter = 'set'.$case;

            $fixture = '"'.trim(str_replace(['null|'], '', $item['return'])).'"';
            if (strpos($item['return'], 'DateTime') !== false) {
                $fixture = 'new \DateTime()';
            } elseif (strpos($item['return'], 'bool') !== false) {
                $fixture = true;
            } elseif (strpos($item['return'], 'int') !== false) {
                $fixture = rand();
            } elseif (strpos($item['return'], 'array') !== false) {
                $fixture = '["foo"=>"bar"]';
            } elseif (strpos($item['return'], 'undefined') !== false || strpos($item['return'], 'string') !== false) {
                $fixture = '"'.substr(md5(mt_rand()), 0, 7).'"';
            }

            $data['magic_methods'][] = [
                'getter' => $getter,
                'setter' => $setter,
                'return' => $item['return'],
                'fixture' => $fixture,
                'summary' => $item['summary'],
                'name'   => $item['name'],
                'type'   => $item['type'],
                'case'   => $case,
            ];

            foreach([$getter, $setter] as $m) {
                if (($key = array_search($m, $data['methods'])) !== false) {
                    unset($data['methods'][$key]);
                }
            }
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

        $testNamespace = implode('\\', $array);
        array_shift($array);
        if ('simple' === $mode) {
            array_shift($array);
        }

        $data['testDirectory'] = 'tests/'.implode('/', $array);
        $data['testNamespace'] = $testNamespace;
        $data['filename'] = $data['testDirectory'].'/'.$data['classShortName'] .'Test.php';
        $data['testShortName'] = $data['classShortName'] . 'Test';

        if (file_exists($data['filename'])) {
            $data['testShortName'] = $data['classShortName'] .  'GeneratedTest';
            $data['filename'] = $data['testDirectory'].'/'.$data['testShortName'] .'.php';
        }



        $data['asserts'] = $this->renderAsserts($data);
        $data['expected'] = $this->renderExpected($data);

        $data['content'] = $this->render($data, 'testCase');

        if (array_key_exists('testcase', $conf)) {
            $tc = $conf['testcase'];
        }

        $data['testcase'] = empty($tc) ? 'TestCase' : $tc;

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
