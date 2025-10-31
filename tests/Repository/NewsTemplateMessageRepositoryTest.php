<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\NewsTemplateMessage;
use WechatWorkPushBundle\Repository\NewsTemplateMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<NewsTemplateMessage>
 * @internal
 */
#[CoversClass(NewsTemplateMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class NewsTemplateMessageRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): object
    {
        $uniqueId = uniqid();
        $entity = new NewsTemplateMessage();

        // 设置必填的 title 和 description 字段
        $entity->setTitle('Test News Template Title ' . $uniqueId);
        $entity->setDescription('Test news template description ' . $uniqueId);

        $entity->setUrl('https://example.com/test/' . $uniqueId);
        $entity->setImageUrl('https://example.com/image/' . $uniqueId . '.jpg');

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

    protected function getRepository(): NewsTemplateMessageRepository
    {
        $repository = self::getEntityManager()->getRepository(NewsTemplateMessage::class);
        self::assertInstanceOf(NewsTemplateMessageRepository::class, $repository);

        return $repository;
    }

    protected function onSetUp(): void
    {
        // Repository test setup is handled by parent class
    }

    private function createValidEntity(): NewsTemplateMessage
    {
        $entity = new NewsTemplateMessage();
        $entity->setTitle('Test News Template Title ' . uniqid());
        $entity->setDescription('Test News Template Description');
        $entity->setUrl('https://example.com/news-template');
        $entity->setImageUrl('https://example.com/image.jpg');
        $entity->setBtnText('Read More');

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
