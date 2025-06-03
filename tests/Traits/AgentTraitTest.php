<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Traits\AgentTrait;

class AgentTraitTest extends TestCase
{
    private AgentTraitTestClass $testObject;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->testObject = new AgentTraitTestClass();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_setAgent_withValidAgent(): void
    {
        $result = $this->testObject->setAgent($this->mockAgent);
        
        $this->assertSame($this->testObject, $result);
        $this->assertSame($this->mockAgent, $this->testObject->getAgent());
    }

    public function test_setMsgId_withValidMsgId(): void
    {
        $msgId = 'msg_123456789';
        $result = $this->testObject->setMsgId($msgId);
        
        $this->assertSame($this->testObject, $result);
        $this->assertEquals($msgId, $this->testObject->getMsgId());
    }

    public function test_setMsgId_withNull(): void
    {
        $result = $this->testObject->setMsgId(null);
        
        $this->assertSame($this->testObject, $result);
        $this->assertNull($this->testObject->getMsgId());
    }

    public function test_setToUser_withValidArray(): void
    {
        $users = ['user1', 'user2', 'user3'];
        $result = $this->testObject->setToUser($users);
        
        $this->assertSame($this->testObject, $result);
        $this->assertEquals($users, $this->testObject->getToUser());
    }

    public function test_setToUser_withNull(): void
    {
        $this->testObject->setToUser(null);
        $this->assertNull($this->testObject->getToUser());
    }

    public function test_setToUser_withEmptyArray(): void
    {
        $this->testObject->setToUser([]);
        $this->assertEquals([], $this->testObject->getToUser());
    }

    public function test_setToUser_withAllUsers(): void
    {
        $this->testObject->setToUser(['@all']);
        $this->assertEquals(['@all'], $this->testObject->getToUser());
    }

    public function test_setToParty_withValidArray(): void
    {
        $parties = ['dept1', 'dept2'];
        $this->testObject->setToParty($parties);
        
        $this->assertEquals($parties, $this->testObject->getToParty());
    }

    public function test_setToParty_withNull(): void
    {
        $this->testObject->setToParty(null);
        $this->assertNull($this->testObject->getToParty());
    }

    public function test_setToTag_withValidArray(): void
    {
        $tags = ['tag1', 'tag2', 'tag3'];
        $this->testObject->setToTag($tags);
        
        $this->assertEquals($tags, $this->testObject->getToTag());
    }

    public function test_setToTag_withNull(): void
    {
        $this->testObject->setToTag(null);
        $this->assertNull($this->testObject->getToTag());
    }

    public function test_getAgentArray_withBasicAgent(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        
        $expectedArray = [
            'agentid' => '1000002'
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function test_getAgentArray_withToUser(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['user1', 'user2']);
        
        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1|user2'
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function test_getAgentArray_withToParty(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['user1']); // 需要设置用户，否则部门和标签会被忽略
        $this->testObject->setToParty(['dept1', 'dept2']);
        
        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1',
            'toparty' => 'dept1|dept2'
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function test_getAgentArray_withToTag(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['user1']);
        $this->testObject->setToTag(['tag1', 'tag2']);
        
        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1',
            'totag' => 'tag1|tag2'
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function test_getAgentArray_withAllFields(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['user1', 'user2']);
        $this->testObject->setToParty(['dept1']);
        $this->testObject->setToTag(['tag1']);
        
        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1|user2',
            'toparty' => 'dept1',
            'totag' => 'tag1'
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function test_getAgentArray_withAllUsers_ignoresPartyAndTag(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['@all']);
        $this->testObject->setToParty(['dept1']);
        $this->testObject->setToTag(['tag1']);
        
        $expectedArray = [
            'agentid' => '1000002',
            'touser' => '@all'
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function test_getAgentArray_withEmptyToUser_includesPartyAndTag(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser([]);
        $this->testObject->setToParty(['dept1']);
        $this->testObject->setToTag(['tag1']);
        
        $result = $this->testObject->getAgentArray();
        
        $this->assertEquals('1000002', $result['agentid']);
        $this->assertEquals('', $result['touser']);
        $this->assertEquals('dept1', $result['toparty']);
        $this->assertEquals('tag1', $result['totag']);
    }

    public function test_getAgentArray_withNullValues(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(null);
        $this->testObject->setToParty(null);
        $this->testObject->setToTag(null);
        
        $expectedArray = [
            'agentid' => '1000002'
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function test_edgeCases_singleElementArrays(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['singleuser']);
        $this->testObject->setToParty(['singledept']);
        $this->testObject->setToTag(['singletag']);
        
        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'singleuser',
            'toparty' => 'singledept',
            'totag' => 'singletag'
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function test_edgeCases_manyElements(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $users = array_map(fn($i) => "user$i", range(1, 50));
        $this->testObject->setToUser($users);
        
        $result = $this->testObject->getAgentArray();
        
        $this->assertEquals(implode('|', $users), $result['touser']);
    }
}

/**
 * 用于测试 AgentTrait 的具体实现类
 */
class AgentTraitTestClass
{
    use AgentTrait;
} 