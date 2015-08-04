<?php

/*
 * This file is part of gpupo/common-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For more information, see
 * <http://www.g1mr.com/common-sdk/>.
 */

namespace Gpupo\CommonSdk\Entity;

use Gpupo\CommonSdk\Entity\Schema\SchemaAbstract;

abstract class EntityAbstract extends SchemaAbstract
{
    protected $previous;

    /**
     * Utilizado em entidades que possuem chave primária diferente de [id].
     *
     * @type string
     */
    protected $primaryKey;

    /**
     * Toda entidade deve possuir um Id
     * mesmo que não possua o atributo Id em seu Schema.
     * Quando este for o caso, getId() será um alias padronizado
     * para acesso ao campo identificador da entidade.
     */
    public function getId()
    {
        if (empty($this->primaryKey)) {
            return $this->get('id');
        }

        return $this->get($this->primaryKey);
    }

    /**
     * Permite normalização de $data.
     */
    protected function beforeConstruct($data = null)
    {
        return $data;
    }

    /**
     * Permite ação após construção.
     */
    protected function setUp()
    {
    }

    /**
     * @param array|EntityInterface $data
     */
    public function __construct($data = null)
    {
        if (!$this instanceof EntityInterface) {
            throw new \Exception('EntityInterface deve ser implementada');
        }

        if ($data instanceof EntityInterface) {
            $data = $data->toArray();
        }

        $schema = $this->getSchema();

        if (!empty($schema)) {
            parent::__construct($this->initSchema($this->getSchema(), $this->beforeConstruct($data)));
        }

        $this->setUp();
    }

    public function setPrevious(EntityInterface $previous)
    {
        $this->previous = $previous;

        return $this;
    }

    public function getPrevious()
    {
        return $this->previous;
    }

    public function toArray()
    {
        if ($this->validate()) {
            $array = parent::toArray();

            foreach ($array as $key => $value) {
                if (empty($value) && $this->isOptional($key)) {
                    unset($array[$key]);
                }
            }

            return $array;
        }
    }
}
