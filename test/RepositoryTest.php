<?php
/**
 * Created by PhpStorm.
 * User: merlin
 * Date: 01/10/15
 * Time: 18:40
 */

namespace Eoko\ODM\DocumentManager\Test;


use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
use Eoko\ODM\DocumentManager\Factory\DocumentManagerFactory;
use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;
use Eoko\ODM\DocumentManager\Repository\DocumentManager;
use Eoko\ODM\DocumentManager\Repository\DocumentRepository;
use Eoko\ODM\DocumentManager\Test\Entity\UserEntity;
use Zend\Config\Config;

class RepositoryTest extends BaseTestCase
{

    public function testCreateTable()
    {
        $this->getRepository()->createTable();

        $retry = 0;
        while (!$this->getRepository()->getStatusTable() !== 'ACTIVE' && $retry < 5) {
            sleep($retry++ * $retry);
        }
    }

    /**
     * @depends testCreateTable
     */
    public function testAdd() {
        $entity = new UserEntity();
        $entity->setUsername('john');

        $result = $this->getRepository()->add($entity);
        $expected = $this->getRepository()->find(['username' => 'john']);

        $this->assertEquals($result, $expected);
        $expected = $this->getRepository()->find($entity);
        $this->assertEquals($result, $expected);


        $entity = new UserEntity();
        $entity->setUsername('pierre');
        $result = $this->getRepository()->add($entity);
        $expected = $this->getRepository()->find(['username' => 'pierre']);

        $this->assertEquals($result, $expected);
    }
    

    /**
     * @depends testAdd
     */
    public function testFind() {
        $criteria = new Criteria();
        $expression = new ExpressionBuilder();

        $exp = $expression->eq('username', 'john');
        $criteria->where($exp);
        $this->assertEquals(1, count($this->getRepository()->findBy($criteria)));
        $this->assertEquals(2, count($this->getRepository()->findAll()));
    }

    /**
     * @depends testFind
     */
    public function testDelete() {
        $result = $this->getRepository()->delete(['username' => 'pierre']);
        $this->assertTrue($result);
    }

    /**
     * @depends testDelete
     */
    public function testDeleteTable()
    {
        $this->getRepository()->deleteTable();

        $retry = 0;
        // Ensure that the Table is really deleted
        while (!$this->getRepository()->isTable() && $retry < 5) {
            sleep($retry++ * $retry);
        }
    }


}
