<?php

namespace Gpupo\CommonSdk;

abstract class ClientAbstract
{
    use Traits\LoggerTrait;
    use Traits\SingletonTrait;
    use Traits\OptionsTrait;

    protected function factoryTransport()
    {
        return new Transport($this->getOptions());
    }

    public function factoryRequest($resource, $post = false)
    {
        $request = new Request;

        if ($post) {
            $request->setMethod('POST');
        }

        $request->setTransport($this->factoryTransport())
            ->setUrl($this->getResourceUri($resource));

        return $request;
    }

    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    protected function exec(Request $request)
    {
        try {
            $data = $request->exec();
            $response = new Response($data);
            $response->setLogger($this->getLogger());
            $response->validate();

            $this->debug('Response',$response->toLog());

            return $response;
        } catch (\Exception $e) {
            $this->error('Execucao fracassada', [
                'exception' => $e->toLog(),
                'request'   => $request->toLog(),
            ]);

            throw $e;
        }
    }

    public function get($resource)
    {
        $request = $this->factoryRequest($resource);

        return $this->exec($request);
    }

    public function post($resource, $body)
    {
        $request = $this->factoryRequest($resource, true)
            ->setBody($body);

        return $this->exec($request);
    }

    public function put($resource, $body)
    {
        $request = $this->factoryRequest($resource)->setBody($body)
            ->setMethod('PUT');

        return $this->exec($request);
    }
}
