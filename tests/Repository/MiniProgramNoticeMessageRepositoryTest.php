<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\MiniProgramNoticeMessage;
use WechatWorkPushBundle\Repository\MiniProgramNoticeMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<MiniProgramNoticeMessage>
 * @internal
 */
#[CoversClass(MiniProgramNoticeMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class MiniProgramNoticeMessageRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): object
    {
        $entity = new MiniProgramNoticeMessage();
        $entity->setAppId('test_app_id_' . uniqid());
        $entity->setTitle('Test Title');

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

    protected function getRepository(): MiniProgramNoticeMessageRepository
    {
        $repository = self::getEntityManager()->getRepository(MiniProgramNoticeMessage::class);
        self::assertInstanceOf(MiniProgramNoticeMessageRepository::class, $repository);

        return $repository;
    }

    protected function onSetUp(): void
    {
        // Repository test setup is handled by parent class
    }

    private function createValidEntity(): MiniProgramNoticeMessage
    {
        $entity = new MiniProgramNoticeMessage();
        $entity->setAppId('wx1234567890123456');
        $entity->setTitle('Test Title ' . uniqid());
        $entity->setPage('pages/index/index');
        $entity->setDescription('Test Desc');

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
