<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Traits\AgentTrait;

/**
 * @internal
 */
#[CoversClass(AgentTrait::class)]
final class AgentTraitTest extends TestCase
{
    private TestAgentEntity $testObject;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testObject = new TestAgentEntity();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testSetAgentWithValidAgent(): void
    {
        $this->testObject->setAgent($this->mockAgent);

        $this->assertSame($this->mockAgent, $this->testObject->getAgent());
    }

    public function testSetMsgIdWithValidMsgId(): void
    {
        $msgId = 'msg_123456789';
        $this->testObject->setMsgId($msgId);

        $this->assertEquals($msgId, $this->testObject->getMsgId());
    }

    public function testSetMsgIdWithNull(): void
    {
        $this->testObject->setMsgId(null);

        $this->assertNull($this->testObject->getMsgId());
    }

    public function testSetToUserWithValidArray(): void
    {
        $users = ['user1', 'user2', 'user3'];
        $this->testObject->setToUser($users);

        $this->assertEquals($users, $this->testObject->getToUser());
    }

    public function testSetToUserWithNull(): void
    {
        $this->testObject->setToUser(null);
        $this->assertNull($this->testObject->getToUser());
    }

    public function testSetToUserWithEmptyArray(): void
    {
        $this->testObject->setToUser([]);
        $this->assertEquals([], $this->testObject->getToUser());
    }

    public function testSetToUserWithAllUsers(): void
    {
        $this->testObject->setToUser(['@all']);
        $this->assertEquals(['@all'], $this->testObject->getToUser());
    }

    public function testSetToPartyWithValidArray(): void
    {
        $parties = ['dept1', 'dept2'];
        $this->testObject->setToParty($parties);

        $this->assertEquals($parties, $this->testObject->getToParty());
    }

    public function testSetToPartyWithNull(): void
    {
        $this->testObject->setToParty(null);
        $this->assertNull($this->testObject->getToParty());
    }

    public function testSetToTagWithValidArray(): void
    {
        $tags = ['tag1', 'tag2', 'tag3'];
        $this->testObject->setToTag($tags);

        $this->assertEquals($tags, $this->testObject->getToTag());
    }

    public function testSetToTagWithNull(): void
    {
        $this->testObject->setToTag(null);
        $this->assertNull($this->testObject->getToTag());
    }

    public function testGetAgentArrayWithBasicAgent(): void
    {
        $this->testObject->setAgent($this->mockAgent);

        $expectedArray = [
            'agentid' => '1000002',
        ];

        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function testGetAgentArrayWithToUser(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['user1', 'user2']);

        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1|user2',
        ];

        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function testGetAgentArrayWithToParty(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['user1']); // 需要设置用户，否则部门和标签会被忽略
        $this->testObject->setToParty(['dept1', 'dept2']);

        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1',
            'toparty' => 'dept1|dept2',
        ];

        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function testGetAgentArrayWithToTag(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['user1']);
        $this->testObject->setToTag(['tag1', 'tag2']);

        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1',
            'totag' => 'tag1|tag2',
        ];

        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function testGetAgentArrayWithAllFields(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['user1', 'user2']);
        $this->testObject->setToParty(['dept1']);
        $this->testObject->setToTag(['tag1']);

        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1|user2',
            'toparty' => 'dept1',
            'totag' => 'tag1',
        ];

        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function testGetAgentArrayWithAllUsersIgnoresPartyAndTag(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['@all']);
        $this->testObject->setToParty(['dept1']);
        $this->testObject->setToTag(['tag1']);

        $expectedArray = [
            'agentid' => '1000002',
            'touser' => '@all',
        ];

        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function testGetAgentArrayWithEmptyToUserIncludesPartyAndTag(): void
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

    public function testGetAgentArrayWithNullValues(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(null);
        $this->testObject->setToParty(null);
        $this->testObject->setToTag(null);

        $expectedArray = [
            'agentid' => '1000002',
        ];

        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function testEdgeCasesSingleElementArrays(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $this->testObject->setToUser(['singleuser']);
        $this->testObject->setToParty(['singledept']);
        $this->testObject->setToTag(['singletag']);

        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'singleuser',
            'toparty' => 'singledept',
            'totag' => 'singletag',
        ];

        $this->assertEquals($expectedArray, $this->testObject->getAgentArray());
    }

    public function testEdgeCasesManyElements(): void
    {
        $this->testObject->setAgent($this->mockAgent);
        $users = array_map(fn ($i) => "user{$i}", range(1, 50));
        $this->testObject->setToUser($users);

        $result = $this->testObject->getAgentArray();

        $this->assertEquals(implode('|', $users), $result['touser']);
    }
}
