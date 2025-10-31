<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\VideoMessage;

/**
 * @internal
 */
#[CoversClass(VideoMessage::class)]
final class VideoMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new VideoMessage();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            // VideoMessage 实体直接属性
            'mediaId' => ['mediaId', 'video_12345678901234567890'],
            'title' => ['title', '企业培训视频'],
            'description' => ['description', '2024年度企业安全培训课程视频，包含消防安全和网络安全相关内容'],

            // TimestampableAware trait 属性
            'createTime' => ['createTime', new \DateTimeImmutable('2024-01-15 09:30:00')],
            'updateTime' => ['updateTime', new \DateTimeImmutable('2024-01-16 14:20:00')],

            // BlameableAware trait 属性
            'createdBy' => ['createdBy', 'admin_user_001'],
            'updatedBy' => ['updatedBy', 'editor_user_002'],

            // IpTraceableAware trait 属性
            'createdFromIp' => ['createdFromIp', '192.168.1.100'],
            'updatedFromIp' => ['updatedFromIp', '192.168.1.101'],

            // AgentTrait 属性 (除了 agent 本身，因为无法在静态方法中创建 Mock)
            'msgId' => ['msgId', 'video_msg_20240115_093000'],
            'toUser' => ['toUser', ['employee_001', 'employee_002', 'manager_001']],
            'toParty' => ['toParty', ['training_dept', 'safety_dept']],
            'toTag' => ['toTag', ['new_employee', 'safety_training']],

            // SafeTrait 属性
            'safe' => ['safe', true],

            // DuplicateCheckTrait 属性
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 3600],
        ];
    }

    private VideoMessage $videoMessage;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->videoMessage = new VideoMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->videoMessage->getId());
    }

    public function testGetMsgTypeReturnsVideo(): void
    {
        $this->assertEquals('video', $this->videoMessage->getMsgType());
    }

    public function testSetMediaIdWithValidMediaId(): void
    {
        $mediaId = 'video_12345678901234567890';
        $this->videoMessage->setMediaId($mediaId);

        $this->assertEquals($mediaId, $this->videoMessage->getMediaId());
    }

    public function testSetTitleWithValidTitle(): void
    {
        $title = '这是视频标题';
        $this->videoMessage->setTitle($title);

        $this->assertEquals($title, $this->videoMessage->getTitle());
    }

    public function testSetTitleWithNull(): void
    {
        $this->videoMessage->setTitle(null);
        $this->assertNull($this->videoMessage->getTitle());
    }

    public function testSetTitleWithMaxLength(): void
    {
        $title = str_repeat('标', 42); // 最大长度128字节，每个中文字符约3字节
        $this->videoMessage->setTitle($title);

        $this->assertEquals($title, $this->videoMessage->getTitle());
    }

    public function testSetDescriptionWithValidDescription(): void
    {
        $description = '这是视频描述内容';
        $this->videoMessage->setDescription($description);

        $this->assertEquals($description, $this->videoMessage->getDescription());
    }

    public function testSetDescriptionWithNull(): void
    {
        $this->videoMessage->setDescription(null);
        $this->assertNull($this->videoMessage->getDescription());
    }

    public function testSetDescriptionWithMaxLength(): void
    {
        $description = str_repeat('描', 170); // 最大长度512字节
        $this->videoMessage->setDescription($description);

        $this->assertEquals($description, $this->videoMessage->getDescription());
    }

    public function testSetCreateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-01 12:00:00');
        $this->videoMessage->setCreateTime($dateTime);

        $this->assertEquals($dateTime, $this->videoMessage->getCreateTime());
    }

    public function testSetUpdateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-02 15:30:00');
        $this->videoMessage->setUpdateTime($dateTime);

        $this->assertEquals($dateTime, $this->videoMessage->getUpdateTime());
    }

    public function testToRequestArrayWithBasicData(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_test123');

        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'video',
            'video' => [
                'media_id' => 'video_test123',
            ],
        ];

        $this->assertEquals($expectedArray, $this->videoMessage->toRequestArray());
    }

    public function testToRequestArrayWithTitleAndDescription(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_complete123');
        $this->videoMessage->setTitle('Test Video');
        $this->videoMessage->setDescription('This is a test video description');

        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'video',
            'video' => [
                'media_id' => 'video_complete123',
                'title' => 'Test Video',
                'description' => 'This is a test video description',
            ],
        ];

        $this->assertEquals($expectedArray, $this->videoMessage->toRequestArray());
    }

    public function testToRequestArrayWithTitleOnly(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_title123');
        $this->videoMessage->setTitle('Only Title');

        $result = $this->videoMessage->toRequestArray();

        $this->assertArrayHasKey('video', $result);
        $video = $result['video'];
        $this->assertIsArray($video);
        $this->assertEquals('Only Title', $video['title']);
        $this->assertArrayNotHasKey('description', $video);
    }

    public function testToRequestArrayWithDescriptionOnly(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_desc123');
        $this->videoMessage->setDescription('Only Description');

        $result = $this->videoMessage->toRequestArray();

        $this->assertArrayHasKey('video', $result);
        $video = $result['video'];
        $this->assertIsArray($video);
        $this->assertEquals('Only Description', $video['description']);
        $this->assertArrayNotHasKey('title', $video);
    }

    public function testToRequestArrayWithToUser(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_user123');
        $this->videoMessage->setToUser(['user1', 'user2']);

        $result = $this->videoMessage->toRequestArray();

        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function testToRequestArrayWithSafeEnabled(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_safe123');
        $this->videoMessage->setSafe(true);

        $result = $this->videoMessage->toRequestArray();

        $this->assertEquals(1, $result['safe']);
    }

    public function testToRequestArrayWithDuplicateCheckEnabled(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_duplicate123');
        $this->videoMessage->setEnableDuplicateCheck(true);
        $this->videoMessage->setDuplicateCheckInterval(3600);

        $result = $this->videoMessage->toRequestArray();

        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function testUserTrackingMethods(): void
    {
        $userId = 'user123';
        $ip = '192.168.1.1';

        $this->videoMessage->setCreatedBy($userId);
        $this->videoMessage->setUpdatedBy($userId);
        $this->videoMessage->setCreatedFromIp($ip);
        $this->videoMessage->setUpdatedFromIp($ip);

        $this->assertEquals($userId, $this->videoMessage->getCreatedBy());
        $this->assertEquals($userId, $this->videoMessage->getUpdatedBy());
        $this->assertEquals($ip, $this->videoMessage->getCreatedFromIp());
        $this->assertEquals($ip, $this->videoMessage->getUpdatedFromIp());
    }

    public function testSetMsgIdWithValidMsgId(): void
    {
        $msgId = 'msg_video_123456';
        $this->videoMessage->setMsgId($msgId);

        $this->assertEquals($msgId, $this->videoMessage->getMsgId());
    }

    public function testSetAgentWithValidAgent(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);

        $this->assertSame($this->mockAgent, $this->videoMessage->getAgent());
    }

    public function testAgentTraitMethods(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setToUser(['user1', 'user2']);
        $this->videoMessage->setToParty(['dept1']);
        $this->videoMessage->setToTag(['tag1']);

        $this->assertSame($this->mockAgent, $this->videoMessage->getAgent());
        $this->assertEquals(['user1', 'user2'], $this->videoMessage->getToUser());
        $this->assertEquals(['dept1'], $this->videoMessage->getToParty());
        $this->assertEquals(['tag1'], $this->videoMessage->getToTag());
    }

    public function testEdgeCasesEmptyStrings(): void
    {
        $this->videoMessage->setTitle('');
        $this->videoMessage->setDescription('');

        $this->assertEquals('', $this->videoMessage->getTitle());
        $this->assertEquals('', $this->videoMessage->getDescription());
    }

    public function testEdgeCasesLongMediaId(): void
    {
        $longMediaId = str_repeat('v', 99); // 不超过字段长度限制
        $this->videoMessage->setMediaId($longMediaId);

        $this->assertEquals($longMediaId, $this->videoMessage->getMediaId());
    }

    public function testToRequestArrayWithAllUsers(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_all123');
        $this->videoMessage->setToUser(['@all']);
        $this->videoMessage->setToParty(['dept1']);
        $this->videoMessage->setToTag(['tag1']);

        $result = $this->videoMessage->toRequestArray();

        $this->assertEquals('@all', $result['touser']);
        $this->assertArrayNotHasKey('toparty', $result);
        $this->assertArrayNotHasKey('totag', $result);
    }

    public function testToRequestArrayWithComplexScenario(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('complex_video_789');
        $this->videoMessage->setTitle('培训视频');
        $this->videoMessage->setDescription('2024年度安全培训视频课程');
        $this->videoMessage->setToUser(['trainee1', 'trainee2']);
        $this->videoMessage->setToParty(['training_dept']);
        $this->videoMessage->setSafe(true);
        $this->videoMessage->setEnableDuplicateCheck(true);
        $this->videoMessage->setDuplicateCheckInterval(7200);

        $result = $this->videoMessage->toRequestArray();

        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'trainee1|trainee2',
            'toparty' => 'training_dept',
            'safe' => 1,
            'enable_duplicate_check' => 1,
            'duplicate_check_interval' => 7200,
            'msgtype' => 'video',
            'video' => [
                'media_id' => 'complex_video_789',
                'title' => '培训视频',
                'description' => '2024年度安全培训视频课程',
            ],
        ];

        $this->assertEquals($expectedArray, $result);
    }
}
