<?php

namespace Eoko\ODM\DocumentManager\Test;

use Eoko\ODM\DocumentManager\Factory\DocumentManagerFactory;
use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;
use Eoko\ODM\DocumentManager\Repository\DocumentManager;
use Eoko\ODM\DocumentManager\Test\Entity\UserEntity;
use Eoko\ODM\Driver\DynamoDB\DynamoDBDriver;
use Eoko\ODM\Driver\DynamoDB\DynamoDBDriverFactory;
use Zend\Config\Config;
use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;

/**
 * Class BaseTestCase
 * @package Eoko\ODM\Driver\DynamoDB\Test
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{

    public static function getClassMetadata($classname)
    {
        return new ClassMetadata($classname, Bootstrap::getServiceManager()->get('Eoko\\ODM\\Metadata\\Annotation'));
    }

    /**
     * @return DynamoDBDriver
     */
    public static function getDriver()
    {
        /** @var  $driver */
        return Bootstrap::getServiceManager()->get('Eoko\\ODM\\Driver\\DynamoDB');
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager() {

        $factory = new DocumentManagerFactory();

        $sm = Bootstrap::getServiceManager();
        $sm->setAllowOverride(true);
        $config = $sm->get('Config');
        $c =  new Config([
            'eoko' => [
                'odm' => [
                    'hydrator' => [
                        'class' => 'Zend\Stdlib\Hydrator\ClassMethods',
                        'strategies' => [
                            'Eoko\ODM\Metadata\Annotation\DateTime' => new DateTimeFormatterStrategy(),
                        ],
                    ],
                ],
            ],
        ]);
        $c->merge(new Config($config));
        $sm->setService('Config', $c->toArray());
        return $factory->createService($sm);
    }

    public function getRepository() {
        return $this->getDocumentManager()->getRepository(UserEntity::class);
    }
}
