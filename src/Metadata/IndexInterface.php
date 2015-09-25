<?php

namespace Eoko\ODM\DocumentManager\Metadata;

interface IndexInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * Must return array with fields
     * @return array
     */
    public function getFields();
}
