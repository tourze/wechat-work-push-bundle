<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\FileMessage;

class FileMessageTest extends TestCase
{
    private FileMessage $fileMessage;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->fileMessage = new FileMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->fileMessage->getId());
    }

    public function test_getMsgType_returnsFile(): void
    {
        $this->assertEquals('file', $this->fileMessage->getMsgType());
    }

    public function test_setMediaId_withValidMediaId(): void
    {
        $mediaId = 'file_12345678901234567890';
        $result = $this->fileMessage->setMediaId($mediaId);
        
        $this->assertSame($this->fileMessage, $result);
        $this->assertEquals($mediaId, $this->fileMessage->getMediaId());
    }

    public function test_setMediaId_withEmptyString(): void
    {
        $this->fileMessage->setMediaId('');
        $this->assertEquals('', $this->fileMessage->getMediaId());
    }

    public function test_setCreateTime_withDateTime(): void
    {
        $dateTime = new \DateTime('2024-01-01 12:00:00');
        $this->fileMessage->setCreateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->fileMessage->getCreateTime());
    }

    public function test_setCreateTime_withNull(): void
    {
        $this->fileMessage->setCreateTime(null);
        $this->assertNull($this->fileMessage->getCreateTime());
    }

    public function test_setUpdateTime_withDateTime(): void
    {
        $dateTime = new \DateTime('2024-01-02 15:30:00');
        $this->fileMessage->setUpdateTime($dateTime);
        
        $this->assertEquals($dateTime, $this->fileMessage->getUpdateTime());
    }

    public function test_toRequestArray_withBasicData(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setMediaId('file_test123');
        
        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'file',
            'file' => [
                'media_id' => 'file_test123'
            ]
        ];
        
        $this->assertEquals($expectedArray, $this->fileMessage->toRequestArray());
    }

    public function test_toRequestArray_withToUser(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setMediaId('file_test123');
        $this->fileMessage->setToUser(['user1', 'user2']);
        
        $result = $this->fileMessage->toRequestArray();
        
        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function test_toRequestArray_withSafeEnabled(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setMediaId('file_secret123');
        $this->fileMessage->setSafe(true);
        
        $result = $this->fileMessage->toRequestArray();
        
        $this->assertEquals(1, $result['safe']);
    }

    public function test_toRequestArray_withDuplicateCheckEnabled(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setMediaId('file_duplicate123');
        $this->fileMessage->setEnableDuplicateCheck(true);
        $this->fileMessage->setDuplicateCheckInterval(3600);
        
        $result = $this->fileMessage->toRequestArray();
        
        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function test_userTrackingMethods(): void
    {
        $userId = 'user123';
        $ip = '192.168.1.1';
        
        $this->fileMessage->setCreatedBy($userId);
        $this->fileMessage->setUpdatedBy($userId);
        $this->fileMessage->setCreatedFromIp($ip);
        $this->fileMessage->setUpdatedFromIp($ip);
        
        $this->assertEquals($userId, $this->fileMessage->getCreatedBy());
        $this->assertEquals($userId, $this->fileMessage->getUpdatedBy());
        $this->assertEquals($ip, $this->fileMessage->getCreatedFromIp());
        $this->assertEquals($ip, $this->fileMessage->getUpdatedFromIp());
    }

    public function test_setMsgId_withValidMsgId(): void
    {
        $msgId = 'msg_file_123456';
        $result = $this->fileMessage->setMsgId($msgId);
        
        $this->assertSame($this->fileMessage, $result);
        $this->assertEquals($msgId, $this->fileMessage->getMsgId());
    }

    public function test_setAgent_withValidAgent(): void
    {
        $result = $this->fileMessage->setAgent($this->mockAgent);
        
        $this->assertSame($this->fileMessage, $result);
        $this->assertSame($this->mockAgent, $this->fileMessage->getAgent());
    }

    public function test_agentTraitMethods(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setToUser(['user1', 'user2']);
        $this->fileMessage->setToParty(['dept1']);
        $this->fileMessage->setToTag(['tag1']);
        
        $this->assertSame($this->mockAgent, $this->fileMessage->getAgent());
        $this->assertEquals(['user1', 'user2'], $this->fileMessage->getToUser());
        $this->assertEquals(['dept1'], $this->fileMessage->getToParty());
        $this->assertEquals(['tag1'], $this->fileMessage->getToTag());
    }

    public function test_edgeCases_longMediaId(): void
    {
        $longMediaId = str_repeat('f', 99); // 不超过字段长度限制
        $this->fileMessage->setMediaId($longMediaId);
        
        $this->assertEquals($longMediaId, $this->fileMessage->getMediaId());
    }

    public function test_edgeCases_specialCharactersInMediaId(): void
    {
        $specialMediaId = 'file_-_123_ABC_xyz.pdf';
        $this->fileMessage->setMediaId($specialMediaId);
        
        $this->assertEquals($specialMediaId, $this->fileMessage->getMediaId());
    }

    public function test_toRequestArray_withAllUsers(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setMediaId('file_all123');
        $this->fileMessage->setToUser(['@all']);
        $this->fileMessage->setToParty(['dept1']);
        $this->fileMessage->setToTag(['tag1']);
        
        $result = $this->fileMessage->toRequestArray();
        
        $this->assertEquals('@all', $result['touser']);
        $this->assertArrayNotHasKey('toparty', $result);
        $this->assertArrayNotHasKey('totag', $result);
    }

    public function test_fileSpecificMediaIds(): void
    {
        // 测试不同文件类型的 media ID
        $pdfMediaId = 'file_pdf_123456789.pdf';
        $this->fileMessage->setMediaId($pdfMediaId);
        $this->assertEquals($pdfMediaId, $this->fileMessage->getMediaId());
        
        $docMediaId = 'file_doc_987654321.docx';
        $this->fileMessage->setMediaId($docMediaId);
        $this->assertEquals($docMediaId, $this->fileMessage->getMediaId());
        
        $xlsMediaId = 'file_xls_555666777.xlsx';
        $this->fileMessage->setMediaId($xlsMediaId);
        $this->assertEquals($xlsMediaId, $this->fileMessage->getMediaId());
    }

    public function test_toRequestArray_withComplexScenario(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setMediaId('complex_file_789');
        $this->fileMessage->setToUser(['manager1', 'employee1']);
        $this->fileMessage->setToParty(['hr_dept']);
        $this->fileMessage->setSafe(true);
        $this->fileMessage->setEnableDuplicateCheck(true);
        $this->fileMessage->setDuplicateCheckInterval(7200);
        
        $result = $this->fileMessage->toRequestArray();
        
        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'manager1|employee1',
            'toparty' => 'hr_dept',
            'safe' => 1,
            'enable_duplicate_check' => 1,
            'duplicate_check_interval' => 7200,
            'msgtype' => 'file',
            'file' => [
                'media_id' => 'complex_file_789'
            ]
        ];
        
        $this->assertEquals($expectedArray, $result);
    }
} 