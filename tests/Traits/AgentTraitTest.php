<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\TestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkPushBundle\Traits\AgentTrait;

class AgentTraitTest extends TestCase
{
    private $subject;
    private Agent $agent;

    protected function setUp(): void
    {
        // 创建一个使用特性的匿名类
        $this->subject = new class {
            use AgentTrait;
        };

        $this->agent = $this->createMock(Agent::class);
        $this->agent->method('getAgentId')->willReturn('1000001');
    }

    public function testAgent_setAndGet(): void
    {
        $this->subject->setAgent($this->agent);
        $this->assertSame($this->agent, $this->subject->getAgent());
    }

    public function testMsgId_setAndGet(): void
    {
        $msgId = '123456789';
        $this->assertNull($this->subject->getMsgId());
        $this->subject->setMsgId($msgId);
        $this->assertEquals($msgId, $this->subject->getMsgId());
    }

    public function testToUser_setAndGet(): void
    {
        $toUser = ['user1', 'user2'];
        $this->assertNull($this->subject->getToUser());
        $this->subject->setToUser($toUser);
        $this->assertEquals($toUser, $this->subject->getToUser());
    }

    public function testToParty_setAndGet(): void
    {
        $toParty = ['party1', 'party2'];
        $this->assertNull($this->subject->getToParty());
        $this->subject->setToParty($toParty);
        $this->assertEquals($toParty, $this->subject->getToParty());
    }

    public function testToTag_setAndGet(): void
    {
        $toTag = ['tag1', 'tag2'];
        $this->assertNull($this->subject->getToTag());
        $this->subject->setToTag($toTag);
        $this->assertEquals($toTag, $this->subject->getToTag());
    }

    public function testGetAgentArray_withBasicAgentInfo(): void
    {
        $this->subject->setAgent($this->agent);
        
        $array = $this->subject->getAgentArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('agentid', $array);
        $this->assertEquals('1000001', $array['agentid']);
    }

    public function testGetAgentArray_withUserPartyTag(): void
    {
        $this->subject->setAgent($this->agent);
        $this->subject->setToUser(['user1', 'user2']);
        $this->subject->setToParty(['party1', 'party2']);
        $this->subject->setToTag(['tag1', 'tag2']);
        
        $array = $this->subject->getAgentArray();
        
        $this->assertArrayHasKey('touser', $array);
        $this->assertEquals('user1|user2', $array['touser']);
        $this->assertArrayHasKey('toparty', $array);
        $this->assertEquals('party1|party2', $array['toparty']);
        $this->assertArrayHasKey('totag', $array);
        $this->assertEquals('tag1|tag2', $array['totag']);
    }

    public function testGetAgentArray_withAllUser(): void
    {
        $this->subject->setAgent($this->agent);
        $this->subject->setToUser(['@all']);
        $this->subject->setToParty(['party1', 'party2']);
        $this->subject->setToTag(['tag1', 'tag2']);
        
        $array = $this->subject->getAgentArray();
        
        $this->assertArrayHasKey('touser', $array);
        $this->assertEquals('@all', $array['touser']);
        $this->assertArrayNotHasKey('toparty', $array);
        $this->assertArrayNotHasKey('totag', $array);
    }

    public function testGetAgentArray_withNullValues(): void
    {
        $this->subject->setAgent($this->agent);
        $this->subject->setToUser(null);
        $this->subject->setToParty(null);
        $this->subject->setToTag(null);
        
        $array = $this->subject->getAgentArray();
        
        $this->assertArrayHasKey('agentid', $array);
        $this->assertArrayNotHasKey('touser', $array);
        $this->assertArrayNotHasKey('toparty', $array);
        $this->assertArrayNotHasKey('totag', $array);
    }
} 