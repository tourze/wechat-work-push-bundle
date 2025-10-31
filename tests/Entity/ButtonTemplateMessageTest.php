<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\ButtonTemplateMessage;

/**
 * @internal
 */
#[CoversClass(ButtonTemplateMessage::class)]
final class ButtonTemplateMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ButtonTemplateMessage();
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
            'msgId' => ['msgId', 'MSG_20240901_004'],
            'toUser' => ['toUser', ['zhangsan', 'lisi']],
            'toParty' => ['toParty', ['1', '2']],
            'toTag' => ['toTag', ['开发组', '产品组']],
            'safe' => ['safe', true],
            'enableDuplicateCheck' => ['enableDuplicateCheck', false],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 3600],

            // TemplateCardMessage基类属性
            'title' => ['title', '新功能发布通知'],
            'description' => ['description', '我们发布了重要的新功能，点击查看详情'],
            'taskId' => ['taskId', 'TASK_FEATURE_001'],

            // ButtonTemplateMessage自身属性
            'url' => ['url', 'https://example.com/features/new-release'],
            'buttonText' => ['buttonText', '查看新功能'],
            'buttonKey' => ['buttonKey', 'view_feature_detail'],
        ];
    }

    private ButtonTemplateMessage $buttonTemplate;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->buttonTemplate = new ButtonTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->buttonTemplate->getId());
    }

    public function testSetUrlAndGetUrl(): void
    {
        $url = 'https://example.com/detail';
        $this->buttonTemplate->setUrl($url);

        $this->assertEquals($url, $this->buttonTemplate->getUrl());
    }

    public function testSetButtonTextAndGetButtonText(): void
    {
        $buttonText = '查看详情';
        $this->buttonTemplate->setButtonText($buttonText);

        $this->assertEquals($buttonText, $this->buttonTemplate->getButtonText());
    }

    public function testSetButtonKeyAndGetButtonKey(): void
    {
        $buttonKey = 'action_detail';
        $this->buttonTemplate->setButtonKey($buttonKey);

        $this->assertEquals($buttonKey, $this->buttonTemplate->getButtonKey());
    }

    public function testSetButtonKeyWithNull(): void
    {
        $this->buttonTemplate->setButtonKey(null);
        $this->assertNull($this->buttonTemplate->getButtonKey());
    }

    public function testToRequestArrayWithBasicData(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('测试描述');
        $this->buttonTemplate->setUrl('https://example.com');
        $this->buttonTemplate->setButtonText('点击按钮');

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayHasKey('template_card', $result);
        $this->assertEquals('template_card', $result['msgtype']);
        $this->assertIsArray($result['template_card']);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertEquals('button_interaction', $templateCard['card_type']);

        // 检查按钮配置
        $this->assertArrayHasKey('button_list', $templateCard);
        $buttonList = $templateCard['button_list'];
        $this->assertIsArray($buttonList);
        $this->assertArrayHasKey(0, $buttonList);
        $firstButton = $buttonList[0];
        $this->assertIsArray($firstButton);
        $this->assertEquals('点击按钮', $firstButton['text']);
        $this->assertEquals('https://example.com', $firstButton['url']);
    }

    public function testToRequestArrayWithButtonKey(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('测试描述');
        $this->buttonTemplate->setUrl('https://example.com');
        $this->buttonTemplate->setButtonText('点击按钮');
        $this->buttonTemplate->setButtonKey('test_key');

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertArrayHasKey('button_list', $templateCard);
        $buttonList = $templateCard['button_list'];
        $this->assertIsArray($buttonList);
        $this->assertArrayHasKey(0, $buttonList);
        $firstButton = $buttonList[0];
        $this->assertIsArray($firstButton);
        $this->assertArrayHasKey('key', $firstButton);
        $this->assertEquals('test_key', $firstButton['key']);
    }

    public function testToRequestArrayWithoutButtonKey(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('测试描述');
        $this->buttonTemplate->setUrl('https://example.com');
        $this->buttonTemplate->setButtonText('点击按钮');
        $this->buttonTemplate->setButtonKey(null);

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertArrayHasKey('button_list', $templateCard);
        $buttonList = $templateCard['button_list'];
        $this->assertIsArray($buttonList);
        $this->assertArrayHasKey(0, $buttonList);
        $firstButton = $buttonList[0];
        $this->assertIsArray($firstButton);
        $this->assertArrayNotHasKey('key', $firstButton);
    }

    public function testRetrieveAdminArrayIncludesButtonFields(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('测试描述');
        $this->buttonTemplate->setUrl('https://example.com');
        $this->buttonTemplate->setButtonText('点击按钮');
        $this->buttonTemplate->setButtonKey('test_key');

        $result = $this->buttonTemplate->retrieveAdminArray();

        $this->assertArrayHasKey('url', $result);
        $this->assertArrayHasKey('buttonText', $result);
        $this->assertArrayHasKey('buttonKey', $result);
        $this->assertEquals('https://example.com', $result['url']);
        $this->assertEquals('点击按钮', $result['buttonText']);
        $this->assertEquals('test_key', $result['buttonKey']);
    }

    public function testToStringReturnsStringId(): void
    {
        $result = $this->buttonTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function testCardActionIsCorrectlySet(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('测试描述');
        $this->buttonTemplate->setUrl('https://example.com/action');
        $this->buttonTemplate->setButtonText('操作按钮');

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertArrayHasKey('card_action', $templateCard);
        $cardAction = $templateCard['card_action'];
        $this->assertIsArray($cardAction);
        $this->assertEquals(1, $cardAction['type']);
        $this->assertEquals('https://example.com/action', $cardAction['url']);
    }

    public function testHorizontalContentListContainsDescription(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('详细描述内容');
        $this->buttonTemplate->setUrl('https://example.com');
        $this->buttonTemplate->setButtonText('按钮');

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertArrayHasKey('horizontal_content_list', $templateCard);
        $contentList = $templateCard['horizontal_content_list'];
        $this->assertIsArray($contentList);
        $this->assertArrayHasKey(0, $contentList);
        $firstContent = $contentList[0];
        $this->assertIsArray($firstContent);
        $this->assertEquals('详细内容', $firstContent['keyname']);
        $this->assertEquals('详细描述内容', $firstContent['value']);
    }
}
