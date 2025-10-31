<?php

namespace WechatWorkPushBundle\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkPushBundle\Entity\MpnewsMessage;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\EventSubscriber\SendMessageListener;

/**
 * @internal
 */
#[CoversClass(SendMessageListener::class)]
#[RunTestsInSeparateProcesses]
final class SendMessageListenerTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 集成测试设置
    }

    private function getListener(): SendMessageListener
    {
        return self::getService(SendMessageListener::class);
    }

    private function createEntityManager(): EntityManagerInterface
    {
        return $this->createMock(EntityManagerInterface::class);
    }

    public function testPostPersistWithTextMessage(): void
    {
        $listener = $this->getListener();

        $agent = $this->createMock(AgentInterface::class);
        $agent->method('getAgentId')->willReturn('12345');

        $message = new TextMessage();
        $message->setAgent($agent);
        $message->setContent('Test message');

        $entityManager = $this->createEntityManager();
        $eventArgs = new PostPersistEventArgs($message, $entityManager);

        $listener->postPersist($message, $eventArgs);

        $this->expectNotToPerformAssertions();
    }

    public function testPostPersistWithMpnewsMessage(): void
    {
        $listener = $this->getListener();

        $corp = new Corp();
        $corp->setName('Test Corp ' . uniqid());
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setCorpSecret('test_secret');

        $agent = new Agent();
        $agent->setAgentId('12345');
        $agent->setName('Test Agent');
        $agent->setSecret('test_secret');
        $agent->setCorp($corp);

        $message = new MpnewsMessage();
        $message->setAgent($agent);
        $message->setThumbMediaUrl('https://example.com/thumb.jpg');

        $entityManager = $this->createEntityManager();
        $eventArgs = new PostPersistEventArgs($message, $entityManager);

        $listener->postPersist($message, $eventArgs);

        $this->expectNotToPerformAssertions();
    }

    public function testPostPersistWithoutAgent(): void
    {
        $listener = $this->getListener();

        $message = new TextMessage();
        $message->setContent('Test message');

        $entityManager = $this->createEntityManager();
        $eventArgs = new PostPersistEventArgs($message, $entityManager);

        $listener->postPersist($message, $eventArgs);

        $this->expectNotToPerformAssertions();
    }
}
