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

namespace Gpupo\CommonSdk\Client;

use Gpupo\CommonSdk\Exception\ManagerException;
use Gpupo\CommonSdk\Map;
use Gpupo\CommonSdk\Response;
use Gpupo\CommonSdk\Traits\LoggerTrait;
use Gpupo\CommonSdk\Request;

abstract class ClientManagerAbstract
{
    use ClientAwareTrait;
    use LoggerTrait;

    protected $maps;

    protected $dryRun;

    protected $lastRequest;

    public function __construct(ClientInterface $client = null)
    {
        if ($client) {
            $this->setClient($client);
        }

        $this->setUp();
    }

    protected function setUp()
    {
    }

    /**
     * @return null|Gpupo\CommonSdk\Response|true
     */
    public function getDryRun()
    {
        return $this->dryRun;
    }

    /**
     * Possibilita o uso de operações de gravação remota sem que estas sejam de
     * fato executadas. Isto é útil para testes.
     *
     * @param $value Gpupo\CommonSdk\Response
     *
     * <code>
     * //Exemplo de uso
     * $manager = new Manager();
     * $manager->setDryRun()->save($entity);
     *
     * </code>
     */
    public function setDryRun(Response $response = null)
    {
        if (empty($response)) {
            $response = true;
        }

        $this->dryRun = $response;

        return $this;
    }

    /**
     * Encontra a URL e método para uma execução de Request.
     *
     * @param string $operation  Operação de execução (save, fetch)
     * @param array  $parameters Parâmetros que serão alocados nos placeholders
     */
    public function factoryMap($operation, array $parameters = null)
    {
        if (!\is_array($this->maps)) {
            throw new ManagerException('Maps missed!');
        }

        if (!array_key_exists($operation, $this->maps)) {
            throw new ManagerException('Map ['.$operation.'] not found on ['.$this->getEntityName().' Manager]');
        }

        $data = $this->maps[$operation];
        if (!\is_array($data)) {
            throw new ManagerException('Map MUST be array');
        }

        return new Map($data, $parameters);
    }

    /**
     * @return bool
     */
    protected function isDryRun()
    {
        return !empty($this->dryRun);
    }

    protected function execute(Map $map, $body = null)
    {
        return $this->perform($map, $body);
    }

    protected function exceptionHandler(\Exception $exception, $method, $resource)
    {
        $text = $method.' on '.$resource.' FAIL:'.$exception->getMessage();

        $this->log('critical', $text, ['code' => $exception->getCode()]);

        return new ManagerException($text, $exception->getCode(), $exception);
    }

    /**
     * Possibilita hook com sobrecarga na implementação, para lidar com erros
     * que necessitam nova tentativa de execução.
     *
     * @param Exception $exception Exceção recebida no processo de execução do Request
     * @param int       $attempt   Numero da iteração para a mesma execução
     */
    protected function retry(\Exception $exception, $attempt)
    {
        return 1 === $attempt && $exception->getCode() >= 500;
    }

    protected function perform(Map $map, $body = null)
    {
        $dryRun = $this->getDryRun();

        if (empty($dryRun)) {
            return $this->performReturn($this->performReal($map, $body), 'Real', $map);
        }

        //factory a Request for DryRun
        $this->lastRequest = new Request([
            'body' => $body,
        ]);

        if ($dryRun instanceof Response) {
            return $this->performReturn($dryRun, 'Mockup', $map);
        }

        return $this->performReturn(true, 'Bypass', $map);
    }

    protected function performReturn($return, $mode, Map $map)
    {
        $this->log('debug', 'ClientManager:Perform', [
            'mode' => $mode,
            // 'map' => $map->toLog(),
        ]);

        return $return;
    }

    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    public function factoryRequestByMap(Map $map)
    {
        return $this->getClient()->factoryRequest($map->getResource());
    }

    public function downloadFile(Map $map, $filename)
    {
        $request = $this->factoryRequestByMap($map->getResource());

        return $this->downloadFileByRequest($request, $filename);
    }

    public function downloadFileByRequest(Request $request, $filename): string
    {
        $dryRun = $this->getDryRun();

        if (!empty($dryRun)) {
            return $filename;
        }

        if(!$this->getClient()->downloadFileByRequest($request, $filename)) {
            throw new ManagerException(sprintf('Error downloading %s file.', $filename));
        }

        return $filename;
    }

    protected function performReal(Map $map, $body = null)
    {
        $methodName = strtolower($map->getMethod());

        $attempt = 0;
        while ($attempt <= 5) {
            ++$attempt;

            try {
                if ($map->getMode()) {
                    $this->getClient()->setMode($map->getMode());
                }

                if ('get' === $methodName) {
                    //set the cache TTL
                    $parameter = null;
                } else {
                    $parameter = $body;
                }

                $result = $this->getClient()->{$methodName}($map->getResource(), $parameter);
                $this->log('debug', 'ClientManager:performReal', [
                    'delegated-to' => sprintf('$this->getClient()->%s()', $methodName),
                    'map' => $map->getResource(),
                    'body' => $body,
                ]);

                return $result;
            } catch (\Exception $exception) {
                if (!$this->retry($exception, $attempt)) {
                    throw $this->exceptionHandler(
                        $exception,
                        $map->getMethod(),
                        $map->getResource()
                    );
                }
            }
        }
    }
}
