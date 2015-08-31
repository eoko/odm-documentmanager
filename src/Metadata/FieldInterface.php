<?php

namespace Eoko\ODM\DocumentManager\Metadata;

interface FieldInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();
}
