<?php

namespace Eoko\ODM\DocumentManager\Test;

use Aws\Sdk;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\Criteria;
use Eoko\ODM\DocumentManager\Metadata\AnnotationDriver;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;
use Eoko\ODM\DocumentManager\Driver\DynamoDBDriver;
use Eoko\ODM\DocumentManager\Test\Entity\UserEntity;
use Eoko\ODM\DocumentManager\Repository\DocumentManager;


class Test extends \PHPUnit_Framework_TestCase
{


    public function testDummy()
    {
        //
//        $annotationReader = new AnnotationReader();
////Get class annotation
//        $reflectionClass = new \ReflectionClass(get_class($item));
//
//        $classAnnotations = $annotationReader->getClassAnnotations($reflectionClass);
//
//        var_dump($classAnnotations);

        $sdk = new Sdk([
            'version' => 'latest',
            'region' => 'eu-west-1',
            'credentials' => [
                'key' => 'AKIAJWBXWTRENZ4ZT6QQ',
                'secret' => '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',
            ],
            'http' => [
                'connect_timeout' => 1,
            ],
        ]);

        $client = $sdk->createDynamoDb();


        $entity = new UserEntity();
        $entity->setUsername('test_55d0e150b75df');
        $entity->setCreatedAt('za');
        $annotationDriver = new AnnotationDriver(new AnnotationReader(), ['Eoko\ODM\DocumentManager' => __DIR__ . '/../src']);



        $classMetadata = $annotationDriver->getClassMetadata($entity);
        $fieldsMetadata = $annotationDriver->getFieldsMetadata($entity);


        $hydrator = new ClassMethods();
        $dStragegie = new DateTimeFormatterStrategy();


        $strategies = [
            'Eoko\ODM\DocumentManager\Annotation\DateTime' => $dStragegie
        ];

        $dynamoDBDriver = new DynamoDBDriver($client);
        $em = new DocumentManager($annotationDriver, $dynamoDBDriver, $hydrator, $strategies);

        $dynamoDBDriver->findBy(new Criteria(), 25, $em->getClassMetadata('Eoko\ODM\DocumentManager\Test\Entity\UserEntity'));

      //  die(__CLASS__);


        $metadata = $em->getClassMetadata('Eoko\ODM\DocumentManager\Test\Entity\UserEntity');
        $repository = $em->getRepository('Eoko\ODM\DocumentManager\Test\Entity\UserEntity');

        $entity->setUsername(uniqid('test_'));
        $entity->setEmail('romain.dary@eoko.fr');
        $entity->setCreatedAt(new \DateTime());
        $add = $repository->add($entity);
        $find = $repository->find($entity);
        $findall = $repository->findAll();
        $delete = $repository->delete($find);

        $entity->setEmail('ooooooooooo');
        $entity->setCreatedAt(null);
        $entity->setEmailVerified(true);
        $update = $repository->update($entity);
        var_dump($add, $find, $findall, $delete, $update);
        die;
    }
}
