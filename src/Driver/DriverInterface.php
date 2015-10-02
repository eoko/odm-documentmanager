<?php

namespace Eoko\ODM\DocumentManager\Driver;

use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;

interface DriverInterface
{

    /**
     * @param array $values
     * @param ClassMetadata $classMetadata
     * @return null
     * @throws \Exception
     */
    public function addItem(array $values, ClassMetadata $classMetadata);

    /**
     * @param array $identifiers
     * @param ClassMetadata $classMetadata
     * @return array|null
     * @throws \Exception
     */
    public function getItem(array $identifiers, ClassMetadata $classMetadata);

    /**
     * @param ClassMetadata $classMetadata
     * @return array
     * @throws \Exception
     */
    public function findAll(ClassMetadata $classMetadata);

    /**
     * @param array $values
     * @param ClassMetadata $classMetadata
     * @return null
     * @throws \Exception
     */
    public function updateItem(array $identifiers, array $values, ClassMetadata $classMetadata);

    /**
     * @param array $values
     * @param ClassMetadata $classMetadata
     * @return null
     * @throws \Exception
     */
    public function deleteItem(array $identifiers, ClassMetadata $classMetadata);

    /**
     * @param ClassMetadata $classMetadata
     * @return null
     */
    public function createTable(ClassMetadata $classMetadata);

    /**
     * @param ClassMetadata $classMetadata
     * @return null
     */
    public function deleteTable(ClassMetadata $classMetadata);
}
