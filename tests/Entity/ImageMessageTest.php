<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\ImageMessage;

/**
 * @internal
 */
#[CoversClass(ImageMessage::class)]
final class ImageMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ImageMessage();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            // 主要属性
            'mediaId' => ['mediaId', 'image_media_20240115_company_chart.jpg'],

            // TimestampableAware traits
            'createTime' => ['createTime', new \DateTimeImmutable('2024-01-15 16:10:00')],
            'updateTime' => ['updateTime', new \DateTimeImmutable('2024-01-15 16:15:00')],

            // BlameableAware traits
            'createdBy' => ['createdBy', 'design_team_001'],
            'updatedBy' => ['updatedBy', 'content_reviewer_002'],

            // IpTraceableAware traits
            'createdFromIp' => ['createdFromIp', '10.1.1.25'],
            'updatedFromIp' => ['updatedFromIp', '192.168.50.30'],

            // AgentTrait properties
            'msgId' => ['msgId', 'msg_image_20240115_161000_001'],
            'toUser' => ['toUser', ['all_staff', 'department_heads']],
            'toParty' => ['toParty', ['design_dept', 'communications_dept']],
            'toTag' => ['toTag', ['company_updates', 'visual_content']],

            // SafeTrait properties
            'safe' => ['safe', false],

            // DuplicateCheckTrait properties
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 1800],
        ];
    }

    private ImageMessage $imageMessage;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageMessage = new ImageMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->imageMessage->getId());
    }

    public function testGetMsgTypeReturnsImage(): void
    {
        $this->assertEquals('image', $this->imageMessage->getMsgType());
    }

    public function testSetMediaIdWithValidMediaId(): void
    {
        $mediaId = 'media_12345678901234567890';
        $this->imageMessage->setMediaId($mediaId);

        $this->assertEquals($mediaId, $this->imageMessage->getMediaId());
    }

    public function testSetMediaIdWithEmptyString(): void
    {
        $this->imageMessage->setMediaId('');
        $this->assertEquals('', $this->imageMessage->getMediaId());
    }

    public function testSetCreateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-01 12:00:00');
        $this->imageMessage->setCreateTime($dateTime);

        $this->assertEquals($dateTime, $this->imageMessage->getCreateTime());
    }

    public function testSetCreateTimeWithNull(): void
    {
        $this->imageMessage->setCreateTime(null);
        $this->assertNull($this->imageMessage->getCreateTime());
    }

    public function testSetUpdateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-02 15:30:00');
        $this->imageMessage->setUpdateTime($dateTime);

        $this->assertEquals($dateTime, $this->imageMessage->getUpdateTime());
    }

    public function testToRequestArrayWithBasicData(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);
        $this->imageMessage->setMediaId('media_test123');

        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'image',
            'image' => [
                'media_id' => 'media_test123',
            ],
            'enable_id_trans' => 0,
        ];

        $this->assertEquals($expectedArray, $this->imageMessage->toRequestArray());
    }

    public function testToRequestArrayWithToUser(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);
        $this->imageMessage->setMediaId('media_test123');
        $this->imageMessage->setToUser(['user1', 'user2']);

        $result = $this->imageMessage->toRequestArray();

        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function testToRequestArrayWithSafeEnabled(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);
        $this->imageMessage->setMediaId('media_secret123');
        $this->imageMessage->setSafe(true);

        $result = $this->imageMessage->toRequestArray();

        $this->assertEquals(1, $result['safe']);
    }

    public function testToRequestArrayWithDuplicateCheckEnabled(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);
        $this->imageMessage->setMediaId('media_duplicate123');
        $this->imageMessage->setEnableDuplicateCheck(true);
        $this->imageMessage->setDuplicateCheckInterval(3600);

        $result = $this->imageMessage->toRequestArray();

        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function testUserTrackingMethods(): void
    {
        $userId = 'user123';
        $ip = '192.168.1.1';

        $this->imageMessage->setCreatedBy($userId);
        $this->imageMessage->setUpdatedBy($userId);
        $this->imageMessage->setCreatedFromIp($ip);
        $this->imageMessage->setUpdatedFromIp($ip);

        $this->assertEquals($userId, $this->imageMessage->getCreatedBy());
        $this->assertEquals($userId, $this->imageMessage->getUpdatedBy());
        $this->assertEquals($ip, $this->imageMessage->getCreatedFromIp());
        $this->assertEquals($ip, $this->imageMessage->getUpdatedFromIp());
    }

    public function testSetMsgIdWithValidMsgId(): void
    {
        $msgId = 'msg_image_123456';
        $this->imageMessage->setMsgId($msgId);

        $this->assertEquals($msgId, $this->imageMessage->getMsgId());
    }

    public function testSetAgentWithValidAgent(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);

        $this->assertSame($this->mockAgent, $this->imageMessage->getAgent());
    }

    public function testAgentTraitMethods(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);
        $this->imageMessage->setToUser(['user1', 'user2']);
        $this->imageMessage->setToParty(['dept1']);
        $this->imageMessage->setToTag(['tag1']);

        $this->assertSame($this->mockAgent, $this->imageMessage->getAgent());
        $this->assertEquals(['user1', 'user2'], $this->imageMessage->getToUser());
        $this->assertEquals(['dept1'], $this->imageMessage->getToParty());
        $this->assertEquals(['tag1'], $this->imageMessage->getToTag());
    }

    public function testEdgeCasesLongMediaId(): void
    {
        $longMediaId = str_repeat('a', 99); // 不超过字段长度限制
        $this->imageMessage->setMediaId($longMediaId);

        $this->assertEquals($longMediaId, $this->imageMessage->getMediaId());
    }

    public function testEdgeCasesSpecialCharactersInMediaId(): void
    {
        $specialMediaId = 'media_-_123_ABC_xyz';
        $this->imageMessage->setMediaId($specialMediaId);

        $this->assertEquals($specialMediaId, $this->imageMessage->getMediaId());
    }

    public function testToRequestArrayWithAllUsers(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);
        $this->imageMessage->setMediaId('media_all123');
        $this->imageMessage->setToUser(['@all']);
        $this->imageMessage->setToParty(['dept1']);
        $this->imageMessage->setToTag(['tag1']);

        $result = $this->imageMessage->toRequestArray();

        $this->assertEquals('@all', $result['touser']);
        $this->assertArrayNotHasKey('toparty', $result);
        $this->assertArrayNotHasKey('totag', $result);
    }
}
