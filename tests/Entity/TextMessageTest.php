<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkPushBundle\Entity\TextMessage;

class TextMessageTest extends TestCase
{
    private TextMessage $message;
    private Agent $agent;

    protected function setUp(): void
    {
        $this->agent = $this->createMock(Agent::class);
        $this->agent->method('getAgentId')->willReturn('1000001');

        $this->message = new TextMessage();
        $this->message->setAgent($this->agent);
    }

    public function testId_initiallyNull(): void
    {
        $this->assertNull($this->message->getId());
    }

    public function testContent_setAndGet(): void
    {
        $content = '这是一条测试消息';
        $this->message->setContent($content);
        $this->assertEquals($content, $this->message->getContent());
    }

    public function testMsgType_returnsText(): void
    {
        $this->assertEquals('text', $this->message->getMsgType());
    }

    public function testMsgId_setAndGet(): void
    {
        $msgId = '123456789';
        $this->assertNull($this->message->getMsgId());
        $this->message->setMsgId($msgId);
        $this->assertEquals($msgId, $this->message->getMsgId());
    }

    public function testCreateTime_setAndGet(): void
    {
        $date = new \DateTime();
        $this->message->setCreateTime($date);
        $this->assertSame($date, $this->message->getCreateTime());
    }

    public function testUpdateTime_setAndGet(): void
    {
        $date = new \DateTime();
        $this->message->setUpdateTime($date);
        $this->assertSame($date, $this->message->getUpdateTime());
    }

    public function testToRequestArray_basicStructure(): void
    {
        $content = '这是一条测试消息';
        $this->message->setContent($content);

        $requestArray = $this->message->toRequestArray();

        $this->assertIsArray($requestArray);
        $this->assertEquals('text', $requestArray['msgtype']);
        $this->assertArrayHasKey('text', $requestArray);
        $this->assertArrayHasKey('content', $requestArray['text']);
        $this->assertEquals($content, $requestArray['text']['content']);
        $this->assertEquals('1000001', $requestArray['agentid']);
    }

    public function testToRequestArray_withToUserPartyTag(): void
    {
        $this->message->setContent('测试消息');
        $this->message->setToUser(['user1', 'user2']);
        $this->message->setToParty(['party1', 'party2']);
        $this->message->setToTag(['tag1', 'tag2']);

        $requestArray = $this->message->toRequestArray();

        $this->assertArrayHasKey('touser', $requestArray);
        $this->assertEquals('user1|user2', $requestArray['touser']);
        $this->assertArrayHasKey('toparty', $requestArray);
        $this->assertEquals('party1|party2', $requestArray['toparty']);
        $this->assertArrayHasKey('totag', $requestArray);
        $this->assertEquals('tag1|tag2', $requestArray['totag']);
    }

    public function testToRequestArray_withAllUser(): void
    {
        $this->message->setContent('测试消息');
        $this->message->setToUser(['@all']);
        $this->message->setToParty(['party1', 'party2']);
        $this->message->setToTag(['tag1', 'tag2']);

        $requestArray = $this->message->toRequestArray();

        $this->assertArrayHasKey('touser', $requestArray);
        $this->assertEquals('@all', $requestArray['touser']);
        $this->assertArrayNotHasKey('toparty', $requestArray);
        $this->assertArrayNotHasKey('totag', $requestArray);
    }

    public function testRetrieveAdminArray_containsExpectedKeys(): void
    {
        $content = '测试消息';
        $this->message->setContent($content);
        $date = new \DateTime('2023-01-01 12:00:00');
        $this->message->setCreateTime($date);
        $this->message->setUpdateTime($date);

        $adminArray = $this->message->retrieveAdminArray();

        $this->assertIsArray($adminArray);
        $this->assertArrayHasKey('id', $adminArray);
        $this->assertArrayHasKey('content', $adminArray);
        $this->assertArrayHasKey('createTime', $adminArray);
        $this->assertArrayHasKey('updateTime', $adminArray);
        $this->assertEquals($content, $adminArray['content']);
        $this->assertEquals('2023-01-01 12:00:00', $adminArray['createTime']);
        $this->assertEquals('2023-01-01 12:00:00', $adminArray['updateTime']);
    }
} 