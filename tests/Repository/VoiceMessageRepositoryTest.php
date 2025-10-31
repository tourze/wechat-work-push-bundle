<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\VoiceMessage;
use WechatWorkPushBundle\Repository\VoiceMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<VoiceMessage>
 * @internal
 */
#[CoversClass(VoiceMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class VoiceMessageRepositoryTest extends AbstractRepositoryTestCase
{
    private VoiceMessageRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(VoiceMessageRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new VoiceMessage();
        $entity->setMediaId('test_voice_media_id_' . uniqid());

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

    protected function getRepository(): VoiceMessageRepository
    {
        return $this->repository;
    }

    private function createTestEntity(): VoiceMessage
    {
        $entity = new VoiceMessage();
        $entity->setMediaId('test_voice_media_id_' . uniqid());

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
