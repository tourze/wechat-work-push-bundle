<?php

namespace WechatWorkPushBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Service\WorkService;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\EventSubscriber\RevokeMessageListener;
use WechatWorkPushBundle\Request\RevokeMessageRequest;

class RevokeMessageListenerTest extends TestCase
{
    private WorkService $workService;
    private RevokeMessageListener $listener;
    private Agent $agent;

    protected function setUp(): void
    {
        $this->workService = $this->createMock(WorkService::class);
        $this->listener = new RevokeMessageListener($this->workService);
        
        $this->agent = $this->createMock(Agent::class);
        $this->agent->method('getAgentId')->willReturn('1000001');
    }

    public function testPostRemove_withValidMsgId_revokesMessage(): void
    {
        // 准备测试数据
        $message = new TextMessage();
        $message->setAgent($this->agent);
        $message->setMsgId('123456789');

        // 设置预期
        $this->workService->expects($this->once())
            ->method('asyncRequest')
            ->with($this->callback(function (RevokeMessageRequest $request) {
                return $request->getMsgId() === '123456789'
                    && $request->getAgent() === $this->agent;
            }));

        // 执行测试
        $this->listener->postRemove($message);
    }

    public function testPostRemove_withNullMsgId_doesNotRevokeMessage(): void
    {
        // 准备测试数据
        $message = new TextMessage();
        $message->setAgent($this->agent);
        $message->setMsgId(null);

        // 设置预期 - 不应调用 asyncRequest
        $this->workService->expects($this->never())
            ->method('asyncRequest');

        // 执行测试
        $this->listener->postRemove($message);
    }

    public function testPostRemove_withEmptyMsgId_doesNotRevokeMessage(): void
    {
        // 准备测试数据
        $message = new TextMessage();
        $message->setAgent($this->agent);
        $message->setMsgId('');

        // 设置预期 - 不应调用 asyncRequest
        $this->workService->expects($this->never())
            ->method('asyncRequest');

        // 执行测试
        $this->listener->postRemove($message);
    }
} 