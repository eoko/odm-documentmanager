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
use Eoko\ODM\DocumentManager\Test\Entity\UserExtraEntity;

class RepositoryExtraTest extends BaseTestCase
{

    public function testCreateTable()
    {
        $this->getRepository(UserExtraEntity::class)->createTable();

        $retry = 0;
        while (!$this->getRepository(UserExtraEntity::class)->getStatusTable() !== 'ACTIVE' && $retry < 5) {
            sleep($retry++ * $retry);
        }
    }

    public function testAdd()
    {
        $entity = new UserExtraEntity();
        $entity->setUsername('john');

        $result = $this->getRepository(UserExtraEntity::class)->add($entity);
        $expected = $this->getRepository(UserExtraEntity::class)->find(['username' => 'john']);

        $this->assertEquals($result, $expected);
        $expected = $this->getRepository(UserExtraEntity::class)->find($entity);
        $this->assertEquals($result, $expected);


        $entity = new UserExtraEntity();
        $entity->setUsername('pierre');
        $result = $this->getRepository(UserExtraEntity::class)->add($entity);
        $expected = $this->getRepository(UserExtraEntity::class)->find(['username' => 'pierre']);

        $this->assertEquals($result, $expected);
    }

    /**
     * @depends testAdd
     */
    public function testUpdate()
    {
        $entity = new UserExtraEntity();
        $entity->setUsername('john');
        $entity->setEmail('john@doe.com');

        $expected = $this->getRepository(UserExtraEntity::class)->update($entity);
        $this->assertTrue($expected == $entity);

        $result = $this->getRepository(UserExtraEntity::class)->find(['username' => 'john']);
        $this->assertTrue($result == $expected);

        $entity->setEmailVerified(true);
        $entity->setCreatedAt(new \DateTime());
        $result = $this->getRepository(UserExtraEntity::class)->update($entity);

        $this->assertTrue($result == $entity);
    }


    /**
     * @depends testUpdate
     */
    public function testFind()
    {
        $criteria = new Criteria();
        $expression = new ExpressionBuilder();

        $exp = $expression->eq('username', 'john');
        $criteria->where($exp);
        $this->assertEquals(1, count($this->getRepository(UserExtraEntity::class)->findBy($criteria)));
        $this->assertEquals(2, count($this->getRepository(UserExtraEntity::class)->findAll()));
    }

    /**
     * @depends testFind
     */
    public function testDelete()
    {
        $result = $this->getRepository(UserExtraEntity::class)->delete(['username' => 'pierre']);
        $this->assertTrue($result);
    }

    /**
     * @depends testDelete
     */
    public function testCreateWithExtraValues()
    {
        $values = ['username' => 'me', 'date' => new \DateTime()];
        $expected = $this->getRepository(UserExtraEntity::class)->add($values);
        $this->assertTrue($values != $this->getRepository(UserExtraEntity::class)->find('me'));
        $this->assertTrue($expected == $this->getRepository(UserExtraEntity::class)->find('me'));
    }

    /**
     * @depends testCreateWithExtraValues
     */
    public function testDeleteTable()
    {
        $this->getRepository(UserExtraEntity::class)->deleteTable();

        $retry = 0;
        // Ensure that the Table is really deleted
        while (!$this->getRepository(UserExtraEntity::class)->isTable() && $retry < 5) {
            sleep($retry++ * $retry);
        }
    }
}
