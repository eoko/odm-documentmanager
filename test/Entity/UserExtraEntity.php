<?php

namespace Eoko\ODM\DocumentManager\Test\Entity;

use Eoko\ODM\Metadata\Annotation\Document;
use Eoko\ODM\Metadata\Annotation\ParentClass;
use Zend\Crypt\Password\Bcrypt;

/**
 * @Document(table="users_extra", provision={"ReadCapacityUnits" : 1, "WriteCapacityUnits" : 1})
 * @ParentClass
 */
class UserExtraEntity extends UserEntity
{

    protected $dateTime;
    protected $crypt;
    public $pcrypt;

    /**
     * UserExtraEntity constructor.
     * @param $dateTime
     */
    public function __construct()
    {
        $this->dateTime = new \DateTime('2014-12-12');
    }


    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return mixed
     */
    public function getCrypt()
    {
        return new Bcrypt();
    }

    /**
     * @param mixed $crypt
     */
    public function setCrypt($crypt)
    {
        $this->crypt = $crypt;
    }
}
