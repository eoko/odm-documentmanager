<?php

namespace Eoko\ODM\DocumentManager\Factory;

use Aws\Sdk as Aws;
use Doctrine\Common\Annotations\AnnotationReader;
use Eoko\ODM\DocumentManager\Repository\DocumentManager;
use Zend\Cache\Storage\Adapter\Memory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;
use Zend\Stdlib\Hydrator\StrategyEnabledInterface;

class DocumentManagerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     * @throws \Exception
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $baseConfig = $serviceLocator->get('Config');

        if (isset($baseConfig['eoko']['odm'])) {
            $base = $baseConfig['eoko']['odm'];

            $connexionDriver = $serviceLocator->get($base['driver']['name']);
            $cache = new Memory();
            $metadataDriver = $serviceLocator->get($base['metadata']['driver']);


            $hydratorClass = $base['hydrator']['class'];

            $hydrator = new $hydratorClass();
            $strategies = [];

            if ($hydrator instanceof StrategyEnabledInterface && isset($base['hydrator']['strategies'])) {
                foreach ($base['hydrator']['strategies'] as $name => $strategy) {
                    if (is_object($strategy)) {
                        // Do nothing we are good :D
                    } elseif (is_callable($strategy)) {
                        $strategy = new $strategy();
                    } elseif ($serviceLocator->has($strategy)) {
                        $strategy = $serviceLocator->get($strategy);
                    } else {
                        throw new \Exception('This is not a valid strategy. Must be obj, callable or service managed.');
                    }
                    $strategies[$name] = $strategy;
                }
            }

            return new DocumentManager($metadataDriver, $connexionDriver, $hydrator, $strategies, $cache);
        } else {
            throw new \Exception('Configuration Missing.');
        }


//
//        $aws = $serviceLocator->get(Aws::class);
//        $cache = new Memory();
//
//        $client = $aws->createDynamoDb();
//        $connexionDriver = new DynamoDBDriver($client);
//        $annotationDriver = new AnnotationDriver(new AnnotationReader(), ['Eoko\ODM\DocumentManager' => __DIR__ . '/../../../../src/']);
//        $hydrator = new ClassMethods();
//        $strategies = ['Eoko\ODM\DocumentManager\Annotation\DateTime' => new DateTimeFormatterStrategy()];
//
//        return new DocumentManager($annotationDriver, $connexionDriver, $hydrator, $cache);
    }
}
