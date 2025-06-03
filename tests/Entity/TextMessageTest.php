<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\TextMessage;

class TextMessageTest extends TestCase
{
    private TextMessage $textMessage;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->textMessage = new TextMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->textMessage->getId());
    }

    public function test_getMsgType_returnsText(): void
    {
        $this->assertEquals('text', $this->textMessage->getMsgType());
    }

    public function test_setContent_withValidContent(): void
    {
        $content = '这是一条测试消息';
        $result = $this->textMessage->setContent($content);
        
        $this->assertSame($this->textMessage, $result);
        $this->assertEquals($content, $this->textMessage->getContent());
    }

    public function test_setContent_withMaxLength(): void
    {
        $content = str_repeat('测', 1024); // 最大长度2048字节，每个中文字符3字节
        $this->textMessage->setContent($content);
        
        $this->assertEquals($content, $this->textMessage->getContent());
    }

    public function test_setContent_withEmptyString(): void
    {
        $this->textMessage->setContent('');
        $this->assertEquals('', $this->textMessage->getContent());
    }

    public function test_setCreatedBy_withValidUserId(): void
    {
        $userId = 'user123';
        $result = $this->textMessage->setCreatedBy($userId);
        
        $this->assertSame($this->textMessage, $result);
        $this->assertEquals($userId, $this->textMessage->getCreatedBy());
    }

    public function test_setCreatedBy_withNull(): void
    {
        $this->textMessage->setCreatedBy(null);
        $this->assertNull($this->textMessage->getCreatedBy());
    }

    public function test_setUpdatedBy_withValidUserId(): void
    {
        $userId = 'user456';
        $result = $this->textMessage->setUpdatedBy($userId);
        
        $this->assertSame($this->textMessage, $result);
        $this->assertEquals($userId, $this->textMessage->getUpdatedBy());
    }

    public function test_setCreatedFromIp_withValidIp(): void
    {
        $ip = '192.168.1.1';
        $result = $this->textMessage->setCreatedFromIp($ip);
        
        $this->assertSame($this->textMessage, $result);
        $this->assertEquals($ip, $this->textMessage->getCreatedFromIp());
    }

    public function test_setUpdatedFromIp_withValidIp(): void
    {
        $ip = '10.0.0.1';
        $result = $this->textMessage->setUpdatedFromIp($ip);
        
        $this->assertSame($this->textMessage, $result);
        $this->assertEquals($ip, $this->textMessage->getUpdatedFromIp());
    }

    public function test_setCreateTime_withDateTime(): void
    {
        $dateTime = new \DateTime('2024-01-01 12:00:00');
        $this->textMessage->setCreateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->textMessage->getCreateTime());
    }

    public function test_setCreateTime_withNull(): void
    {
        $this->textMessage->setCreateTime(null);
        $this->assertNull($this->textMessage->getCreateTime());
    }

    public function test_setUpdateTime_withDateTime(): void
    {
        $dateTime = new \DateTime('2024-01-02 15:30:00');
        $this->textMessage->setUpdateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->textMessage->getUpdateTime());
    }

    public function test_toRequestArray_withBasicData(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Hello World');
        
        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_id_trans' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'text',
            'text' => [
                'content' => 'Hello World'
            ]
        ];
        
        $this->assertEquals($expectedArray, $this->textMessage->toRequestArray());
    }

    public function test_toRequestArray_withToUser(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Hello');
        $this->textMessage->setToUser(['user1', 'user2']);
        
        $result = $this->textMessage->toRequestArray();
        
        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function test_toRequestArray_withToParty(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Hello');
        $this->textMessage->setToUser(['user1']);
        $this->textMessage->setToParty(['dept1', 'dept2']);
        
        $result = $this->textMessage->toRequestArray();
        
        $this->assertArrayHasKey('toparty', $result);
        $this->assertEquals('dept1|dept2', $result['toparty']);
    }

    public function test_toRequestArray_withToTag(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Hello');
        $this->textMessage->setToUser(['user1']);
        $this->textMessage->setToTag(['tag1', 'tag2']);
        
        $result = $this->textMessage->toRequestArray();
        
        $this->assertArrayHasKey('totag', $result);
        $this->assertEquals('tag1|tag2', $result['totag']);
    }

    public function test_toRequestArray_withAllUsers(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Hello');
        $this->textMessage->setToUser(['@all']);
        $this->textMessage->setToParty(['dept1']);
        $this->textMessage->setToTag(['tag1']);
        
        $result = $this->textMessage->toRequestArray();
        
        $this->assertEquals('@all', $result['touser']);
        $this->assertArrayNotHasKey('toparty', $result);
        $this->assertArrayNotHasKey('totag', $result);
    }

    public function test_toRequestArray_withSafeEnabled(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Secret message');
        $this->textMessage->setSafe(true);
        
        $result = $this->textMessage->toRequestArray();
        
        $this->assertEquals(1, $result['safe']);
    }

    public function test_toRequestArray_withIdTransEnabled(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Message with @user123');
        $this->textMessage->setEnableIdTrans(true);
        
        $result = $this->textMessage->toRequestArray();
        
        $this->assertEquals(1, $result['enable_id_trans']);
    }

    public function test_toRequestArray_withDuplicateCheckEnabled(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Duplicate check message');
        $this->textMessage->setEnableDuplicateCheck(true);
        $this->textMessage->setDuplicateCheckInterval(3600);
        
        $result = $this->textMessage->toRequestArray();
        
        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function test_retrieveAdminArray_withCompleteData(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Admin test message');
        $createTime = new \DateTime('2024-01-01 10:00:00');
        $updateTime = new \DateTime('2024-01-01 11:00:00');
        $this->textMessage->setCreateTime($createTime);
        $this->textMessage->setUpdateTime($updateTime);
        
        $expectedArray = [
            'id' => null,
            'content' => 'Admin test message',
            'createTime' => '2024-01-01 10:00:00',
            'updateTime' => '2024-01-01 11:00:00',
            'agentid' => '1000002'
        ];
        
        $this->assertEquals($expectedArray, $this->textMessage->retrieveAdminArray());
    }

    public function test_retrieveAdminArray_withNullDates(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Test');
        
        $result = $this->textMessage->retrieveAdminArray();
        
        $this->assertNull($result['createTime']);
        $this->assertNull($result['updateTime']);
    }

    public function test_setMsgId_withValidMsgId(): void
    {
        $msgId = 'msg_123456789';
        $result = $this->textMessage->setMsgId($msgId);
        
        $this->assertSame($this->textMessage, $result);
        $this->assertEquals($msgId, $this->textMessage->getMsgId());
    }

    public function test_setAgent_withValidAgent(): void
    {
        $result = $this->textMessage->setAgent($this->mockAgent);
        
        $this->assertSame($this->textMessage, $result);
        $this->assertSame($this->mockAgent, $this->textMessage->getAgent());
    }

    public function test_setToParty_withValidArray(): void
    {
        $parties = ['dept1', 'dept2', 'dept3'];
        $this->textMessage->setToParty($parties);
        
        $this->assertEquals($parties, $this->textMessage->getToParty());
    }

    public function test_setToTag_withValidArray(): void
    {
        $tags = ['tag1', 'tag2'];
        $this->textMessage->setToTag($tags);
        
        $this->assertEquals($tags, $this->textMessage->getToTag());
    }

    public function test_edgeCases_emptyArrays(): void
    {
        $this->textMessage->setToUser([]);
        $this->textMessage->setToParty([]);
        $this->textMessage->setToTag([]);
        
        $this->assertEquals([], $this->textMessage->getToUser());
        $this->assertEquals([], $this->textMessage->getToParty());
        $this->assertEquals([], $this->textMessage->getToTag());
    }
} 