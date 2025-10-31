<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\TextMessage;

/**
 * @internal
 */
#[CoversClass(TextMessage::class)]
final class TextMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new TextMessage();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            // 主要属性
            'content' => ['content', '这是一条重要的工作通知，请及时查收并处理相关事务。'],

            // TimestampableAware traits
            'createTime' => ['createTime', new \DateTimeImmutable('2024-01-15 09:30:00')],
            'updateTime' => ['updateTime', new \DateTimeImmutable('2024-01-15 10:45:00')],

            // BlameableAware traits
            'createdBy' => ['createdBy', 'admin_user_001'],
            'updatedBy' => ['updatedBy', 'manager_user_002'],

            // IpTraceableAware traits
            'createdFromIp' => ['createdFromIp', '192.168.1.100'],
            'updatedFromIp' => ['updatedFromIp', '10.0.2.50'],

            // AgentTrait properties
            'msgId' => ['msgId', 'msg_text_20240115_093000_001'],
            'toUser' => ['toUser', ['user001', 'user002', 'manager01']],
            'toParty' => ['toParty', ['dept_hr', 'dept_it']],
            'toTag' => ['toTag', ['important', 'urgent']],

            // SafeTrait properties
            'safe' => ['safe', true],

            // DuplicateCheckTrait properties
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 3600],

            // IdTransTrait properties (TextMessage specific)
            'enableIdTrans' => ['enableIdTrans', true],
        ];
    }

    private TextMessage $textMessage;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->textMessage = new TextMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->textMessage->getId());
    }

    public function testGetMsgTypeReturnsText(): void
    {
        $this->assertEquals('text', $this->textMessage->getMsgType());
    }

    public function testSetContentWithValidContent(): void
    {
        $content = '这是一条测试消息';
        $this->textMessage->setContent($content);

        $this->assertEquals($content, $this->textMessage->getContent());
    }

    public function testSetContentWithMaxLength(): void
    {
        $content = str_repeat('测', 1024); // 最大长度2048字节，每个中文字符3字节
        $this->textMessage->setContent($content);

        $this->assertEquals($content, $this->textMessage->getContent());
    }

    public function testSetContentWithEmptyString(): void
    {
        $this->textMessage->setContent('');
        $this->assertEquals('', $this->textMessage->getContent());
    }

    public function testSetCreatedByWithValidUserId(): void
    {
        $userId = 'user123';
        $this->textMessage->setCreatedBy($userId);

        $this->assertEquals($userId, $this->textMessage->getCreatedBy());
    }

    public function testSetCreatedByWithNull(): void
    {
        $this->textMessage->setCreatedBy(null);
        $this->assertNull($this->textMessage->getCreatedBy());
    }

    public function testSetUpdatedByWithValidUserId(): void
    {
        $userId = 'user456';
        $this->textMessage->setUpdatedBy($userId);

        $this->assertEquals($userId, $this->textMessage->getUpdatedBy());
    }

    public function testSetCreatedFromIpWithValidIp(): void
    {
        $ip = '192.168.1.1';
        $this->textMessage->setCreatedFromIp($ip);

        $this->assertEquals($ip, $this->textMessage->getCreatedFromIp());
    }

    public function testSetUpdatedFromIpWithValidIp(): void
    {
        $ip = '10.0.0.1';
        $this->textMessage->setUpdatedFromIp($ip);

        $this->assertEquals($ip, $this->textMessage->getUpdatedFromIp());
    }

    public function testSetCreateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-01 12:00:00');
        $this->textMessage->setCreateTime($dateTime);

        $this->assertEquals($dateTime, $this->textMessage->getCreateTime());
    }

    public function testSetCreateTimeWithNull(): void
    {
        $this->textMessage->setCreateTime(null);
        $this->assertNull($this->textMessage->getCreateTime());
    }

    public function testSetUpdateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-02 15:30:00');
        $this->textMessage->setUpdateTime($dateTime);

        $this->assertEquals($dateTime, $this->textMessage->getUpdateTime());
    }

    public function testToRequestArrayWithBasicData(): void
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
                'content' => 'Hello World',
            ],
        ];

        $this->assertEquals($expectedArray, $this->textMessage->toRequestArray());
    }

    public function testToRequestArrayWithToUser(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Hello');
        $this->textMessage->setToUser(['user1', 'user2']);

        $result = $this->textMessage->toRequestArray();

        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function testToRequestArrayWithToParty(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Hello');
        $this->textMessage->setToUser(['user1']);
        $this->textMessage->setToParty(['dept1', 'dept2']);

        $result = $this->textMessage->toRequestArray();

        $this->assertArrayHasKey('toparty', $result);
        $this->assertEquals('dept1|dept2', $result['toparty']);
    }

    public function testToRequestArrayWithToTag(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Hello');
        $this->textMessage->setToUser(['user1']);
        $this->textMessage->setToTag(['tag1', 'tag2']);

        $result = $this->textMessage->toRequestArray();

        $this->assertArrayHasKey('totag', $result);
        $this->assertEquals('tag1|tag2', $result['totag']);
    }

    public function testToRequestArrayWithAllUsers(): void
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

    public function testToRequestArrayWithSafeEnabled(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Secret message');
        $this->textMessage->setSafe(true);

        $result = $this->textMessage->toRequestArray();

        $this->assertEquals(1, $result['safe']);
    }

    public function testToRequestArrayWithIdTransEnabled(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Message with @user123');
        $this->textMessage->setEnableIdTrans(true);

        $result = $this->textMessage->toRequestArray();

        $this->assertEquals(1, $result['enable_id_trans']);
    }

    public function testToRequestArrayWithDuplicateCheckEnabled(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Duplicate check message');
        $this->textMessage->setEnableDuplicateCheck(true);
        $this->textMessage->setDuplicateCheckInterval(3600);

        $result = $this->textMessage->toRequestArray();

        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function testRetrieveAdminArrayWithCompleteData(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Admin test message');
        $createTime = new \DateTimeImmutable('2024-01-01 10:00:00');
        $updateTime = new \DateTimeImmutable('2024-01-01 11:00:00');
        $this->textMessage->setCreateTime($createTime);
        $this->textMessage->setUpdateTime($updateTime);

        $expectedArray = [
            'id' => null,
            'content' => 'Admin test message',
            'createTime' => '2024-01-01 10:00:00',
            'updateTime' => '2024-01-01 11:00:00',
            'agentid' => '1000002',
        ];

        $this->assertEquals($expectedArray, $this->textMessage->retrieveAdminArray());
    }

    public function testRetrieveAdminArrayWithNullDates(): void
    {
        $this->textMessage->setAgent($this->mockAgent);
        $this->textMessage->setContent('Test');

        $result = $this->textMessage->retrieveAdminArray();

        $this->assertNull($result['createTime']);
        $this->assertNull($result['updateTime']);
    }

    public function testSetMsgIdWithValidMsgId(): void
    {
        $msgId = 'msg_123456789';
        $this->textMessage->setMsgId($msgId);

        $this->assertEquals($msgId, $this->textMessage->getMsgId());
    }

    public function testSetAgentWithValidAgent(): void
    {
        $this->textMessage->setAgent($this->mockAgent);

        $this->assertSame($this->mockAgent, $this->textMessage->getAgent());
    }

    public function testSetToPartyWithValidArray(): void
    {
        $parties = ['dept1', 'dept2', 'dept3'];
        $this->textMessage->setToParty($parties);

        $this->assertEquals($parties, $this->textMessage->getToParty());
    }

    public function testSetToTagWithValidArray(): void
    {
        $tags = ['tag1', 'tag2'];
        $this->textMessage->setToTag($tags);

        $this->assertEquals($tags, $this->textMessage->getToTag());
    }

    public function testEdgeCasesEmptyArrays(): void
    {
        $this->textMessage->setToUser([]);
        $this->textMessage->setToParty([]);
        $this->textMessage->setToTag([]);

        $this->assertEquals([], $this->textMessage->getToUser());
        $this->assertEquals([], $this->textMessage->getToParty());
        $this->assertEquals([], $this->textMessage->getToTag());
    }
}
