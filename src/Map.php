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

namespace Gpupo\CommonSdk;

use Gpupo\Common\Entity\Collection;

/**
 * @method string getMethod()
 * @method array  getParameters()
 */
class Map extends Collection
{
    public function __construct(array $elements = [], array $parameters = null)
    {
        if (2 > \count($elements)) {
            throw new \Exception('Map require elements');
        }

        $data = [
            'method' => $elements[0],
            'resource' => $elements[1],
            'parameters' => $parameters,
        ];

        parent::__construct($data);
    }

    public function getResource(): string
    {
        $route = $this->get('resource');
        $parameters = $this->getParameters();
        if (!empty($parameters) && \is_array($parameters)) {
            $route = $this->populatePlaceholders($route, $parameters);
        }

        return $route;
    }

    public function toLog(): array
    {
        return array_merge($this->toArray(), [
            'endpoint' => $this->getResource(),
        ]);
    }

    public function getMode()
    {
        $parameters = $this->getParameters();

        if (\is_array($parameters) && array_key_exists('mode', $parameters)) {
            return $parameters['mode'];
        }
    }

    protected function placeHolderValueEmpty($value): bool
    {
        return empty($value) && 0 !== $value && '0' !== $value;
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
}
