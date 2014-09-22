<?php
namespace Gpupo\CommonSdk;

interface ClientInterface
{
    public function getDefaultOptions();
    public function getResourceUri($resource);
}
