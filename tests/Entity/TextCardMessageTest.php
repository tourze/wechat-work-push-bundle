<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\TextCardMessage;

/**
 * @internal
 */
#[CoversClass(TextCardMessage::class)]
final class TextCardMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new TextCardMessage();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            // 实体直接属性
            'title' => ['title', '系统通知'],
            'description' => ['description', '您有一条新的工作通知，请及时查看处理'],
            'url' => ['url', 'https://example.com/notifications/123'],
            'btnText' => ['btnText', '立即查看'],

            // AgentTrait 属性 (跳过 agent，因为是接口类型)
            'msgId' => ['msgId', 'msg_card_20240101_001'],
            'toUser' => ['toUser', ['user001', 'user002']],
            'toParty' => ['toParty', ['dept001', 'dept002']],
            'toTag' => ['toTag', ['tag001', 'tag002']],

            // DuplicateCheckTrait 属性
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 3600],

            // IdTransTrait 属性
            'enableIdTrans' => ['enableIdTrans', true],

            // 时间戳属性
            'createTime' => ['createTime', new \DateTimeImmutable('2024-01-01 10:00:00')],
            'updateTime' => ['updateTime', new \DateTimeImmutable('2024-01-01 11:00:00')],

            // 责任追溯属性
            'createdBy' => ['createdBy', 'admin_001'],
            'updatedBy' => ['updatedBy', 'admin_002'],

            // IP追溯属性
            'createdFromIp' => ['createdFromIp', '192.168.1.100'],
            'updatedFromIp' => ['updatedFromIp', '10.0.0.50'],
        ];
    }

    private TextCardMessage $textCardMessage;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->textCardMessage = new TextCardMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->textCardMessage->getId());
    }

    public function testGetMsgTypeReturnsTextcard(): void
    {
        $this->assertEquals('textcard', $this->textCardMessage->getMsgType());
    }

    public function testSetTitleAndGetTitle(): void
    {
        $title = '通知标题';
        $this->textCardMessage->setTitle($title);

        $this->assertEquals($title, $this->textCardMessage->getTitle());
    }

    public function testSetDescriptionAndGetDescription(): void
    {
        $description = '这是通知的详细描述';
        $this->textCardMessage->setDescription($description);

        $this->assertEquals($description, $this->textCardMessage->getDescription());
    }

    public function testSetUrlAndGetUrl(): void
    {
        $url = 'https://example.com';
        $this->textCardMessage->setUrl($url);

        $this->assertEquals($url, $this->textCardMessage->getUrl());
    }

    public function testSetBtnTextAndGetBtnText(): void
    {
        $btnText = '查看详情';
        $this->textCardMessage->setBtnText($btnText);

        $this->assertEquals($btnText, $this->textCardMessage->getBtnText());
    }

    public function testToRequestArrayWithBasicData(): void
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
                'btntxt' => '点击查看',
            ],
        ];

        $this->assertEquals($expectedArray, $this->textCardMessage->toRequestArray());
    }
}
