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

    protected function render($data, $template)
    {
        $loader = new Twig_Loader_String();
        $twig = new Twig_Environment($loader);

        return $twig->render(file_get_contents(__DIR__.'/'.$template.'.twig'), $data);
    }

    public function generate(array $data)
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

        return "\n\r".$this->renderDocBlock($data, 'methods')
         ."\n\r" . $this->renderAssertsBlock($data, 'methods')."\n";
    }

    protected function camelCase($name)
    {
        return ucfirst($name);
    }

    public function renderAssertsBlock(array $data)
    {
        return $this->render($data, 'asserts');
    }

    public function renderDocBlock(array $data)
    {
        return $this->render($data, 'methods');
    }

}
