<?php

namespace Eoko\ODM\DocumentManager\Repository;

use Doctrine\Common\Collections\Criteria;
use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;
use Zend\Stdlib\Hydrator\HydratorInterface;

class DocumentRepository
{
    /**
     * @var string
     */
    protected $_entityName;

    /**
     * @var DocumentManager
     */
    protected $_em;

    /**
     * @var ClassMetadata
     */
    protected $_class;

    /** @var  HydratorInterface */
    protected $_hydrator;

    /**
     * Initializes a new <tt>DocumentRepository</tt>.
     *
     * @param DocumentManager $entityManager The DocumentManager to use.
     * @param ClassMetadata $class The class descriptor.
     * @param HydratorInterface $hydrator
     */
    public function __construct($entityManager, ClassMetadata $class, HydratorInterface $hydrator)
    {
        $this->_entityName = $class->getName();
        $this->_em = $entityManager;
        $this->_class = $class;
        $this->_hydrator = $hydrator;
    }

    /**
     * Finds an entity by its primary key / identifier.
     *
     * @param array|object $keysOrEntity
     * @return object
     * @throws \Exception
     */
    public function find($keysOrEntity)
    {
        if(is_object($keysOrEntity)) {
            $keysOrEntity = $this->_hydrator->extract($keysOrEntity);
        }
        $result = $this->_em->getConnexionDriver()->getItem($keysOrEntity, $this->_class);
        $className = $this->_class->getName();
        return is_array($result) ? $this->_hydrator->hydrate($result, new $className()) : false;
    }


    /**
     * Delete an entity by its primary key / identifier.
     *
     * @param array|object $keysOrEntity
     * @return bool
     * @throws \Exception
     */
    public function delete($keysOrEntity)
    {
        if(is_object($keysOrEntity)) {
            $keysOrEntity = $this->_hydrator->extract($keysOrEntity);
        }
        $result = $this->_em->getConnexionDriver()->deleteItem($keysOrEntity, $this->_class);
        return $result ? true : false;
    }

    function example() {
        $repository = $this->_em->getRepository('MonEntity');
        $repository->add(array('ke'));
    }

    /**
     * Add an entity. The newly created entity is return.
     *
     * @param $entity
     * @return object
     * @throws \Exception
     */
    public function add($entity)
    {
        $values = $this->_hydrator->extract($entity);
        $values = $this->_em->getConnexionDriver()->addItem($values, $this->_class);
        return $this->_hydrator->hydrate($values, new $this->_entityName());
    }

    /**
     * @param $entity
     * @return bool
     */
    public function update($array)
    {
        $values = array_filter($this->_hydrator->extract($entity), function ($value) {
            return !empty($value) || $value === 0;
        });

        $result = $this->_em->getConnexionDriver()->updateItem($values, $this->_class);
        return $result ? true : false;
    }

    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        $hydrator = $this->_hydrator;
        $classname = $this->_class->getName();
        $result = $this->_em->getConnexionDriver()->findAll($this->_class);
        return array_map(function ($item) use ($hydrator, $classname) {
            return $hydrator->hydrate($item, new $classname());
        }, $result);
    }

    public function createTable()
    {
        return $this->_em->getConnexionDriver()->createTable($this->_class);
    }

    public function deleteTable()
    {
        return $this->_em->getConnexionDriver()->deleteTable($this->_class);
    }

    public function getStatusTable()
    {
        return $this->_em->getConnexionDriver()->getTableStatus($this->_class);
    }

    public function isTable()
    {
        return $this->_em->getConnexionDriver()->isTable($this->_class);
    }

    /**
     * Finds entities by a set of criteria.
     *
     * @param Criteria $criteria
     * @return array The objects.
     *
     */
    public function findBy(Criteria $criteria)
    {
        $hydrator = $this->_hydrator;
        $classname = $this->_class->getName();
        $result = $this->_em->getConnexionDriver()->findBy($criteria, $this->_class);
        return array_map(function ($item) use ($hydrator, $classname) {
            return $hydrator->hydrate($item, new $classname());
        }, $result);
    }
}
