<?php
namespace Gpupo\CommonSdk\Traits;

use Gpupo\CommonSdk\Entity\Collection;

trait OptionsTrait
{
    protected $options = [];

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(Array $options = [])
    {
        $list = array_merge($this->getDefaultOptions(), $options);

        $this->options = new Collection($list);

        return $this;
    }
}
