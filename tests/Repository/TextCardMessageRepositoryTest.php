<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\TextCardMessage;
use WechatWorkPushBundle\Repository\TextCardMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<TextCardMessage>
 * @internal
 */
#[CoversClass(TextCardMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class TextCardMessageRepositoryTest extends AbstractRepositoryTestCase
{
    private TextCardMessageRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(TextCardMessageRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new TextCardMessage();
        $entity->setTitle('Test Title ' . uniqid());
        $entity->setDescription('Test Description ' . uniqid());
        $entity->setUrl('https://example.com/test/' . uniqid());
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

    protected function getRepository(): TextCardMessageRepository
    {
        return $this->repository;
    }

    private function createTestEntity(): TextCardMessage
    {
        $uniqueId = uniqid();
        $entity = new TextCardMessage();
        $entity->setTitle('Test Card Title ' . $uniqueId);
        $entity->setDescription('Test card description ' . $uniqueId);
        $entity->setUrl('https://example.com/test/' . $uniqueId);
        $entity->setBtnText('Click');

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
