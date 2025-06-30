<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\TextCardMessage;

class TextCardMessageTest extends TestCase
{
    private TextCardMessage $textCardMessage;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->textCardMessage = new TextCardMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->textCardMessage->getId());
    }

    public function test_getMsgType_returnsTextcard(): void
    {
        $this->assertEquals('textcard', $this->textCardMessage->getMsgType());
    }

    public function test_setTitle_andGetTitle(): void
    {
        $title = '通知标题';
        $this->textCardMessage->setTitle($title);

        $this->assertEquals($title, $this->textCardMessage->getTitle());
    }

    public function test_setDescription_andGetDescription(): void
    {
        $description = '这是通知的详细描述';
        $this->textCardMessage->setDescription($description);

        $this->assertEquals($description, $this->textCardMessage->getDescription());
    }

    public function test_setUrl_andGetUrl(): void
    {
        $url = 'https://example.com';
        $this->textCardMessage->setUrl($url);

        $this->assertEquals($url, $this->textCardMessage->getUrl());
    }

    public function test_setBtnText_andGetBtnText(): void
    {
        $btnText = '查看详情';
        $this->textCardMessage->setBtnText($btnText);

        $this->assertEquals($btnText, $this->textCardMessage->getBtnText());
    }

    public function test_toRequestArray_withBasicData(): void
    {
        $this->textCardMessage->setAgent($this->mockAgent);
        $this->textCardMessage->setTitle('测试标题');
        $this->textCardMessage->setDescription('测试描述');
        $this->textCardMessage->setUrl('https://example.com');
        $this->textCardMessage->setBtnText('点击查看');

        $expectedArray = [
            'agentid' => '1000002',
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'textcard',
            'textcard' => [
                'title' => '测试标题',
                'description' => '测试描述',
                'url' => 'https://example.com',
                'btntxt' => '点击查看'
            ]
        ];

        $this->assertEquals($expectedArray, $this->textCardMessage->toRequestArray());
    }


    public function test_toString_returnsStringId(): void
    {
        $result = $this->textCardMessage->__toString();
        $this->assertNotNull($result);
    }
}