<?php
/**
 * Created by PhpStorm.
 * User: merlin
 * Date: 01/10/15
 * Time: 18:40
 */

namespace Eoko\ODM\DocumentManager\Test;

use Eoko\ODM\DocumentManager\Metadata\ClassMetadata;
use Eoko\ODM\DocumentManager\Repository\DocumentManager;
use Eoko\ODM\DocumentManager\Repository\DocumentRepository;
use Eoko\ODM\DocumentManager\Test\Entity\UserEntity;

class FactoryTest extends BaseTestCase
{

    public function testTestFactory()
    {
        $dm = $this->getDocumentManager();
        $this->assertInstanceOf(DocumentManager::class, $dm);
    }

    public function testMetadata()
    {
        $metadata = $this->getDocumentManager()->getClassMetadata(UserEntity::class);
        $this->assertInstanceOf(ClassMetadata::class, $metadata);
    }

    public function testRepository()
    {
        $this->assertInstanceOf(DocumentRepository::class, $this->getRepository());
    }
}
