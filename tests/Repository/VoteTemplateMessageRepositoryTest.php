<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\VoteTemplateMessage;
use WechatWorkPushBundle\Repository\VoteTemplateMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<VoteTemplateMessage>
 * @internal
 */
#[CoversClass(VoteTemplateMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class VoteTemplateMessageRepositoryTest extends AbstractRepositoryTestCase
{
    private VoteTemplateMessageRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(VoteTemplateMessageRepository::class);
    }

    protected function createNewEntity(): object
    {
        $uniqueId = uniqid();
        $entity = new VoteTemplateMessage();

        // 设置必填的 title 和 description 字段
        $entity->setTitle('Test Vote Title ' . $uniqueId);
        $entity->setDescription('Test vote description ' . $uniqueId);

        $entity->setQuestionKey('test_vote_question_' . $uniqueId);
        $entity->setOptions([['id' => '1', 'text' => 'Vote Option 1'], ['id' => '2', 'text' => 'Vote Option 2']]);

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

    protected function getRepository(): VoteTemplateMessageRepository
    {
        return $this->repository;
    }

    private function createTestEntity(): VoteTemplateMessage
    {
        $uniqueId = uniqid();
        $entity = new VoteTemplateMessage();
        $entity->setTitle('Test Vote Title ' . $uniqueId);
        $entity->setDescription('Test vote description ' . $uniqueId);
        $entity->setQuestionKey('test_question_key_' . $uniqueId);
        $entity->setOptions([
            ['id' => '1', 'text' => 'Option A'],
            ['id' => '2', 'text' => 'Option B'],
        ]);

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
