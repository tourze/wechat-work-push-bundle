<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\VideoMessage;

class VideoMessageTest extends TestCase
{
    private VideoMessage $videoMessage;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->videoMessage = new VideoMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->videoMessage->getId());
    }

    public function test_getMsgType_returnsVideo(): void
    {
        $this->assertEquals('video', $this->videoMessage->getMsgType());
    }

    public function test_setMediaId_withValidMediaId(): void
    {
        $mediaId = 'video_12345678901234567890';
        $result = $this->videoMessage->setMediaId($mediaId);
        
        $this->assertSame($this->videoMessage, $result);
        $this->assertEquals($mediaId, $this->videoMessage->getMediaId());
    }

    public function test_setTitle_withValidTitle(): void
    {
        $title = '这是视频标题';
        $this->videoMessage->setTitle($title);
        
        $this->assertEquals($title, $this->videoMessage->getTitle());
    }

    public function test_setTitle_withNull(): void
    {
        $this->videoMessage->setTitle(null);
        $this->assertNull($this->videoMessage->getTitle());
    }

    public function test_setTitle_withMaxLength(): void
    {
        $title = str_repeat('标', 42); // 最大长度128字节，每个中文字符约3字节
        $this->videoMessage->setTitle($title);
        
        $this->assertEquals($title, $this->videoMessage->getTitle());
    }

    public function test_setDescription_withValidDescription(): void
    {
        $description = '这是视频描述内容';
        $this->videoMessage->setDescription($description);
        
        $this->assertEquals($description, $this->videoMessage->getDescription());
    }

    public function test_setDescription_withNull(): void
    {
        $this->videoMessage->setDescription(null);
        $this->assertNull($this->videoMessage->getDescription());
    }

    public function test_setDescription_withMaxLength(): void
    {
        $description = str_repeat('描', 170); // 最大长度512字节
        $this->videoMessage->setDescription($description);
        
        $this->assertEquals($description, $this->videoMessage->getDescription());
    }

    public function test_setCreateTime_withDateTime(): void
    {
        $dateTime = new \DateTime('2024-01-01 12:00:00');
        $this->videoMessage->setCreateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->videoMessage->getCreateTime());
    }

    public function test_setUpdateTime_withDateTime(): void
    {
        $dateTime = new \DateTime('2024-01-02 15:30:00');
        $this->videoMessage->setUpdateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->videoMessage->getUpdateTime());
    }

    public function test_toRequestArray_withBasicData(): void
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
                'media_id' => 'video_test123'
            ]
        ];
        
        $this->assertEquals($expectedArray, $this->videoMessage->toRequestArray());
    }

    public function test_toRequestArray_withTitleAndDescription(): void
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
                'description' => 'This is a test video description'
            ]
        ];
        
        $this->assertEquals($expectedArray, $this->videoMessage->toRequestArray());
    }

    public function test_toRequestArray_withTitleOnly(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_title123');
        $this->videoMessage->setTitle('Only Title');
        
        $result = $this->videoMessage->toRequestArray();
        
        $this->assertEquals('Only Title', $result['video']['title']);
        $this->assertArrayNotHasKey('description', $result['video']);
    }

    public function test_toRequestArray_withDescriptionOnly(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_desc123');
        $this->videoMessage->setDescription('Only Description');
        
        $result = $this->videoMessage->toRequestArray();
        
        $this->assertEquals('Only Description', $result['video']['description']);
        $this->assertArrayNotHasKey('title', $result['video']);
    }

    public function test_toRequestArray_withToUser(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_user123');
        $this->videoMessage->setToUser(['user1', 'user2']);
        
        $result = $this->videoMessage->toRequestArray();
        
        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function test_toRequestArray_withSafeEnabled(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_safe123');
        $this->videoMessage->setSafe(true);
        
        $result = $this->videoMessage->toRequestArray();
        
        $this->assertEquals(1, $result['safe']);
    }

    public function test_toRequestArray_withDuplicateCheckEnabled(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('video_duplicate123');
        $this->videoMessage->setEnableDuplicateCheck(true);
        $this->videoMessage->setDuplicateCheckInterval(3600);
        
        $result = $this->videoMessage->toRequestArray();
        
        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function test_userTrackingMethods(): void
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

    public function test_setMsgId_withValidMsgId(): void
    {
        $msgId = 'msg_video_123456';
        $result = $this->videoMessage->setMsgId($msgId);
        
        $this->assertSame($this->videoMessage, $result);
        $this->assertEquals($msgId, $this->videoMessage->getMsgId());
    }

    public function test_setAgent_withValidAgent(): void
    {
        $result = $this->videoMessage->setAgent($this->mockAgent);
        
        $this->assertSame($this->videoMessage, $result);
        $this->assertSame($this->mockAgent, $this->videoMessage->getAgent());
    }

    public function test_agentTraitMethods(): void
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

    public function test_edgeCases_emptyStrings(): void
    {
        $this->videoMessage->setTitle('');
        $this->videoMessage->setDescription('');
        
        $this->assertEquals('', $this->videoMessage->getTitle());
        $this->assertEquals('', $this->videoMessage->getDescription());
    }

    public function test_edgeCases_longMediaId(): void
    {
        $longMediaId = str_repeat('v', 99); // 不超过字段长度限制
        $this->videoMessage->setMediaId($longMediaId);
        
        $this->assertEquals($longMediaId, $this->videoMessage->getMediaId());
    }

    public function test_toRequestArray_withAllUsers(): void
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

    public function test_toRequestArray_withComplexScenario(): void
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
                'description' => '2024年度安全培训视频课程'
            ]
        ];
        
        $this->assertEquals($expectedArray, $result);
    }

    public function test_toRequestArray_excludesNullTitleAndDescription(): void
    {
        $this->videoMessage->setAgent($this->mockAgent);
        $this->videoMessage->setMediaId('minimal_video');
        $this->videoMessage->setTitle(null);
        $this->videoMessage->setDescription(null);
        
        $result = $this->videoMessage->toRequestArray();
        
        $this->assertArrayNotHasKey('title', $result['video']);
        $this->assertArrayNotHasKey('description', $result['video']);
        $this->assertArrayHasKey('media_id', $result['video']);
    }
} 