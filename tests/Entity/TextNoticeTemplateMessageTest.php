<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\TextNoticeTemplateMessage;

/**
 * @internal
 */
#[CoversClass(TextNoticeTemplateMessage::class)]
final class TextNoticeTemplateMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new TextNoticeTemplateMessage();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        $now = new \DateTimeImmutable();

        return [
            // Traits属性
            'createTime' => ['createTime', $now],
            'updateTime' => ['updateTime', $now],
            'createdBy' => ['createdBy', '管理员'],
            'updatedBy' => ['updatedBy', '测试用户'],
            'createdFromIp' => ['createdFromIp', '192.168.1.100'],
            'updatedFromIp' => ['updatedFromIp', '10.0.0.1'],
            'msgId' => ['msgId', 'MSG_20240901_006'],
            'toUser' => ['toUser', ['zhangsan', 'lisi']],
            'toParty' => ['toParty', ['1', '2']],
            'toTag' => ['toTag', ['开发组', '产品组']],
            'safe' => ['safe', true],
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 5400],

            // TemplateCardMessage基类属性
            'title' => ['title', '系统维护通知'],
            'description' => ['description', '系统将于今晚22:00-24:00进行维护升级，请提前做好数据备份'],
            'taskId' => ['taskId', 'TASK_MAINTENANCE_001'],

            // TextNoticeTemplateMessage自身属性
            'url' => ['url', 'https://company.com/notice/system-maintenance-2024'],
            'btnText' => ['btnText', '查看维护详情'],
        ];
    }

    private TextNoticeTemplateMessage $textNoticeTemplate;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->textNoticeTemplate = new TextNoticeTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->textNoticeTemplate->getId());
    }

    public function testToStringReturnsStringId(): void
    {
        $this->textNoticeTemplate->setDescription('描述内容');
        $this->textNoticeTemplate->setUrl('https://example.com');
        $result = $this->textNoticeTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function testGetCardTypeReturnsTextNotification(): void
    {
        // 测试公共API行为而不是私有方法
        $this->textNoticeTemplate->setAgent($this->mockAgent);
        $this->textNoticeTemplate->setTitle('Test Title');
        $this->textNoticeTemplate->setDescription('Test Description');
        $this->textNoticeTemplate->setUrl('https://example.com/notice');
        $result = $this->textNoticeTemplate->toRequestArray();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertArrayHasKey('card_type', $templateCard);
        $this->assertEquals('text_notice', $templateCard['card_type']);
    }

    public function testSetTitleAndGetTitle(): void
    {
        $title = '文本通知卡片';
        $this->textNoticeTemplate->setTitle($title);

        $this->assertEquals($title, $this->textNoticeTemplate->getTitle());
    }

    public function testSetDescriptionAndGetDescription(): void
    {
        $description = '这是文本通知卡片描述';
        $this->textNoticeTemplate->setDescription($description);

        $this->assertEquals($description, $this->textNoticeTemplate->getDescription());
    }

    public function testToRequestArrayReturnsTemplateCardType(): void
    {
        $this->textNoticeTemplate->setAgent($this->mockAgent);
        $this->textNoticeTemplate->setTitle('测试文本通知');
        $this->textNoticeTemplate->setDescription('测试描述');
        $this->textNoticeTemplate->setUrl('https://example.com/text-notice');

        $result = $this->textNoticeTemplate->toRequestArray();

        $this->assertEquals('template_card', $result['msgtype']);
        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertEquals('text_notice', $templateCard['card_type']);
    }

    public function testRetrieveAdminArrayIncludesBaseFields(): void
    {
        $this->textNoticeTemplate->setAgent($this->mockAgent);
        $this->textNoticeTemplate->setTitle('管理文本通知');
        $this->textNoticeTemplate->setDescription('管理描述');
        $this->textNoticeTemplate->setUrl('https://example.com/admin-text');

        $result = $this->textNoticeTemplate->retrieveAdminArray();

        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertEquals('管理文本通知', $result['title']);
        $this->assertEquals('管理描述', $result['description']);
    }
}
