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
 * @method setMethod(string $string)
 * @method setBody(string $string)
 * @method setUrl(string $string)
 * @method setHeader(array $string)
 */
class Request extends Collection
{
    public function getMethod(): ?string
    {
        return $this->get('method') ?: 'GET';
    }

    public function getBody(): ?string
    {
        return $this->get('body');
    }

    public function getUrl(): ?string
    {
        return $this->get('url');
    }

    public function getHeader(): array
    {
        return $this->get('header');
    }

    public function buildHeader(): array
    {
        $list = [];
        foreach ($this->getHeader() as $key => $value) {
            $list[] = sprintf('%s:%s', $key, $value);
        }

        return $list;
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
            ->setUrl($this->getUrl())
            ->setMethod($this->getMethod())
            ->setHeader($this->buildHeader())
            ->setBody($this->getBody());

        return $transport->exec();
    }

    public function toLog(): array
    {
        return [
            'url' => $this->getUrl(),
            'method' => $this->getMethod(),
            'header' => $this->getHeader(),
            'body' => $this->getBody(),
        ];
    }
}
