<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\MarkdownMessage;
use WechatWorkPushBundle\Repository\MarkdownMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<MarkdownMessage>
 * @internal
 */
#[CoversClass(MarkdownMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class MarkdownMessageRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): object
    {
        $entity = new MarkdownMessage();
        $entity->setContent('# Test Markdown Content ' . uniqid() . "\n\nThis is a test markdown message.");

        $entity->setAgent($this->createTestAgent());

        return $entity;
    }

    protected function getRepository(): MarkdownMessageRepository
    {
        $repository = self::getEntityManager()->getRepository(MarkdownMessage::class);
        self::assertInstanceOf(MarkdownMessageRepository::class, $repository);

        return $repository;
    }

    protected function onSetUp(): void
    {
        // Repository test setup is handled by parent class
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

    private function createValidEntity(): MarkdownMessage
    {
        $entity = new MarkdownMessage();
        $entity->setContent('# Test Markdown Content ' . uniqid() . "\n\nThis is a test markdown message.");

        $entity->setAgent($this->createTestAgent());

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
