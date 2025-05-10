<?php

namespace WechatWorkPushBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\Model\AppMessage;

class AppMessageTest extends TestCase
{
    private TextMessage $message;
    private Agent $agent;

    protected function setUp(): void
    {
        $this->agent = $this->createMock(Agent::class);
        $this->agent->method('getAgentId')->willReturn('1000001');

        $this->message = new TextMessage();
        $this->message->setAgent($this->agent);
        $this->message->setContent('测试消息');
    }

    public function testClassImplementsAppMessageInterface(): void
    {
        $this->assertInstanceOf(AppMessage::class, $this->message);
    }

    public function testGetMsgType_returnsCorrectType(): void
    {
        $this->assertEquals('text', $this->message->getMsgType());
    }

    public function testGetMsgId_initiallyNull(): void
    {
        $this->assertNull($this->message->getMsgId());
    }

    public function testSetMsgId_setsMsgId(): void
    {
        $msgId = '123456789';
        $this->message->setMsgId($msgId);
        $this->assertEquals($msgId, $this->message->getMsgId());
    }

    public function testGetAgent_returnsSetAgent(): void
    {
        $this->assertSame($this->agent, $this->message->getAgent());
    }

    public function testToRequestArray_containsMsgTypeAndContent(): void
    {
        $requestArray = $this->message->toRequestArray();
        
        $this->assertIsArray($requestArray);
        $this->assertArrayHasKey('msgtype', $requestArray);
        $this->assertEquals('text', $requestArray['msgtype']);
        $this->assertArrayHasKey('text', $requestArray);
        $this->assertIsArray($requestArray['text']);
        $this->assertArrayHasKey('content', $requestArray['text']);
        $this->assertEquals('测试消息', $requestArray['text']['content']);
    }
} 