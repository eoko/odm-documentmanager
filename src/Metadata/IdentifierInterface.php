<?php
/**
 * Created by PhpStorm.
 * User: merlin
 * Date: 28/08/15
 * Time: 14:08
 */

namespace Eoko\ODM\DocumentManager\Metadata;

interface IdentifierInterface
{

    /**
     * Must return array with "fieldType" => "FieldValue"
     * @return array
     */
    public function getIdentifier();
}
