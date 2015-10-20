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
        $data = [
            'method'        => $elements[0],
            'resource'      => $elements[1],
            'parameters'    => $parameters,
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
        return (empty($value) && $value !== 0 && $value !== '0');
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
            'endpoint'  => $this->getResource(),
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
