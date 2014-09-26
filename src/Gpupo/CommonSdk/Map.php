<?php

namespace Gpupo\CommonSdk;

use Gpupo\CommonSdk\Entity\Collection;

class Map extends Collection
{
    public function __construct(array $elements = array(), array $parameters = null)
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

    protected function populatePlaceholders($route, $parameters)
    {
        foreach ($parameters as $key => $value) {
            if (!empty($value)) {
                $route = str_replace("{" . $key . "}", $value, $route);
            } else {
                $route = str_replace('&' . $key . "={" . $key . "}", '', $route);
            }
        }

        return $route;
    }
}
