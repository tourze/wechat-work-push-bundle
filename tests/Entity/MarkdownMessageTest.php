<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\MarkdownMessage;

/**
 * @internal
 */
#[CoversClass(MarkdownMessage::class)]
final class MarkdownMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new MarkdownMessage();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            // 主要属性
            'content' => ['content', "# 项目状态更新\n\n## 本周完成\n- **功能开发**: 完成用户认证模块\n- **测试**: 单元测试覆盖率达到90%\n\n## 下周计划\n- [ ] 代码审查\n- [ ] 性能优化\n\n*感谢团队的努力工作！*"],

            // TimestampableAware traits
            'createTime' => ['createTime', new \DateTimeImmutable('2024-01-15 15:00:00')],
            'updateTime' => ['updateTime', new \DateTimeImmutable('2024-01-15 15:30:00')],

            // BlameableAware traits
            'createdBy' => ['createdBy', 'project_manager_001'],
            'updatedBy' => ['updatedBy', 'tech_lead_002'],

            // IpTraceableAware traits
            'createdFromIp' => ['createdFromIp', '192.168.200.10'],
            'updatedFromIp' => ['updatedFromIp', '172.30.1.20'],

            // AgentTrait properties
            'msgId' => ['msgId', 'msg_markdown_20240115_150000_001'],
            'toUser' => ['toUser', ['dev_team', 'qa_team', 'product_owner']],
            'toParty' => ['toParty', ['development_dept', 'product_dept']],
            'toTag' => ['toTag', ['project_updates', 'weekly_status']],

            // DuplicateCheckTrait properties (no SafeTrait for MarkdownMessage)
            'enableDuplicateCheck' => ['enableDuplicateCheck', false],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 7200],
        ];
    }

    private MarkdownMessage $markdownMessage;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->markdownMessage = new MarkdownMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->markdownMessage->getId());
    }

    public function testGetMsgTypeReturnsMarkdown(): void
    {
        $this->assertEquals('markdown', $this->markdownMessage->getMsgType());
    }

    public function testSetContentAndGetContent(): void
    {
        $content = '# 标题\n这是**粗体**文本';
        $this->markdownMessage->setContent($content);

        $this->assertEquals($content, $this->markdownMessage->getContent());
    }

    public function testSetContentWithMaxLength(): void
    {
        $content = str_repeat('a', 2048);
        $this->markdownMessage->setContent($content);

        $this->assertEquals($content, $this->markdownMessage->getContent());
    }

    public function testSetCreatedByAndGetCreatedBy(): void
    {
        $userId = 'user123';
        $this->markdownMessage->setCreatedBy($userId);

        $this->assertEquals($userId, $this->markdownMessage->getCreatedBy());
    }

    public function testSetCreatedFromIpAndGetCreatedFromIp(): void
    {
        $ip = '192.168.1.1';
        $this->markdownMessage->setCreatedFromIp($ip);

        $this->assertEquals($ip, $this->markdownMessage->getCreatedFromIp());
    }

    public function testSetAgentAndGetAgent(): void
    {
        $this->markdownMessage->setAgent($this->mockAgent);

        $this->assertSame($this->mockAgent, $this->markdownMessage->getAgent());
    }

    public function testToRequestArrayWithBasicData(): void
    {
        $this->markdownMessage->setAgent($this->mockAgent);
        $this->markdownMessage->setContent('# 测试标题\n这是**测试**内容');
        $this->markdownMessage->setToUser(['user1', 'user2']);

        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1|user2',
            'enable_id_trans' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'markdown',
            'markdown' => [
                'content' => '# 测试标题\n这是**测试**内容',
            ],
        ];

        $this->assertEquals($expectedArray, $this->markdownMessage->toRequestArray());
    }

    public function testToRequestArrayWithAllUsers(): void
    {
        $this->markdownMessage->setAgent($this->mockAgent);
        $this->markdownMessage->setContent('通知所有人');
        $this->markdownMessage->setToUser(['@all']);

        $result = $this->markdownMessage->toRequestArray();

        $this->assertEquals('@all', $result['touser']);
        $this->assertArrayNotHasKey('toparty', $result);
        $this->assertArrayNotHasKey('totag', $result);
    }

    public function testToRequestArrayWithDuplicateCheckEnabled(): void
    {
        $this->markdownMessage->setAgent($this->mockAgent);
        $this->markdownMessage->setContent('重复检查消息');
        $this->markdownMessage->setEnableDuplicateCheck(true);
        $this->markdownMessage->setDuplicateCheckInterval(3600);

        $result = $this->markdownMessage->toRequestArray();

        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }

    public function testSetMsgIdAndGetMsgId(): void
    {
        $msgId = 'msg_markdown_123';
        $this->markdownMessage->setMsgId($msgId);

        $this->assertEquals($msgId, $this->markdownMessage->getMsgId());
    }

    public function testToStringReturnsStringId(): void
    {
        $result = $this->markdownMessage->__toString();
        $this->assertNotNull($result);
    }
}
