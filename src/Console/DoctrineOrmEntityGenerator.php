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
use Gpupo\CommonSdk\Entity\Schema\Tools;

class DoctrineOrmEntityGenerator extends AbstractGenerator
{
    public function saveDataDoctrineMetadata($object)
    {
        $class = get_class($object);
        $explode = explode('\\', $class);

        $subnamespace = $explode[3];
        $lastname = end($explode);

        $classNames = $this->processClassNames($object, $class);
        $table = $object->getTableName();
        $doctrine = [
            'type' => 'entity',
            'table' => $table,
            'repositoryClass' => $classNames['repository'],
            'id' => [
                'id' => ['type' => 'integer', 'generator' => ['strategy' => 'AUTO']],
            ],
            'fields' => [],
        ];

        $fields = [];

        $recursiveTodo = [];

        $this->output->writeln("\n".$classNames['to']);

        foreach ($object->getSchema() as $key => $value) {
            if ('id' === $key) {
                continue;
            }
            if (Tools::isObjectType($value)) {
                $meta = $this->generateDoctrineObject($object, $classNames, $key, $value, $lastname);
                if (in_array($meta['associationMappingType'], ['oneToMany', 'manyToMany'], true)) {
                    $propertyKey = StringTool::normalizeToPlural($key);
                } else {
                    $propertyKey = StringTool::normalizeToSingular($key);
                }
                $doctrine[$meta['associationMappingType']][$propertyKey] = $meta['spec'];

                // if('oneToOneBidirectional' === $value) {
                //     $fields[$key] = $this->getFieldDescription($key, $value);
                // }

                // $this->output->writeln(sprintf('     - Key is <bg=black;fg=white> %s </> and Association type is <bg=white;fg=blue> %s </>', $propertyKey, $meta['associationMappingType']));
            } else {
                $fields[$key] = $this->getFieldDescription($key, $value);
            }

            $recursiveTodo[] = $object->get($key);


        }

        $doctrine['uniqueConstraints'] = $object->getUniqueConstraints();

        ksort($fields);
        $doctrine['fields'] = $fields;

        foreach([
            'oneToOne' => $classNames['to'],
            'manyToOne' => $classNames['to'],
        ] as $mode => $modeTo) {
            $modeMethod = sprintf('get%s', ucfirst($mode));
            $modeValue = $this->{$modeMethod}($modeTo);
            if (!empty($modeValue)) {
                if (array_key_exists($mode, $doctrine)) {
                    $doctrine[$mode] = array_merge($doctrine[$mode], $modeValue);
                } else {
                    $doctrine[$mode] = $modeValue;
                }
            }
        }

        $doctrine['lifecycleCallbacks'] = [
                'prePersist' => [],
                'postPersist' => [],
        ];

        $entity = [$classNames['to'] => $doctrine];
        $file = sprintf('Resources/metadata/%s.dcm.yml', str_replace('\\', '.', $classNames['to']));
        $content = sprintf("# %s metadata\n", $key).Yaml::dump($entity, 8, 2);

        $this->save($file, $content);

        foreach($recursiveTodo as $todo) {
             $this->recursiveSave($todo);
        }

            return true;
    }

    protected function processClassNames($object, $class)
    {
        $target = $class;

        if ($object instanceof CollectionInterface) {
            $target = get_class($object->factoryElement([]));
        }

        $explode = explode('\\', $target);

        return [
            'to' => str_replace('ArrayCollection', 'ORM\Entity', $target),
            'lastname' => end($explode),
            'repository' => str_replace('ArrayCollection', 'ORM\Repository', $target).'Repository',
        ];
    }

    protected function factoryContainerKey($prefix, $target)
    {
        return $prefix . '_' . StringTool::normalizeToSlug($target);
    }

    protected function setManyToOne($target, $childSpec)
    {
        $key = $this->factoryContainerKey('manyToOne', $target);

        $this->container->set($key, $childSpec);
    }

    protected function getManyToOne($target)
    {
        return $this->container->get($this->factoryContainerKey('manyToOne', $target));
    }

    protected function setOneToOne($target, $childSpec)
    {
        $key = $this->factoryContainerKey('oneToOne', $target);
        $this->output->writeln(sprintf('     - set <bg=black;fg=white> %s </>', $key));
        $this->container->set($key, $childSpec);
    }

    protected function getOneToOne($target)
    {
        $key = $this->factoryContainerKey('oneToOne', $target);
        $this->output->writeln(sprintf('     - get <bg=black;fg=white> %s </>', $key));

        return $this->container->get($this->factoryContainerKey('oneToOne', $target));
    }

    protected function generateDoctrineObject($object, $classNames, $key, $value, $lastname)
    {
        $lastname = strtolower($lastname);
        $targetObject = $object->get($key);
        $targetEntity = get_class($targetObject);

        if ($targetObject instanceof CollectionInterface) {
            $associationMappingType = $targetObject->getAssociationMappingType();
        } else {
            $associationMappingType = 'oneToOne';
        }

        $targetClassNames = $this->processClassNames($targetObject, $targetEntity);
        $method = sprintf('generateAssociation%sSpec', ucfirst($associationMappingType));

        $classNames['origin'] = $targetClassNames;
        return [
            'associationMappingType' => $associationMappingType,
            'spec' => $this->{$method}($this->generateAssociationGenericSpec($targetClassNames), $lastname, $classNames, $key, $value),
        ];
    }

    protected function generateAssociationGenericSpec($targetClassNames)
    {
        return [
            'targetEntity' => $targetClassNames['to'],
            'cascade' => ['persist', 'remove'],
            'options' => [
            ],
        ];
    }

    protected function generateAssociationOneToOneSpec($genericSpec, $lastname, $classNames, $key, $value)
    {
        if ('oneToOneUnidirectional' === $value) {
            return $this->generateAssociationOneToOneUnidirectionalSpec($genericSpec, $lastname, $classNames, $key, $value);
        }

        return $this->generateAssociationOneToOneBidirectionalSpec($genericSpec, $lastname, $classNames, $key, $value);
    }

    protected function generateAssociationOneToOneUnidirectionalSpec($genericSpec, $lastname, $classNames, $key, $value)
    {
        return $genericSpec;
    }

    protected function generateAssociationOneToOneBidirectionalSpec($genericSpec, $lastname, $classNames, $key, $value)
    {
        // unset($genericSpec['cascade']);
        $spec = array_merge($genericSpec, [
            'mappedBy' => StringTool::camelCaseToSnakeCase(StringTool::normalizeToSingular($classNames['lastname'])),
        ]);
        $childSpec = [];
        $childSpec[$lastname] = [
            'targetEntity' => $classNames['to'],
            'inversedBy' => StringTool::camelCaseToSnakeCase(StringTool::normalizeToSingular($classNames['origin']['lastname'])),
            'joinColumn' => [
                'name' => sprintf('%s_id', StringTool::normalizeToSingular($lastname)),
                'referencedColumnName' => 'id',
            ],
        ];

        $this->setOneToOne($classNames['origin']['to'], $childSpec);

        return $spec;
    }

    protected function generateAssociationManyToManySpec($spec, $lastname, $classNames, $key, $value)
    {
        return array_merge($spec, [
            'cascade' => ['persist'],
            'joinTable' => [
                'name' => sprintf('cs_pivot_%s_to_%s', $lastname, StringTool::normalizeToPlural($key)),
                'joinColumns' => [
                    sprintf('%s_id', StringTool::normalizeToSingular($key)) => [
                        'referencedColumnName' => 'id',
                    ],
                ],
                'inverseJoinColumns' => [
                    sprintf('%s_id', StringTool::normalizeToSingular($key)) => [
                        'referencedColumnName' => 'id',
                        'unique' => true,
                    ],
                ],
            ],
        ]);
    }

    protected function generateAssociationOneToManySpec($spec, $lastname, $classNames, $key, $value)
    {
        $spec = array_merge($spec, [
            'mappedBy' => StringTool::normalizeToSingular($lastname),
        ]);

        //add Many To one
        $childSpec = [];
        $childSpec[$lastname] = [
            'targetEntity' => $classNames['to'],
            'inversedBy' => StringTool::normalizeToPlural($key),
            'joinColumn' => [
                'name' => sprintf('%s_id', StringTool::normalizeToSingular($lastname)),
                'referencedColumnName' => 'id',
            ],
        ];

        $this->setManyToOne($spec['targetEntity'], $childSpec);

        return $spec;
    }
}
