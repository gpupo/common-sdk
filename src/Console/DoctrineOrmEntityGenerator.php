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

use Gpupo\CommonSchema\ArrayCollection\Thing\CollectionInterface;
use Gpupo\CommonSchema\ArrayCollection\Thing\EntityInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Yaml\Yaml;

class DoctrineOrmEntityGenerator
{
    protected $input;
    protected $output;

    public function __construct(ArgvInput $input, ConsoleOutput $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function saveDataToSchema($key, $value, array $data)
    {
        $file = sprintf('Resources/schema/v2/%s.yaml', $key);
        $content = sprintf("# %s \n# generated at %s\n", $key, date('r')).Yaml::dump($data, 8);

        return $this->save($file, $content);
    }

    public function saveDataDoctrineMetadata($object)
    {
        $class = get_class($object);
        $explode = explode('\\', $class);

        $abstractList = ['People', 'Thing'];

        if (in_array($explode[3], $abstractList, true)) {
            return;
        }

        $toClass = str_replace('ArrayCollection', 'ORM\Entity', $class);
        $repositoryClass = str_replace('ArrayCollection', 'ORM\Repository', $class).'Repository';
        $table = $object->getTableName();
        $doctrine = [
                'type' => 'entity',
                'table' => $table,
                'repositoryClass' => $repositoryClass,
                'id' => [
                    'id' => ['type' => 'integer', 'generator' => ['strategy' => 'AUTO']],
                ],
            ];

        foreach ($object->getSchema() as $key => $value) {
            if ('id' === $key) {
                continue;
            }
            $doctrine['fields'][$key] = $this->generateDoctrineField($key, $value);
            $this->recursiveSaveDataDoctrineMetadata($object->get($key));
        }

        $doctrine['lifecycleCallbacks'] = [
                'prePersist' => [],
                'postPersist' => [],
            ];

        $entity = [$toClass => $doctrine];
        $file = sprintf('config/yaml/%s.dcm.yml', str_replace('\\', '.', $toClass));
        $content = sprintf("# %s metadata\n# generated at %s\n", $key, date('r')).Yaml::dump($entity, 8, 2);

        return $this->save($file, $content);
    }

    public function recursiveSaveDataDoctrineMetadata($object)
    {
        if (!is_object($object)) {
            return;
        }
        if ($object instanceof CollectionInterface) {
            return $this->recursiveSaveDataDoctrineMetadata($object->first());
        }

        if (!$object instanceof EntityInterface) {
            die(sprintf('Class %s must implement %s', get_class($object), EntityInterface::class));
        }

        $this->saveDataDoctrineMetadata($object);

        foreach ($object as $prop) {
            if (is_object($prop)) {
                $this->recursiveSaveDataDoctrineMetadata($prop);
            }
        }
    }

    protected function save($file, $content)
    {
        file_put_contents($file, $content);
        $this->output->writeln(sprintf('Generated <fg=green> %s </> file', $file));
    }

    protected function generateDoctrineField($key, $value)
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
                case 'object':
                    $spec = [
                        'type' => 'manyToMany',
                        'options' => [],
                    ];

                    break;
                case 'number':
                    $spec = [
                        'type' => 'decimal',
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
                default:
                    throw new \Exception(sprintf('Type %s not found', $value));
                    break;
            }

        $spec['options']['comment'] = '';

        return $spec;
    }
}
