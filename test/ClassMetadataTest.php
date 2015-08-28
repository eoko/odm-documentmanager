<?php

namespace Eoko\ODM\DocumentManager\Test;

use Doctrine\Common\Annotations\AnnotationReader;
use Eoko\ODM\DocumentManager\Metadata\DocumentInterface;
use Eoko\ODM\DocumentManager\Metadata\DriverInterface;
use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;
use Eoko\ODM\DocumentManager\Test\Entity\UserEntity;
use Eoko\ODM\Metadata\Annotation\AnnotationDriver;
use Zend\Cache\Storage\Adapter\Memory;

class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{

    public function testName()
    {
        $this->assertEquals($this->getClassMetadata()->getName(), UserEntity::class);
    }

    public function testGetClass()
    {
        $classMetadata = $this->getClassMetadata()->getClass();
        $this->assertInternalType('array', $classMetadata);
    }

    public function testGetDocument()
    {
        $classMetadata = $this->getClassMetadata()->getDocument();
        $this->assertInstanceOf(DocumentInterface::class, $classMetadata);
    }

    public function testGetFieldNames()
    {
        $fieldNames = $this->getClassMetadata()->getFieldNames();
        $this->assertInternalType('array', $fieldNames);
    }

    public function testGetFields()
    {
        $fields = $this->getClassMetadata()->getFields();
        $this->assertInternalType('array', $fields);
    }

    public function testGetIdentifiers()
    {
        $identifiers = $this->getClassMetadata()->getIdentifier();
        $this->assertInternalType('array', $identifiers);
    }

    public function testGetIdentifiersName()
    {
        $identifiersName = $this->getClassMetadata()->getIdentifierFieldNames();
        $this->assertInternalType('array', $identifiersName);
    }

    public function testGetTypeOfField()
    {
        $identifiersName = $this->getClassMetadata()->getTypeOfField('username');
        $this->assertInternalType('string', $identifiersName);
    }

    public function testHasField()
    {
        $has = $this->getClassMetadata()->hasField('email');
        $this->assertTrue($has);
        $hasNot = $this->getClassMetadata()->hasField('hello');
        $this->assertFalse($hasNot);
    }

    public function testIsIdentifier()
    {
        $is = $this->getClassMetadata()->isIdentifier('username');
        $this->assertTrue($is);
        $isNot = $this->getClassMetadata()->isIdentifier('email');
        $this->assertFalse($isNot);
    }

    private function getAnnotationDriver()
    {
        $cache = new Memory();
        return new AnnotationDriver(new AnnotationReader(), ['Eoko\ODM\DocumentManager' => __DIR__ . '/../src/'], $cache);
    }

    private function getClassMetadata()
    {
        return new ClassMetadata(UserEntity::class, $this->getAnnotationDriver());
    }
}
