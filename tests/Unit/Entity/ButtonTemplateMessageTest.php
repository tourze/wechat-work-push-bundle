<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\ButtonTemplateMessage;

class ButtonTemplateMessageTest extends TestCase
{
    private ButtonTemplateMessage $buttonTemplate;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->buttonTemplate = new ButtonTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->buttonTemplate->getId());
    }

    public function test_setUrl_andGetUrl(): void
    {
        $url = 'https://example.com/detail';
        $result = $this->buttonTemplate->setUrl($url);

        $this->assertSame($this->buttonTemplate, $result);
        $this->assertEquals($url, $this->buttonTemplate->getUrl());
    }

    public function test_setButtonText_andGetButtonText(): void
    {
        $buttonText = '查看详情';
        $result = $this->buttonTemplate->setButtonText($buttonText);

        $this->assertSame($this->buttonTemplate, $result);
        $this->assertEquals($buttonText, $this->buttonTemplate->getButtonText());
    }

    public function test_setButtonKey_andGetButtonKey(): void
    {
        $buttonKey = 'action_detail';
        $result = $this->buttonTemplate->setButtonKey($buttonKey);

        $this->assertSame($this->buttonTemplate, $result);
        $this->assertEquals($buttonKey, $this->buttonTemplate->getButtonKey());
    }

    public function test_setButtonKey_withNull(): void
    {
        $this->buttonTemplate->setButtonKey(null);
        $this->assertNull($this->buttonTemplate->getButtonKey());
    }

    public function test_toRequestArray_withBasicData(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('测试描述');
        $this->buttonTemplate->setUrl('https://example.com');
        $this->buttonTemplate->setButtonText('点击按钮');

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayHasKey('template_card', $result);
        $this->assertEquals('template_card', $result['msgtype']);
        $this->assertEquals('button_interaction', $result['template_card']['card_type']);
        
        // 检查按钮配置
        $this->assertArrayHasKey('button_list', $result['template_card']);
        $this->assertEquals('点击按钮', $result['template_card']['button_list'][0]['text']);
        $this->assertEquals('https://example.com', $result['template_card']['button_list'][0]['url']);
    }

    public function test_toRequestArray_withButtonKey(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('测试描述');
        $this->buttonTemplate->setUrl('https://example.com');
        $this->buttonTemplate->setButtonText('点击按钮');
        $this->buttonTemplate->setButtonKey('test_key');

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayHasKey('key', $result['template_card']['button_list'][0]);
        $this->assertEquals('test_key', $result['template_card']['button_list'][0]['key']);
    }

    public function test_toRequestArray_withoutButtonKey(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('测试描述');
        $this->buttonTemplate->setUrl('https://example.com');
        $this->buttonTemplate->setButtonText('点击按钮');
        $this->buttonTemplate->setButtonKey(null);

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayNotHasKey('key', $result['template_card']['button_list'][0]);
    }

    public function test_retrieveAdminArray_includesButtonFields(): void
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

    public function test_toString_returnsStringId(): void
    {
        $result = $this->buttonTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function test_cardAction_isCorrectlySet(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('测试描述');
        $this->buttonTemplate->setUrl('https://example.com/action');
        $this->buttonTemplate->setButtonText('操作按钮');

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayHasKey('card_action', $result['template_card']);
        $this->assertEquals(1, $result['template_card']['card_action']['type']);
        $this->assertEquals('https://example.com/action', $result['template_card']['card_action']['url']);
    }

    public function test_horizontalContentList_containsDescription(): void
    {
        $this->buttonTemplate->setAgent($this->mockAgent);
        $this->buttonTemplate->setTitle('测试标题');
        $this->buttonTemplate->setDescription('详细描述内容');
        $this->buttonTemplate->setUrl('https://example.com');
        $this->buttonTemplate->setButtonText('按钮');

        $result = $this->buttonTemplate->toRequestArray();

        $this->assertArrayHasKey('horizontal_content_list', $result['template_card']);
        $contentList = $result['template_card']['horizontal_content_list'];
        $this->assertEquals('详细内容', $contentList[0]['keyname']);
        $this->assertEquals('详细描述内容', $contentList[0]['value']);
    }
}