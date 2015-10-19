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
            $file = str_replace("\\", '_', $file);
            $dir = "{$this->resourcesPath}Documentation";
            $path =  "$dir/{$file}";
            touch ($path);

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
        foreach($data['schema'] as $item) {
            $case = $this->camelCase($item['name']);
            $data['methods'][] = [
                'getter'    => 'get'.$case,
                'setter'    => 'set'.$case,
                'return'    => $item['return'],
                'name'      => $item['name'],
                'type'      => $item['type'],
                'case'      => $case,
            ];
        }

        $data['block'] =  $this->renderDocBlock($data);

        $this->renderTest($data);

        if(!empty($json)) {
            $this->renderJson($data, $json);
        }


    }

    protected function camelCase($name)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', ucfirst($name))));
    }

    protected function renderTest(array $data)
    {
        $dest = $this->getResourcesDestinationPath("testCase_{$data['class']}.php");
        $data['asserts'] =  $this->renderAsserts($data);
        $data['expected'] =  $this->renderExpected($data);

        if ($dest) {
            file_put_contents($dest, $this->render($data, 'testCase'));
        }
    }

    protected function renderJson(array $data, $json)
    {
        $dest = $this->getResourcesDestinationPath("{$data['class']}.json");

        if ($dest) {
            file_put_contents($dest, $json);
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
