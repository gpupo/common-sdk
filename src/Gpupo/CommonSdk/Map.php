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
        if ($parameters) {
            foreach ($parameters as $key => $value) {
                $route = str_replace("{" . $key . "}", $value, $route);
            }
        }

        return $route;
    }
}
