<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\ButtonTemplateMessage;
use WechatWorkPushBundle\Repository\ButtonTemplateMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<ButtonTemplateMessage>
 * @internal
 */
#[CoversClass(ButtonTemplateMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class ButtonTemplateMessageRepositoryTest extends AbstractRepositoryTestCase
{
    private ButtonTemplateMessageRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ButtonTemplateMessageRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new ButtonTemplateMessage();
        $entity->setTitle('Test Title ' . uniqid());
        $entity->setDescription('Test Description');
        $entity->setUrl('https://example.com/' . uniqid());
        $entity->setButtonText('Click Me');

        // 创建 Corp
        $corp = new Corp();
        $corp->setName('Test Corp ' . uniqid());
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setCorpSecret('test_corp_secret_' . uniqid());
        self::getEntityManager()->persist($corp);

        // 创建并持久化测试 agent
        $agent = new Agent();
        $agent->setAgentId('test_agent_' . uniqid());
        $agent->setName('Test Agent ' . uniqid());
        $agent->setSecret('test_secret_' . uniqid());
        $agent->setCorp($corp);
        self::getEntityManager()->persist($agent);
        self::getEntityManager()->flush();

        $entity->setAgent($agent);

        return $entity;
    }

    protected function getRepository(): ButtonTemplateMessageRepository
    {
        return $this->repository;
    }

    private function createTestEntity(): ButtonTemplateMessage
    {
        $entity = new ButtonTemplateMessage();
        $entity->setTitle('Test Title ' . uniqid());
        $entity->setDescription('Test Description');
        $entity->setUrl('https://example.com/' . uniqid());
        $entity->setButtonText('Click Me');

        // 创建 Corp
        $corp = new Corp();
        $corp->setName('Test Corp ' . uniqid());
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setCorpSecret('test_corp_secret_' . uniqid());
        self::getEntityManager()->persist($corp);

        // 创建并持久化测试 agent
        $agent = new Agent();
        $agent->setAgentId('test_agent_' . uniqid());
        $agent->setName('Test Agent ' . uniqid());
        $agent->setSecret('test_secret_' . uniqid());
        $agent->setCorp($corp);
        self::getEntityManager()->persist($agent);
        self::getEntityManager()->flush();

        $entity->setAgent($agent);

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

    /**
     * 测试Repository的查找功能
     */
    public function testFindByWithValidCriteria(): void
    {
        $entity = $this->createTestEntity();
        $this->repository->save($entity);

        // 测试根据条件查找
        $results = $this->repository->findBy(['title' => $entity->getTitle()]);

        $this->assertCount(1, $results);
        $this->assertInstanceOf(ButtonTemplateMessage::class, $results[0]);
        $this->assertSame($entity->getTitle(), $results[0]->getTitle());
    }

    /**
     * 测试Repository的查找功能 - 空结果
     */
    public function testFindByWithNonExistentCriteria(): void
    {
        $results = $this->repository->findBy(['title' => 'non-existent-title-' . uniqid()]);

        $this->assertCount(0, $results);
    }
}
