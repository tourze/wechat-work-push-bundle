<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\Repository\TextMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<TextMessage>
 * @internal
 */
#[CoversClass(TextMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class TextMessageRepositoryTest extends AbstractRepositoryTestCase
{
    private TextMessageRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(TextMessageRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new TextMessage();
        $entity->setContent('Test message content ' . uniqid());

        $entity->setAgent($this->createTestAgent());

        return $entity;
    }

    protected function getRepository(): TextMessageRepository
    {
        return $this->repository;
    }

    private function createTestAgent(): Agent
    {
        // 创建 Corp
        $corp = new Corp();
        $corp->setName('Test Corp ' . uniqid());
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setCorpSecret('test_corp_secret_' . uniqid());
        self::getEntityManager()->persist($corp);

        // 创建 Agent
        $agent = new Agent();
        $agent->setAgentId('test_agent_' . uniqid());
        $agent->setName('Test Agent');
        $agent->setSecret('test_secret_' . uniqid());
        $agent->setCorp($corp);
        self::getEntityManager()->persist($agent);
        self::getEntityManager()->flush();

        return $agent;
    }

    private function createTestEntity(): TextMessage
    {
        $entity = new TextMessage();
        $entity->setContent('Test message content ' . uniqid());

        $entity->setAgent($this->createTestAgent());

        return $entity;
    }

    public function testSaveMethodPersistsEntity(): void
    {
        $entity = $this->createTestEntity();

        $this->repository->save($entity);

        $this->assertNotNull($entity->getId());
    }

    public function testRemoveMethodRemovesEntity(): void
    {
        $entity = $this->createTestEntity();

        $this->repository->save($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $this->assertNull($this->repository->find($id));
    }
}
