<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\TextNoticeTemplateMessage;
use WechatWorkPushBundle\Repository\TextNoticeTemplateMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<TextNoticeTemplateMessage>
 * @internal
 */
#[CoversClass(TextNoticeTemplateMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class TextNoticeTemplateMessageRepositoryTest extends AbstractRepositoryTestCase
{
    private TextNoticeTemplateMessageRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(TextNoticeTemplateMessageRepository::class);
    }

    protected function createNewEntity(): object
    {
        $uniqueId = uniqid();
        $entity = new TextNoticeTemplateMessage();

        // 设置必填的 title 和 description 字段
        $entity->setTitle('Test Text Notice Template Title ' . $uniqueId);
        $entity->setDescription('Test text notice template description ' . $uniqueId);

        $entity->setUrl('https://example.com/test/' . $uniqueId);

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

    protected function getRepository(): TextNoticeTemplateMessageRepository
    {
        return $this->repository;
    }

    private function createTestEntity(): TextNoticeTemplateMessage
    {
        $uniqueId = uniqid();
        $entity = new TextNoticeTemplateMessage();
        $entity->setTitle('Test Template Title ' . $uniqueId);
        $entity->setDescription('Test template description ' . $uniqueId);
        $entity->setUrl('https://example.com/template/' . $uniqueId);
        $entity->setBtnText('View Details');

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
