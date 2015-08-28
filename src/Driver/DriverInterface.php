<?php

namespace Eoko\ODM\DocumentManager\Driver;

use Doctrine\Common\Collections\ArrayCollection;
use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;

interface DriverInterface
{
    public function __construct($options);

    /**
     * @param array $values
     * @param ClassMetadata $classMetadata
     * @return null
     * @throws \Exception
     */
    public function addItem(array $values, ClassMetadata $classMetadata);

    /**
     * @param array $values
     * @param ClassMetadata $classMetadata
     * @return array|null
     * @throws \Exception
     */
    public function getItem(array $values, ClassMetadata $classMetadata);

    /**
     * @param ClassMetadata $classMetadata
     * @return ArrayCollection
     * @throws \Exception
     */
    public function findAll(ClassMetadata $classMetadata);

    /**
     * @param array $values
     * @param ClassMetadata $classMetadata
     * @return null
     * @throws \Exception
     */
    public function updateItem(array $values, ClassMetadata $classMetadata);

    /**
     * @param array $values
     * @param ClassMetadata $classMetadata
     * @return null
     * @throws \Exception
     */
    public function deleteItem(array $values, ClassMetadata $classMetadata);
}
