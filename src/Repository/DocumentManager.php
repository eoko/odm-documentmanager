<?php

namespace Eoko\ODM\DocumentManager\Repository;

use Eoko\ODM\DocumentManager\Metadata\FieldInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Eoko\ODM\DocumentManager\Driver\DynamoDBDriver;
use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;
use Eoko\ODM\DocumentManager\Metadata\DriverInterface;

class DocumentManager
{

    /** @var array  */
    protected $entities = [];

    /** @var array  */
    protected $removedEntities = [];

    /** @var array  */
    protected $repositories = [];

    /** @var  ClassMetadata */
    protected $classMetadata;

    /** @var  DriverInterface */
    protected $metadataDriver;

    /** @var  \Eoko\ODM\DocumentManager\Driver\DriverInterface */
    protected $connexionDriver;

    /** @var  HydratorInterface */
    protected $hydrator;

    /** @var  StorageInterface */
    protected $cache;

    /** @var  [] */
    protected $strategies;

    public function __construct($metadataDriver, $connexionDriver, $hydrator, $strategies, $cache)
    {
        $this->metadataDriver = $metadataDriver;
        $this->connexionDriver = $connexionDriver;
        $this->hydrator = $hydrator;
        $this->strategies = $strategies;
        $this->cache = $cache;
    }

    /**
     * @return \Eoko\ODM\Driver\DynamoDB\DynamoDBDriver
     */
    public function getConnexionDriver()
    {
        return $this->connexionDriver;
    }

    /**
     * @return HydratorInterface
     */
    protected function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * Gets the repository for a class.
     *
     * @param string $className
     * @return DocumentRepository
     */
    public function getRepository($className)
    {
        if (!isset($this->repositories[$className])) {
            $classMetada = $this->getClassMetadata($className);
            $hydrator = $this->getHydrator();
            foreach ($classMetada->getFields() as $key => $fields) {
                if (!is_null($fields)) {
                    foreach ($fields as $field) {
                        if ($field instanceof FieldInterface && isset($this->strategies[get_class($field)])) {
                            $hydrator->addStrategy($field->getName(), $this->strategies[get_class($field)]);
                        }
                    }
                }
            }

            $hydrator = $this->getHydrator();

            $this->repositories[$className] = new DocumentRepository($this, $classMetada, $hydrator);
        }
        return $this->repositories[$className];
    }

    /**
     * Returns the ClassMetadata descriptor for a class.
     *
     * The class name must be the fully-qualified class name without a leading backslash
     * (as it is returned by get_class($obj)).
     *
     * @param string $className
     * @return ClassMetadata
     */
    public function getClassMetadata($className)
    {
        if (!$this->cache->hasItem('Eoko\ODM\DocumentManager\Cache\\' . $className)) {
            $classMetadata = new ClassMetadata($className, $this->metadataDriver);
            $this->cache->setItem('Eoko\ODM\DocumentManager\Cache\\' . $className, $classMetadata);
        } else {
            $classMetadata = $this->cache->getItem('Eoko\ODM\DocumentManager\Cache\\' . $className);
        }
        return $classMetadata;
    }
}
