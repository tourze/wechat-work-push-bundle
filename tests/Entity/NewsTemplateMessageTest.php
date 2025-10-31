<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\NewsTemplateMessage;

/**
 * @internal
 */
#[CoversClass(NewsTemplateMessage::class)]
final class NewsTemplateMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new NewsTemplateMessage();
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
            'msgId' => ['msgId', 'MSG_20240901_005'],
            'toUser' => ['toUser', ['zhangsan', 'lisi']],
            'toParty' => ['toParty', ['1', '2']],
            'toTag' => ['toTag', ['开发组', '产品组']],
            'safe' => ['safe', false],
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 4800],

            // TemplateCardMessage基类属性
            'title' => ['title', '产品新闻发布'],
            'description' => ['description', '最新产品发布会即将举行，敬请关注'],
            'taskId' => ['taskId', 'TASK_NEWS_001'],

            // NewsTemplateMessage自身属性
            'url' => ['url', 'https://company.com/news/product-launch-2024'],
            'imageUrl' => ['imageUrl', 'https://cdn.company.com/images/product-launch.jpg'],
            'btnText' => ['btnText', '查看新闻详情'],
        ];
    }

    private NewsTemplateMessage $newsTemplate;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->newsTemplate = new NewsTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->newsTemplate->getId());
    }

    public function testToStringReturnsStringId(): void
    {
        $this->newsTemplate->setDescription('描述内容');
        $this->newsTemplate->setUrl('https://example.com');
        $this->newsTemplate->setImageUrl('https://example.com/image.jpg');
        $result = $this->newsTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function testGetCardTypeReturnsNewsNotification(): void
    {
        // 测试公共API行为而不是私有方法
        $this->newsTemplate->setAgent($this->mockAgent);
        $this->newsTemplate->setTitle('Test Title');
        $this->newsTemplate->setDescription('Test Description');
        $this->newsTemplate->setUrl('https://example.com/news');
        $this->newsTemplate->setImageUrl('https://example.com/image.jpg');
        $result = $this->newsTemplate->toRequestArray();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertArrayHasKey('card_type', $templateCard);
        $this->assertEquals('news_notice', $templateCard['card_type']);
    }

    public function testSetTitleAndGetTitle(): void
    {
        $title = '新闻通知卡片';
        $this->newsTemplate->setTitle($title);

        $this->assertEquals($title, $this->newsTemplate->getTitle());
    }

    public function testSetDescriptionAndGetDescription(): void
    {
        $description = '这是新闻通知卡片描述';
        $this->newsTemplate->setDescription($description);

        $this->assertEquals($description, $this->newsTemplate->getDescription());
    }

    public function testToRequestArrayReturnsTemplateCardType(): void
    {
        $this->newsTemplate->setAgent($this->mockAgent);
        $this->newsTemplate->setTitle('测试新闻');
        $this->newsTemplate->setDescription('测试新闻描述');
        $this->newsTemplate->setUrl('https://example.com/news');
        $this->newsTemplate->setImageUrl('https://example.com/image.jpg');

        $result = $this->newsTemplate->toRequestArray();

        $this->assertEquals('template_card', $result['msgtype']);
        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertEquals('news_notice', $templateCard['card_type']);
    }

    public function testRetrieveAdminArrayIncludesBaseFields(): void
    {
        $this->newsTemplate->setAgent($this->mockAgent);
        $this->newsTemplate->setTitle('管理新闻');
        $this->newsTemplate->setDescription('管理新闻描述');
        $this->newsTemplate->setUrl('https://example.com/admin');
        $this->newsTemplate->setImageUrl('https://example.com/admin-image.jpg');

        $result = $this->newsTemplate->retrieveAdminArray();

        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertEquals('管理新闻', $result['title']);
        $this->assertEquals('管理新闻描述', $result['description']);
    }
}
