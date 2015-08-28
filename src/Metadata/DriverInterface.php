<?php

namespace Eoko\ODM\DocumentManager\Metadata;

interface DriverInterface
{
    public function __construct($options);

    public function getFieldsMetadata($entity);

    public function getClassMetadata($classname);
}
