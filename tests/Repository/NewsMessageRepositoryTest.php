<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\NewsMessage;
use WechatWorkPushBundle\Repository\NewsMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<NewsMessage>
 * @internal
 */
#[CoversClass(NewsMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class NewsMessageRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): object
    {
        $entity = new NewsMessage();
        $entity->setTitle('Test News Title ' . uniqid());
        $entity->setDescription('Test news description ' . uniqid());
        $entity->setUrl('https://example.com/test/' . uniqid());

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

    protected function getRepository(): NewsMessageRepository
    {
        $repository = self::getEntityManager()->getRepository(NewsMessage::class);
        self::assertInstanceOf(NewsMessageRepository::class, $repository);

        return $repository;
    }

    protected function onSetUp(): void
    {
        // Repository test setup is handled by parent class
    }

    private function createValidEntity(): NewsMessage
    {
        $entity = new NewsMessage();
        $entity->setTitle('Test News Title ' . uniqid());
        $entity->setDescription('Test News Description');
        $entity->setUrl('https://example.com/news');
        $entity->setPicUrl('https://example.com/pic.jpg');

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
