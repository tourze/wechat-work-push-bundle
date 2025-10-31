<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\MultipleTemplateMessage;
use WechatWorkPushBundle\Repository\MultipleTemplateMessageRepository;

/**
 * @template-extends AbstractRepositoryTestCase<MultipleTemplateMessage>
 * @internal
 */
#[CoversClass(MultipleTemplateMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class MultipleTemplateMessageRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): object
    {
        $uniqueId = uniqid();
        $entity = new MultipleTemplateMessage();

        // 设置必填的 title 和 description 字段
        $entity->setTitle('Test Multiple Template Title ' . $uniqueId);
        $entity->setDescription('Test multiple template description ' . $uniqueId);

        $entity->setQuestionKey('test_question_' . $uniqueId);
        $entity->setOptions([['id' => '1', 'text' => 'Option 1'], ['id' => '2', 'text' => 'Option 2']]);

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

    protected function getRepository(): MultipleTemplateMessageRepository
    {
        $repository = self::getEntityManager()->getRepository(MultipleTemplateMessage::class);
        self::assertInstanceOf(MultipleTemplateMessageRepository::class, $repository);

        return $repository;
    }

    protected function onSetUp(): void
    {
        // Repository test setup is handled by parent class
    }

    private function createValidEntity(): MultipleTemplateMessage
    {
        $entity = new MultipleTemplateMessage();
        $entity->setTitle('Test Multiple Template Title ' . uniqid());
        $entity->setDescription('Test Multiple Template Description');
        $entity->setQuestionKey('test_question_key_' . uniqid());
        $entity->setOptions([
            ['id' => '1', 'text' => 'Option 1'],
            ['id' => '2', 'text' => 'Option 2'],
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
