<?php

namespace WechatWorkPushBundle\Tests\Integration\EventSubscriber;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkBundle\Service\WorkService;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\EventSubscriber\RevokeMessageListener;
use WechatWorkPushBundle\Request\RevokeMessageRequest;

class RevokeMessageListenerTest extends TestCase
{
    private RevokeMessageListener $listener;
    private WorkService&MockObject $mockWorkService;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->mockWorkService = $this->createMock(WorkService::class);
        $this->mockAgent = $this->createMock(AgentInterface::class);
        
        $this->listener = new RevokeMessageListener($this->mockWorkService);
    }

    public function test_postRemove_revokesMessageSuccessfully(): void
    {
        $textMessage = new TextMessage();
        $textMessage->setAgent($this->mockAgent);
        $textMessage->setMsgId('integration_msg_456');

        $this->mockWorkService->expects($this->once())
            ->method('asyncRequest')
            ->with($this->callback(function ($request) {
                return $request instanceof RevokeMessageRequest 
                    && $request->getMsgId() === 'integration_msg_456';
            }));

        $this->listener->postRemove($textMessage);
    }

    public function test_postRemove_skipsWhenNoMsgId(): void
    {
        $textMessage = new TextMessage();
        $textMessage->setAgent($this->mockAgent);
        $textMessage->setMsgId(null);

        $this->mockWorkService->expects($this->never())
            ->method('asyncRequest');

        $this->listener->postRemove($textMessage);
    }
}