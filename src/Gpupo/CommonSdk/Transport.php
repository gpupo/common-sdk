<?php

namespace Gpupo\CommonSdk;

use Gpupo\CommonSdk\Entity\Collection;

class Transport
{
    protected $curl;

    public function setOption($option, $value)
    {
        return curl_setopt($this->curl, $option, $value);
    }

    public function __construct(Collection $options)
    {
        $this->curl = curl_init();
        $this->setOption(CURLOPT_SSLVERSION, 3);
        $this->setOption(CURLOPT_RETURNTRANSFER, true );
        $this->setOption(CURLOPT_VERBOSE, $options->get('verbose'));
    }
    
    public function setUrl($url)
    {
        $this->setOption(CURLOPT_URL, $url);
        
        return $this;
    }
    
    public function setMethod($method)
    {
        switch (strtoupper($method)) {
            case 'POST':
                $this->setOption(CURLOPT_POST, true);
                break;
        }
        
        return $this;
    }
    
    public function exec()
    {
        $data = [];
        $data['responseRaw'] = curl_exec($this->curl);
        $data['httpStatusCode'] = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $data['requestInfo'] = curl_getinfo($this->curl);
        
        curl_close($this->curl);
        
        return $data;
    }
}
