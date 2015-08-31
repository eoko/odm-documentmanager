<?php

namespace Eoko\ODM\DocumentManager\Test;

use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;
use Eoko\ODM\DocumentManager\Metadata\DocumentInterface;
use Eoko\ODM\DocumentManager\Metadata\FieldInterface;
use Eoko\ODM\DocumentManager\Metadata\IdentifierInterface;
use Eoko\ODM\DocumentManager\Test\Entity\UserEntity;
use Eoko\ODM\Metadata\Annotation\AnnotationDriver;

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

    private function getClassMetadata()
    {
        $usernameMetadata = \Mockery::mock(FieldInterface::class);
        $usernameMetadata->shouldReceive('getType')->andReturn('string');
        $usernameMetadata->shouldReceive('getValue')->andReturn('john');

        $created_atMetadata = \Mockery::mock(FieldInterface::class);
        $created_atMetadata->shouldReceive('getType')->andReturn('string');
        $created_atMetadata->shouldReceive('getValue')->andReturn('john');

        $emailMetadata = \Mockery::mock(FieldInterface::class);
        $emailMetadata->shouldReceive('getType')->andReturn('string');
        $emailMetadata->shouldReceive('getValue')->andReturn('john');

        $email_verifiedMetadata = \Mockery::mock(FieldInterface::class);
        $email_verifiedMetadata->shouldReceive('getType')->andReturn('string');
        $email_verifiedMetadata->shouldReceive('getValue')->andReturn('john');

        $documentMetadata = \Mockery::mock(DocumentInterface::class);
        $documentMetadata->shouldReceive('getTable')->andReturn('table');

        $identifierMetadata = \Mockery::mock(IdentifierInterface::class);
        $identifierMetadata->shouldReceive('getIdentifier')->andReturn(['username' => '12']);

        $classMetadata = [
            'document' => $documentMetadata,
            'identifiers' => $identifierMetadata
        ];

        $fieldMetadata = [
            'username' => [ 'meta1' => $usernameMetadata ],
            'created_at' => ['meta1' => $created_atMetadata],
            'email' => ['meta1' => $emailMetadata],
            'email_verified' => ['meta1' => $email_verifiedMetadata],
        ];
        $annotationDriver = \Mockery::mock(AnnotationDriver::class);
        $annotationDriver->shouldReceive('getClassMetadata')->andReturn($classMetadata);
        $annotationDriver->shouldReceive('getFieldsMetadata')->andReturn($fieldMetadata);

        return new ClassMetadata(UserEntity::class, $annotationDriver);
    }
}
