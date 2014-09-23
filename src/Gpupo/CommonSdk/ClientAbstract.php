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
        $data = $request->exec();
        $response = new Response($data);
        
        $this->debug('exec',$response->toLog());
        
        return $response;
    }

    public function get($resource)
    {
        $request = $this->factoryRequest($resource);

        return $this->exec($request);
    }

    public function post($resource, $body)
    {
        $request = $this->factoryRequest($resource, true);

        curl_setopt($request->getAgent(), CURLOPT_POSTFIELDS, $body);

        return $this->exec($request);
    }

    public function put($resource, $body)
    {
        $this->debug('put', [
            'resource'  => $resource,
            'body'      => $body
        ]);
        
        $request = $this->factoryRequest($resource);
        
        $request->setMethod('PUT');
        curl_setopt($request->getAgent(), CURLOPT_PUT, true);

        $pointer = fopen('php://temp/maxmemory:512000', 'w+');
        //$pointer = tmpfile();
        if (!$pointer) {
            throw new \Exception('could not open temp memory data');
        }
        fwrite($pointer, $body);
        fseek($pointer, 0);

        curl_setopt($request, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($request, CURLOPT_INFILE, $pointer);
        curl_setopt($request, CURLOPT_INFILESIZE, strlen($body));

        //curl_setopt($request, CURLOPT_POSTFIELDS, $body);
        //curl_setopt($request, CURLOPT_CUSTOMREQUEST, "PUT");
        return $this->exec($request);
    }
}
