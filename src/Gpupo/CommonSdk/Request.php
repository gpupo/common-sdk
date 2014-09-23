<?php

namespace Gpupo\CommonSdk;

use Gpupo\CommonSdk\Entity\Collection;

class Request extends Collection
{
    protected $transport;
    
    protected $url;
    
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function setTransport(Transport $transport)
    {
        $this->transport = $transport;
        
        return $this;
    }
    
    public function getTransport()
    {
        return $this->transport;
    }
    
    public function exec()
    {
        return $this->getTransport()->setUrl($this->url)
            ->setMethod($this->get('method', 'GET'))
            ->exec();
    }
    
    public function toLog()
    {
        return [
            'method'    => $this->method,
            'transport' => $this->getTransport()->toLog(),
        ];
    }
}
