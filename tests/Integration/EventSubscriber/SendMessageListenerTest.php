<?php

namespace WechatWorkPushBundle\Tests\Integration\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkBundle\Service\WorkService;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\EventSubscriber\SendMessageListener;

class SendMessageListenerTest extends TestCase
{
    private SendMessageListener $listener;
    private WorkService&MockObject $mockWorkService;
    private EntityManagerInterface&MockObject $mockEntityManager;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->mockWorkService = $this->createMock(WorkService::class);
        $this->mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $this->mockAgent = $this->createMock(AgentInterface::class);
        
        $this->listener = new SendMessageListener(
            $this->mockWorkService,
            $this->mockEntityManager
        );
    }

    public function test_postPersist_sendsMessageSuccessfully(): void
    {
        $textMessage = new TextMessage();
        $textMessage->setAgent($this->mockAgent);
        $textMessage->setContent('Test message');

        // Create real PostPersistEventArgs
        $eventArgs = new PostPersistEventArgs(
            $textMessage,
            $this->mockEntityManager
        );

        $this->mockWorkService->expects($this->once())
            ->method('request')
            ->willReturn(['msgid' => 'test_msg_123']);

        $this->mockEntityManager->expects($this->once())
            ->method('persist')
            ->with($textMessage);
        
        $this->mockEntityManager->expects($this->once())
            ->method('flush');

        $this->listener->postPersist($textMessage, $eventArgs);
        
        $this->assertEquals('test_msg_123', $textMessage->getMsgId());
    }

    public function test_postPersist_handlesFailedResponse(): void
    {
        $textMessage = new TextMessage();
        $textMessage->setAgent($this->mockAgent);
        $textMessage->setContent('Test message');

        // Create real PostPersistEventArgs
        $eventArgs = new PostPersistEventArgs(
            $textMessage,
            $this->mockEntityManager
        );

        $this->mockWorkService->expects($this->once())
            ->method('request')
            ->willReturn(['error' => 'Failed to send']);

        // No persist or flush should be called when there's no msgid
        $this->mockEntityManager->expects($this->never())
            ->method('persist');
        
        $this->mockEntityManager->expects($this->never())
            ->method('flush');

        $this->listener->postPersist($textMessage, $eventArgs);
        
        $this->assertNull($textMessage->getMsgId());
    }
}