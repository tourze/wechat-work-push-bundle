<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\FileMessage;

/**
 * @internal
 */
#[CoversClass(FileMessage::class)]
final class FileMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new FileMessage();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            // 主要属性
            'mediaId' => ['mediaId', 'file_media_20240115_quarterly_report.pdf'],

            // TimestampableAware traits
            'createTime' => ['createTime', new \DateTimeImmutable('2024-01-15 11:30:00')],
            'updateTime' => ['updateTime', new \DateTimeImmutable('2024-01-15 11:45:00')],

            // BlameableAware traits
            'createdBy' => ['createdBy', 'finance_analyst_001'],
            'updatedBy' => ['updatedBy', 'document_manager_002'],

            // IpTraceableAware traits
            'createdFromIp' => ['createdFromIp', '172.20.1.15'],
            'updatedFromIp' => ['updatedFromIp', '10.10.10.40'],

            // AgentTrait properties
            'msgId' => ['msgId', 'msg_file_20240115_113000_001'],
            'toUser' => ['toUser', ['executives', 'department_managers']],
            'toParty' => ['toParty', ['finance_dept', 'executive_office']],
            'toTag' => ['toTag', ['quarterly_reports', 'confidential_docs']],

            // SafeTrait properties
            'safe' => ['safe', true],

            // DuplicateCheckTrait properties
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 5400],
        ];
    }

    private FileMessage $fileMessage;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileMessage = new FileMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->fileMessage->getId());
    }

    public function testGetMsgTypeReturnsFile(): void
    {
        $this->assertEquals('file', $this->fileMessage->getMsgType());
    }

    public function testSetMediaIdWithValidMediaId(): void
    {
        $mediaId = 'file_12345678901234567890';
        $this->fileMessage->setMediaId($mediaId);

        $this->assertEquals($mediaId, $this->fileMessage->getMediaId());
    }

    public function testSetMediaIdWithEmptyString(): void
    {
        $this->fileMessage->setMediaId('');
        $this->assertEquals('', $this->fileMessage->getMediaId());
    }

    public function testSetCreateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-01 12:00:00');
        $this->fileMessage->setCreateTime($dateTime);

        $this->assertEquals($dateTime, $this->fileMessage->getCreateTime());
    }

    public function testSetCreateTimeWithNull(): void
    {
        $this->fileMessage->setCreateTime(null);
        $this->assertNull($this->fileMessage->getCreateTime());
    }

    public function testSetUpdateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-02 15:30:00');
        $this->fileMessage->setUpdateTime($dateTime);

        $this->assertEquals($dateTime, $this->fileMessage->getUpdateTime());
    }

    public function testToRequestArrayWithBasicData(): void
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
                'media_id' => 'file_test123',
            ],
            'enable_id_trans' => 0,
        ];

        $this->assertEquals($expectedArray, $this->fileMessage->toRequestArray());
    }

    public function testToRequestArrayWithToUser(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setMediaId('file_test123');
        $this->fileMessage->setToUser(['user1', 'user2']);

        $result = $this->fileMessage->toRequestArray();

        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function testToRequestArrayWithSafeEnabled(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setMediaId('file_secret123');
        $this->fileMessage->setSafe(true);

        $result = $this->fileMessage->toRequestArray();

        $this->assertEquals(1, $result['safe']);
    }

    public function testToRequestArrayWithDuplicateCheckEnabled(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);
        $this->fileMessage->setMediaId('file_duplicate123');
        $this->fileMessage->setEnableDuplicateCheck(true);
        $this->fileMessage->setDuplicateCheckInterval(3600);

        $result = $this->fileMessage->toRequestArray();

        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function testUserTrackingMethods(): void
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

    public function testSetMsgIdWithValidMsgId(): void
    {
        $msgId = 'msg_file_123456';
        $this->fileMessage->setMsgId($msgId);

        $this->assertEquals($msgId, $this->fileMessage->getMsgId());
    }

    public function testSetAgentWithValidAgent(): void
    {
        $this->fileMessage->setAgent($this->mockAgent);

        $this->assertSame($this->mockAgent, $this->fileMessage->getAgent());
    }

    public function testAgentTraitMethods(): void
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

    public function testEdgeCasesLongMediaId(): void
    {
        $longMediaId = str_repeat('f', 99); // 不超过字段长度限制
        $this->fileMessage->setMediaId($longMediaId);

        $this->assertEquals($longMediaId, $this->fileMessage->getMediaId());
    }

    public function testEdgeCasesSpecialCharactersInMediaId(): void
    {
        $specialMediaId = 'file_-_123_ABC_xyz.pdf';
        $this->fileMessage->setMediaId($specialMediaId);

        $this->assertEquals($specialMediaId, $this->fileMessage->getMediaId());
    }

    public function testToRequestArrayWithAllUsers(): void
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

    public function testFileSpecificMediaIds(): void
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

    public function testToRequestArrayWithComplexScenario(): void
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
                'media_id' => 'complex_file_789',
            ],
            'enable_id_trans' => 0,
        ];

        $this->assertEquals($expectedArray, $result);
    }
}
