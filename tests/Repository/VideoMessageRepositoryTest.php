<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\VideoMessage;
use WechatWorkPushBundle\Repository\VideoMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<VideoMessage>
 * @internal
 */
#[CoversClass(VideoMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class VideoMessageRepositoryTest extends AbstractRepositoryTestCase
{
    private VideoMessageRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(VideoMessageRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new VideoMessage();
        $entity->setMediaId('test_video_media_id_' . uniqid());
        $entity->setTitle('Test Video Title ' . uniqid());
        $entity->setDescription('Test video description ' . uniqid());

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

    protected function getRepository(): VideoMessageRepository
    {
        return $this->repository;
    }

    private function createTestEntity(): VideoMessage
    {
        $uniqueId = uniqid();
        $entity = new VideoMessage();
        $entity->setMediaId('test_media_id_' . $uniqueId);
        $entity->setTitle('Test Video Title ' . $uniqueId);
        $entity->setDescription('Test video description ' . $uniqueId);

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
