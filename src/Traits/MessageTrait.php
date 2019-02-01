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

namespace Gpupo\CommonSdk\Traits;

use Psr\Http\Message\StreamInterface;

/**
 * Trait implementing functionality common to requests and responses.
 */
trait MessageTrait
{
    private $protocol = '1.1';

    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    public function withProtocolVersion($version)
    {
        if ($this->protocol === $version) {
            return $this;
        }

        $new = clone $this;
        $new->protocol = $version;

        return $new;
    }

    public function getHeaders()
    {
        return $this->get('header');
    }

    public function hasHeader($header)
    {
        $headers = $this->getHeaders();

        return isset($headers[strtolower($header)]);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($header = null): array
    {
        $headers = $this->getHeaders();

        if (empty($header)) {
            return $headers;
        }

        return $headers[$header];
    }

    public function getHeaderLine($header)
    {
        return implode(', ', $this->getHeader($header));
    }

    /**
     * @see https://www.php-fig.org/psr/psr-7/
     *
     * @todo PSR-7 Implement
     *
     * @param mixed $header
     * @param mixed $value
     */
    public function withHeader($header, $value)
    {
    }

    /**
     * @see https://www.php-fig.org/psr/psr-7/
     *
     * @todo PSR-7 Implement
     *
     * @param mixed $header
     * @param mixed $value
     */
    public function withAddedHeader($header, $value)
    {
    }

    /**
     * @see https://www.php-fig.org/psr/psr-7/
     *
     * @todo PSR-7 Implement
     *
     * @param mixed $header
     */
    public function withoutHeader($header)
    {
    }

    public function getBody(): ?string
    {
        return $this->get('body');
    }

    public function getMethod(): ?string
    {
        return $this->get('method') ?: 'GET';
    }

    public function withBody(StreamInterface $body)
    {
        if ($body === $this->getBody()) {
            return $this;
        }

        $new = clone $this;
        $new->setBody($body);

        return $new;
    }
}
