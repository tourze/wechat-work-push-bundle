<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\ImageMessage;

class ImageMessageTest extends TestCase
{
    private ImageMessage $imageMessage;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->imageMessage = new ImageMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->imageMessage->getId());
    }

    public function test_getMsgType_returnsImage(): void
    {
        $this->assertEquals('image', $this->imageMessage->getMsgType());
    }

    public function test_setMediaId_withValidMediaId(): void
    {
        $mediaId = 'media_12345678901234567890';
        $result = $this->imageMessage->setMediaId($mediaId);
        
        $this->assertSame($this->imageMessage, $result);
        $this->assertEquals($mediaId, $this->imageMessage->getMediaId());
    }

    public function test_setMediaId_withEmptyString(): void
    {
        $this->imageMessage->setMediaId('');
        $this->assertEquals('', $this->imageMessage->getMediaId());
    }

    public function test_setCreateTime_withDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-01 12:00:00');
        $this->imageMessage->setCreateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->imageMessage->getCreateTime());
    }

    public function test_setCreateTime_withNull(): void
    {
        $this->imageMessage->setCreateTime(null);
        $this->assertNull($this->imageMessage->getCreateTime());
    }

    public function test_setUpdateTime_withDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-02 15:30:00');
        $this->imageMessage->setUpdateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->imageMessage->getUpdateTime());
    }

    public function test_toRequestArray_withBasicData(): void
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
                'media_id' => 'media_test123'
            ]
        ];
        
        $this->assertEquals($expectedArray, $this->imageMessage->toRequestArray());
    }

    public function test_toRequestArray_withToUser(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);
        $this->imageMessage->setMediaId('media_test123');
        $this->imageMessage->setToUser(['user1', 'user2']);
        
        $result = $this->imageMessage->toRequestArray();
        
        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function test_toRequestArray_withSafeEnabled(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);
        $this->imageMessage->setMediaId('media_secret123');
        $this->imageMessage->setSafe(true);
        
        $result = $this->imageMessage->toRequestArray();
        
        $this->assertEquals(1, $result['safe']);
    }

    public function test_toRequestArray_withDuplicateCheckEnabled(): void
    {
        $this->imageMessage->setAgent($this->mockAgent);
        $this->imageMessage->setMediaId('media_duplicate123');
        $this->imageMessage->setEnableDuplicateCheck(true);
        $this->imageMessage->setDuplicateCheckInterval(3600);
        
        $result = $this->imageMessage->toRequestArray();
        
        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function test_userTrackingMethods(): void
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

    public function test_setMsgId_withValidMsgId(): void
    {
        $msgId = 'msg_image_123456';
        $result = $this->imageMessage->setMsgId($msgId);
        
        $this->assertSame($this->imageMessage, $result);
        $this->assertEquals($msgId, $this->imageMessage->getMsgId());
    }

    public function test_setAgent_withValidAgent(): void
    {
        $result = $this->imageMessage->setAgent($this->mockAgent);
        
        $this->assertSame($this->imageMessage, $result);
        $this->assertSame($this->mockAgent, $this->imageMessage->getAgent());
    }

    public function test_agentTraitMethods(): void
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

    public function test_edgeCases_longMediaId(): void
    {
        $longMediaId = str_repeat('a', 99); // 不超过字段长度限制
        $this->imageMessage->setMediaId($longMediaId);
        
        $this->assertEquals($longMediaId, $this->imageMessage->getMediaId());
    }

    public function test_edgeCases_specialCharactersInMediaId(): void
    {
        $specialMediaId = 'media_-_123_ABC_xyz';
        $this->imageMessage->setMediaId($specialMediaId);
        
        $this->assertEquals($specialMediaId, $this->imageMessage->getMediaId());
    }

    public function test_toRequestArray_withAllUsers(): void
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