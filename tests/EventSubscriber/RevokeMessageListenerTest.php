<?php

namespace WechatWorkPushBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\EventSubscriber\RevokeMessageListener;

/**
 * @internal
 */
#[CoversClass(RevokeMessageListener::class)]
#[RunTestsInSeparateProcesses]
final class RevokeMessageListenerTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 集成测试设置
    }

    private function getListener(): RevokeMessageListener
    {
        return self::getService(RevokeMessageListener::class);
    }

    public function testPostRemoveWithValidMsgId(): void
    {
        $listener = $this->getListener();

        $agent = $this->createMock(AgentInterface::class);
        $agent->method('getAgentId')->willReturn('12345');

        $message = new TextMessage();
        $message->setMsgId('test_msg_id');
        $message->setAgent($agent);

        $listener->postRemove($message);

        $this->expectNotToPerformAssertions();
    }

    public function testPostRemoveWithNullMsgId(): void
    {
        $listener = $this->getListener();

        $message = new TextMessage();
        $message->setMsgId(null);

        $listener->postRemove($message);

        $this->expectNotToPerformAssertions();
    }

    public function testPostRemoveWithEmptyMsgId(): void
    {
        $listener = $this->getListener();

        $agent = $this->createMock(AgentInterface::class);
        $agent->method('getAgentId')->willReturn('12345');

        $message = new TextMessage();
        $message->setMsgId('');
        $message->setAgent($agent);

        $listener->postRemove($message);

        $this->expectNotToPerformAssertions();
    }
}
