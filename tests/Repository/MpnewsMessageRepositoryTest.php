<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\MpnewsMessage;
use WechatWorkPushBundle\Repository\MpnewsMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<MpnewsMessage>
 * @internal
 */
#[CoversClass(MpnewsMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class MpnewsMessageRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): object
    {
        $entity = new MpnewsMessage();
        $entity->setTitle('Test MPNews Title ' . uniqid());
        $entity->setContent('Test content ' . uniqid());

        // 创建 Corp
        $corp = new Corp();
        $corp->setName('Test Corp ' . uniqid());
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setCorpSecret('test_corp_secret_' . uniqid());
        self::getEntityManager()->persist($corp);

        // 创建一个测试 agent
        $agent = new Agent();
        $agent->setAgentId('test_agent_' . uniqid());
        $agent->setName('Test Agent');
        $agent->setSecret('test_secret_' . uniqid());
        $agent->setCorp($corp);
        self::getEntityManager()->persist($agent);
        self::getEntityManager()->flush();
        $entity->setAgent($agent);

        return $entity;
    }

    protected function getRepository(): MpnewsMessageRepository
    {
        $repository = self::getEntityManager()->getRepository(MpnewsMessage::class);
        self::assertInstanceOf(MpnewsMessageRepository::class, $repository);

        return $repository;
    }

    protected function onSetUp(): void
    {
        // Repository test setup is handled by parent class
    }

    private function createValidEntity(): MpnewsMessage
    {
        $entity = new MpnewsMessage();
        $entity->setTitle('Test Mpnews Title ' . uniqid());
        $entity->setContent('This is test mpnews content');
        $entity->setThumbMediaUrl('https://example.com/thumb.jpg');
        $entity->setThumbMediaId('test_thumb_media_id_' . uniqid());

        // 创建 Corp
        $corp = new Corp();
        $corp->setName('Test Corp ' . uniqid());
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setCorpSecret('test_corp_secret_' . uniqid());
        self::getEntityManager()->persist($corp);

        // 创建一个测试 agent
        $agent = new Agent();
        $agent->setAgentId('test_agent_' . uniqid());
        $agent->setName('Test Agent');
        $agent->setSecret('test_secret_' . uniqid());
        $agent->setCorp($corp);
        self::getEntityManager()->persist($agent);
        self::getEntityManager()->flush();
        $entity->setAgent($agent);

        return $entity;
    }

    public function testSaveMethodPersistsEntity(): void
    {
        $entity = $this->createValidEntity();

        $this->getRepository()->save($entity);

        $this->assertNotNull($entity->getId());
    }

    public function testRemoveMethodRemovesEntity(): void
    {
        $entity = $this->createValidEntity();
        $this->getRepository()->save($entity);
        $id = $entity->getId();

        $this->getRepository()->remove($entity);

        $this->assertNull($this->getRepository()->find($id));
    }
}
