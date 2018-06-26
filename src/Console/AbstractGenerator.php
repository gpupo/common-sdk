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

namespace Gpupo\CommonSdk\Console;

use Gpupo\Common\Entity\Collection;
use Gpupo\Common\Tools\StringTool;
use Gpupo\CommonSchema\ArrayCollection\Thing\CollectionInterface;
use Gpupo\CommonSchema\ArrayCollection\Thing\EntityInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Yaml\Yaml;

class AbstractGenerator
{
    protected $input;
    protected $output;
    protected $container;

    public function __construct(ArgvInput $input, ConsoleOutput $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->container = new Collection();
    }

    public function debug()
    {
        //dump($this->container);
    }

    protected function save($file, $content)
    {
        file_put_contents($file, $content);
        $this->output->writeln(sprintf('Generated <fg=green> %s </>', $file));
    }

    protected function getFieldDescription($key, $value)
    {
        switch ($value) {
                case 'string':
                    $spec = [
                        'type' => 'string',
                        'lenght' => 50,
                        'unique' => false,
                        'options' => [],
                    ];

                    break;
                case 'datetime':
                    $spec = [
                        'type' => 'datetime',
                        'options' => [],
                    ];

                    break;
                case 'number':
                    $spec = [
                        'type' => 'float',
                        'precision' => 10,
                        'scale' => 2,
                        'options' => [],
                    ];

                    break;
                case 'integer':
                    $spec = [
                        'type' => 'bigint',
                        'options' => [],
                    ];

                    break;
                case 'array':
                    $spec = [
                        'type' => 'array',
                        'options' => [],
                    ];

                    break;
                case 'boolean':
                    $spec = [
                        'type' => 'boolean',
                        'options' => [],
                    ];

                    break;
                case 'oneToOneBidirectional':
                    $spec = [
                        // 'type' => 'boolean',
                        // 'options' => [],
                    ];

                    break;
                default:
                    throw new \Exception(sprintf('Type %s not found', $value));

                    break;
            }

        return $spec;
    }

    public function recursiveSave($object)
    {
        if (!is_object($object)) {
            //String or Array
            return;
        }
        if ($object instanceof CollectionInterface) {
            return $this->recursiveSave($object->factoryElement([]));
        }

        if (!$object instanceof EntityInterface) {
            die(sprintf('Class %s must implement %s', get_class($object), EntityInterface::class));
        }

        $this->saveDataDoctrineMetadata($object);

        foreach ($object as $prop) {
            if (is_object($prop)) {
                $this->recursiveSave($prop);
            }
        }
    }
}
