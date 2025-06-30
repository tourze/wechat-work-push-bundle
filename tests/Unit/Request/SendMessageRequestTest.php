<?php

namespace WechatWorkPushBundle\Tests\Unit\Request;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Request\SendMessageRequest;

class SendMessageRequestTest extends TestCase
{
    private SendMessageRequest $request;
    private AgentInterface&MockObject $mockAgent;
    private AppMessage&MockObject $mockMessage;

    protected function setUp(): void
    {
        $this->request = new SendMessageRequest();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockMessage = $this->createMock(AppMessage::class);
    }

    public function test_getRequestPath_returnsCorrectPath(): void
    {
        $this->assertEquals('/cgi-bin/message/send', $this->request->getRequestPath());
    }

    public function test_setMessage_andGetMessage(): void
    {
        $this->request->setMessage($this->mockMessage);
        
        $this->assertSame($this->mockMessage, $this->request->getMessage());
    }

    public function test_getRequestOptions_withValidAgentAndMessage(): void
    {
        $this->mockAgent->expects($this->once())
            ->method('getAgentId')
            ->willReturn('1000002');

        $this->mockMessage->expects($this->once())
            ->method('toRequestArray')
            ->willReturn([
                'msgtype' => 'text',
                'text' => ['content' => 'Test message']
            ]);

        $this->request->setAgent($this->mockAgent);
        $this->request->setMessage($this->mockMessage);

        $options = $this->request->getRequestOptions();

        $expectedJson = [
            'agentid' => '1000002',
            'msgtype' => 'text',
            'text' => ['content' => 'Test message']
        ];

        $this->assertEquals(['json' => $expectedJson], $options);
    }

    public function test_getRequestOptions_mergesAgentIdWithMessageArray(): void
    {
        $this->mockAgent->expects($this->once())
            ->method('getAgentId')
            ->willReturn('2000003');

        $messageArray = [
            'msgtype' => 'image',
            'image' => ['media_id' => 'media123'],
            'touser' => 'user1'
        ];

        $this->mockMessage->expects($this->once())
            ->method('toRequestArray')
            ->willReturn($messageArray);

        $this->request->setAgent($this->mockAgent);
        $this->request->setMessage($this->mockMessage);

        $options = $this->request->getRequestOptions();

        $expectedJson = [
            'agentid' => '2000003',
            'msgtype' => 'image',
            'image' => ['media_id' => 'media123'],
            'touser' => 'user1'
        ];

        $this->assertEquals(['json' => $expectedJson], $options);
    }
}