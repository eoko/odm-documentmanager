<?php

namespace Eoko\ODM\DocumentManager\Metadata;

interface DriverInterface
{

    public function getFieldsMetadata($entity);

    public function getClassMetadata($classname);
}
