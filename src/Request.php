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

namespace Gpupo\CommonSdk;

use Gpupo\Common\Entity\Collection;

/**
 * @method setMethod(string $string)
 * @method setBody(string $string)
 * @method setUrl(string $string)
 * @method setHeader(array $string)
 */
class Request extends Collection
{
    /**
     * @return string
     */
    public function getBody()
    {
        return $this->get('body');
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->get('header');
    }

    public function setTransport(Transport $transport)
    {
        $this->set('transport', $transport);

        return $this;
    }

    public function getTransport()
    {
        $transport = $this->get('transport');

        if (!$transport instanceof Transport) {
            throw new Exception\ClientException('Transport missed');
        }

        return $transport;
    }

    public function exec()
    {
        $transport = $this->getTransport()
            ->setUrl($this->get('url'))
            ->setMethod($this->get('method', 'GET'))
            ->setHeader($this->getHeader())
            ->setBody($this->getBody());

        return $transport->exec();
    }
}
