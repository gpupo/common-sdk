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

namespace Gpupo\CommonSdk;

use Gpupo\Common\Entity\Collection;

/**
 * @method string getMethod()
 * @method array getParameters()
 */
class Map extends Collection
{
    public function __construct(array $elements = [], array $parameters = null)
    {
        if (2 > count($elements)) {
            throw new \Exception('Map require elements');
        }

        $data = [
            'method'     => $elements[0],
            'resource'   => $elements[1],
            'parameters' => $parameters,
        ];

        parent::__construct($data);
    }

    public function getResource()
    {
        $route = $this->get('resource');
        $parameters = $this->getParameters();
        if (!empty($parameters) && is_array($parameters)) {
            $route = $this->populatePlaceholders($route, $parameters);
        }

        return $route;
    }

    protected function placeHolderValueEmpty($value)
    {
        return empty($value) && $value !== 0 && $value !== '0';
    }

    protected function populatePlaceholders($route, $parameters)
    {
        foreach ($parameters as $key => $value) {
            if ($this->placeHolderValueEmpty($value)) {
                $route = str_replace('&'.$key.'={'.$key.'}', '', $route);
            } else {
                $route = str_replace('{'.$key.'}', $value, $route);
            }
        }

        return $route;
    }

    public function toLog()
    {
        return array_merge($this->toArray(), [
            'endpoint' => $this->getResource(),
        ]);
    }

    public function getMode()
    {
        $parameters = $this->getParameters();

        if (is_array($parameters) && array_key_exists('mode', $parameters)) {
            return $parameters['mode'];
        }
    }
}
