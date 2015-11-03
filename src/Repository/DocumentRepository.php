<?php

namespace Eoko\ODM\DocumentManager\Repository;

use Doctrine\Common\Collections\Criteria;
use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;
use Zend\Filter\ToNull;
use Zend\Stdlib\Hydrator\FilterEnabledInterface;
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

        if ($hydrator instanceof FilterEnabledInterface) {
            $hydrator->addFilter('null', new ToNull(\Zend\Filter\ToNull::TYPE_ALL));
        }

        $this->_hydrator = $hydrator;
    }

    /**
     * Finds an entity by its primary key / identifier that can be string, array or entity.
     *
     * If entity have multiple identifier, it's unsafe to use the string identifier.
     *
     * @param string|array|object $identifiers
     * @return object
     * @throws \Exception
     */
    public function find($identifiers)
    {
        if (is_object($identifiers)) {
            $identifiers = $this->_hydrator->extract($identifiers);
        }

        if (is_string($identifiers)) {
            $field = $this->_class->getIdentifierFieldNames();
            $identifiers = [$field[0] => $identifiers];
        }

        $classIdentifiers = $this->_class->getIdentifier();

        $identifiers = array_intersect_key($identifiers, $classIdentifiers);

        $result = $this->_em->getConnexionDriver()->getItem($identifiers, $this->_class);
        $className = $this->_class->getName();
        return is_array($result) ? $this->_hydrator->hydrate($result, new $className()) : false;
    }


    /**
     * Delete an entity by its primary key / identifier.
     *
     * @param array|object $identifiers
     * @return bool
     * @throws \Exception
     */
    public function delete($identifiers)
    {
        if (is_object($identifiers)) {
            $identifiers = $this->_hydrator->extract($identifiers);
        }

        if (is_string($identifiers)) {
            $field = $this->_class->getIdentifierFieldNames();
            $identifiers = [$field[0] => $identifiers];
        }

        $classIdentifiers = $this->_class->getIdentifier();
        $identifiers = array_intersect_key($identifiers, $classIdentifiers);

        $result = $this->_em->getConnexionDriver()->deleteItem($identifiers, $this->_class);
        return $result ? true : false;
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
        $values = is_object($entity) ? $this->_hydrator->extract($entity) : $entity;
        $values = array_intersect_key($values, $this->_class->getFields());

        $values = $this->_em->getConnexionDriver()->addItem($values, $this->_class);
        return $this->_hydrator->hydrate($values, new $this->_entityName());
    }

    /**
     * @param object|array $values
     * @return bool
     * @throws \Exception
     */
    public function update($values)
    {
        if (is_object($values)) {
            $values = $this->_hydrator->extract($values);
        }

        $values = array_intersect_key($values, $this->_class->getFields());

        $identifier = $this->_class->getIdentifier();
        $identifiers = array_intersect_key($values, $identifier);

        $result = $this->_em->getConnexionDriver()->updateItem($identifiers, $values, $this->_class);

        if (!$result) {
            throw new \Exception('Something wrong.');
        }

        return $this->_hydrator->hydrate($values, new $this->_entityName());
    }

    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        $hydrator = $this->_hydrator;
        $className = $this->_class->getName();
        $result = $this->_em->getConnexionDriver()->findAll($this->_class);
        return array_map(function ($item) use ($hydrator, $className) {
            return $hydrator->hydrate($item, new $className());
        }, $result);
    }

    /**
     * @return null
     */
    public function createTable()
    {
        return $this->_em->getConnexionDriver()->createTable($this->_class);
    }

    /**
     * @return null
     */
    public function deleteTable()
    {
        return $this->_em->getConnexionDriver()->deleteTable($this->_class);
    }

    /**
     * @return false|string False if an error occured
     */
    public function getStatusTable()
    {
        return $this->_em->getConnexionDriver()->getTableStatus($this->_class);
    }

    /**
     * @return bool
     */
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
