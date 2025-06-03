<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\VoiceMessage;

class VoiceMessageTest extends TestCase
{
    private VoiceMessage $voiceMessage;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->voiceMessage = new VoiceMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->voiceMessage->getId());
    }

    public function test_getMsgType_returnsVoice(): void
    {
        $this->assertEquals('voice', $this->voiceMessage->getMsgType());
    }

    public function test_setMediaId_withValidMediaId(): void
    {
        $mediaId = 'voice_12345678901234567890';
        $result = $this->voiceMessage->setMediaId($mediaId);
        
        $this->assertSame($this->voiceMessage, $result);
        $this->assertEquals($mediaId, $this->voiceMessage->getMediaId());
    }

    public function test_setMediaId_withEmptyString(): void
    {
        $this->voiceMessage->setMediaId('');
        $this->assertEquals('', $this->voiceMessage->getMediaId());
    }

    public function test_setCreateTime_withDateTime(): void
    {
        $dateTime = new \DateTime('2024-01-01 12:00:00');
        $this->voiceMessage->setCreateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->voiceMessage->getCreateTime());
    }

    public function test_setCreateTime_withNull(): void
    {
        $this->voiceMessage->setCreateTime(null);
        $this->assertNull($this->voiceMessage->getCreateTime());
    }

    public function test_setUpdateTime_withDateTime(): void
    {
        $dateTime = new \DateTime('2024-01-02 15:30:00');
        $this->voiceMessage->setUpdateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->voiceMessage->getUpdateTime());
    }

    public function test_toRequestArray_withBasicData(): void
    {
        $this->voiceMessage->setAgent($this->mockAgent);
        $this->voiceMessage->setMediaId('voice_test123');
        
        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'voice',
            'voice' => [
                'media_id' => 'voice_test123'
            ]
        ];
        
        $this->assertEquals($expectedArray, $this->voiceMessage->toRequestArray());
    }

    public function test_toRequestArray_withToUser(): void
    {
        $this->voiceMessage->setAgent($this->mockAgent);
        $this->voiceMessage->setMediaId('voice_test123');
        $this->voiceMessage->setToUser(['user1', 'user2']);
        
        $result = $this->voiceMessage->toRequestArray();
        
        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function test_toRequestArray_withSafeEnabled(): void
    {
        $this->voiceMessage->setAgent($this->mockAgent);
        $this->voiceMessage->setMediaId('voice_secret123');
        $this->voiceMessage->setSafe(true);
        
        $result = $this->voiceMessage->toRequestArray();
        
        $this->assertEquals(1, $result['safe']);
    }

    public function test_toRequestArray_withDuplicateCheckEnabled(): void
    {
        $this->voiceMessage->setAgent($this->mockAgent);
        $this->voiceMessage->setMediaId('voice_duplicate123');
        $this->voiceMessage->setEnableDuplicateCheck(true);
        $this->voiceMessage->setDuplicateCheckInterval(3600);
        
        $result = $this->voiceMessage->toRequestArray();
        
        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function test_userTrackingMethods(): void
    {
        $userId = 'user123';
        $ip = '192.168.1.1';
        
        $this->voiceMessage->setCreatedBy($userId);
        $this->voiceMessage->setUpdatedBy($userId);
        $this->voiceMessage->setCreatedFromIp($ip);
        $this->voiceMessage->setUpdatedFromIp($ip);
        
        $this->assertEquals($userId, $this->voiceMessage->getCreatedBy());
        $this->assertEquals($userId, $this->voiceMessage->getUpdatedBy());
        $this->assertEquals($ip, $this->voiceMessage->getCreatedFromIp());
        $this->assertEquals($ip, $this->voiceMessage->getUpdatedFromIp());
    }

    public function test_setMsgId_withValidMsgId(): void
    {
        $msgId = 'msg_voice_123456';
        $result = $this->voiceMessage->setMsgId($msgId);
        
        $this->assertSame($this->voiceMessage, $result);
        $this->assertEquals($msgId, $this->voiceMessage->getMsgId());
    }

    public function test_setAgent_withValidAgent(): void
    {
        $result = $this->voiceMessage->setAgent($this->mockAgent);
        
        $this->assertSame($this->voiceMessage, $result);
        $this->assertSame($this->mockAgent, $this->voiceMessage->getAgent());
    }

    public function test_agentTraitMethods(): void
    {
        $this->voiceMessage->setAgent($this->mockAgent);
        $this->voiceMessage->setToUser(['user1', 'user2']);
        $this->voiceMessage->setToParty(['dept1']);
        $this->voiceMessage->setToTag(['tag1']);
        
        $this->assertSame($this->mockAgent, $this->voiceMessage->getAgent());
        $this->assertEquals(['user1', 'user2'], $this->voiceMessage->getToUser());
        $this->assertEquals(['dept1'], $this->voiceMessage->getToParty());
        $this->assertEquals(['tag1'], $this->voiceMessage->getToTag());
    }

    public function test_edgeCases_longMediaId(): void
    {
        $longMediaId = str_repeat('v', 99); // 不超过字段长度限制
        $this->voiceMessage->setMediaId($longMediaId);
        
        $this->assertEquals($longMediaId, $this->voiceMessage->getMediaId());
    }

    public function test_edgeCases_specialCharactersInMediaId(): void
    {
        $specialMediaId = 'voice_-_123_ABC_xyz.mp3';
        $this->voiceMessage->setMediaId($specialMediaId);
        
        $this->assertEquals($specialMediaId, $this->voiceMessage->getMediaId());
    }

    public function test_toRequestArray_withAllUsers(): void
    {
        $this->voiceMessage->setAgent($this->mockAgent);
        $this->voiceMessage->setMediaId('voice_all123');
        $this->voiceMessage->setToUser(['@all']);
        $this->voiceMessage->setToParty(['dept1']);
        $this->voiceMessage->setToTag(['tag1']);
        
        $result = $this->voiceMessage->toRequestArray();
        
        $this->assertEquals('@all', $result['touser']);
        $this->assertArrayNotHasKey('toparty', $result);
        $this->assertArrayNotHasKey('totag', $result);
    }

    public function test_voiceSpecificMediaIds(): void
    {
        // 测试不同音频格式的 media ID
        $mp3MediaId = 'voice_mp3_123456789.mp3';
        $this->voiceMessage->setMediaId($mp3MediaId);
        $this->assertEquals($mp3MediaId, $this->voiceMessage->getMediaId());
        
        $amrMediaId = 'voice_amr_987654321.amr';
        $this->voiceMessage->setMediaId($amrMediaId);
        $this->assertEquals($amrMediaId, $this->voiceMessage->getMediaId());
    }

    public function test_toRequestArray_withComplexScenario(): void
    {
        $this->voiceMessage->setAgent($this->mockAgent);
        $this->voiceMessage->setMediaId('complex_voice_789');
        $this->voiceMessage->setToUser(['manager1', 'employee1']);
        $this->voiceMessage->setToParty(['hr_dept']);
        $this->voiceMessage->setSafe(true);
        $this->voiceMessage->setEnableDuplicateCheck(true);
        $this->voiceMessage->setDuplicateCheckInterval(7200);
        
        $result = $this->voiceMessage->toRequestArray();
        
        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'manager1|employee1',
            'toparty' => 'hr_dept',
            'safe' => 1,
            'enable_duplicate_check' => 1,
            'duplicate_check_interval' => 7200,
            'msgtype' => 'voice',
            'voice' => [
                'media_id' => 'complex_voice_789'
            ]
        ];
        
        $this->assertEquals($expectedArray, $result);
    }
} 