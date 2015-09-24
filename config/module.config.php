<?php
return [

    'service_manager' => [
        'factories' => [
            'Eoko\\ODM\\DocumentManager' => 'Eoko\\ODM\\DocumentManager\\Factory\\DocumentManagerFactory'
        ]
    ],
    'eoko' => [
        'odm' => [
            'hydrator' => [
                'class' => 'Zend\Stdlib\Hydrator\ClassMethods',
            ],
        ],
    ],

];
