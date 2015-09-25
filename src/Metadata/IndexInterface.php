<?php

namespace Eoko\ODM\DocumentManager\Metadata;

interface IndexInterface
{
    /**
     * @return string
     */
    public function getIndexName();

    /**
     * Must return array with fields
     * @return array
     */
    public function getFields();
}
