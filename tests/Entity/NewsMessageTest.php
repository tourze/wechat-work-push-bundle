<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\NewsMessage;

/**
 * @internal
 */
#[CoversClass(NewsMessage::class)]
final class NewsMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new NewsMessage();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'title' => ['title', '测试新闻标题'],
            'description' => ['description', '这是一条测试新闻的详细描述内容'],
            'url' => ['url', 'https://example.com/news/detail/123'],
            'picUrl' => ['picUrl', 'https://example.com/images/news.jpg'],
            'appId' => ['appId', 'wx1234567890abcdef'],
            'pagePath' => ['pagePath', 'pages/news/detail'],
            'createTime' => ['createTime', new \DateTimeImmutable('2024-01-01 10:00:00')],
            'updateTime' => ['updateTime', new \DateTimeImmutable('2024-01-01 11:00:00')],
            'createdBy' => ['createdBy', 'user123'],
            'updatedBy' => ['updatedBy', 'user456'],
            'createdFromIp' => ['createdFromIp', '192.168.1.100'],
            'updatedFromIp' => ['updatedFromIp', '192.168.1.101'],
            'msgId' => ['msgId', 'msg_news_20240101_001'],
            'toUser' => ['toUser', ['user1', 'user2', 'user3']],
            'toParty' => ['toParty', ['dept1', 'dept2']],
            'toTag' => ['toTag', ['tag1', 'tag2']],
            'safe' => ['safe', true],
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 3600],
        ];
    }

    private NewsMessage $newsMessage;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->newsMessage = new NewsMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->newsMessage->getId());
    }

    public function testGetMsgTypeReturnsNews(): void
    {
        $this->assertEquals('news', $this->newsMessage->getMsgType());
    }

    public function testSetTitleWithValidTitle(): void
    {
        $title = '这是新闻标题';
        $this->newsMessage->setTitle($title);

        $this->assertEquals($title, $this->newsMessage->getTitle());
    }

    public function testSetTitleWithMaxLength(): void
    {
        $title = str_repeat('标', 64); // 最大长度128字节，每个中文字符2字节
        $this->newsMessage->setTitle($title);

        $this->assertEquals($title, $this->newsMessage->getTitle());
    }

    public function testSetDescriptionWithValidDescription(): void
    {
        $description = '这是新闻描述内容';
        $this->newsMessage->setDescription($description);

        $this->assertEquals($description, $this->newsMessage->getDescription());
    }

    public function testSetDescriptionWithNull(): void
    {
        $this->newsMessage->setDescription(null);
        $this->assertNull($this->newsMessage->getDescription());
    }

    public function testSetDescriptionWithMaxLength(): void
    {
        $description = str_repeat('描', 256); // 最大长度512字节
        $this->newsMessage->setDescription($description);

        $this->assertEquals($description, $this->newsMessage->getDescription());
    }

    public function testSetUrlWithValidUrl(): void
    {
        $url = 'https://example.com/news/123';
        $this->newsMessage->setUrl($url);

        $this->assertEquals($url, $this->newsMessage->getUrl());
    }

    public function testSetUrlWithNull(): void
    {
        $this->newsMessage->setUrl(null);
        $this->assertNull($this->newsMessage->getUrl());
    }

    public function testSetUrlWithMaxLength(): void
    {
        $url = 'https://example.com/' . str_repeat('path/', 100);
        $this->newsMessage->setUrl($url);

        $this->assertEquals($url, $this->newsMessage->getUrl());
    }

    public function testSetPicUrlWithValidUrl(): void
    {
        $picUrl = 'https://example.com/image.jpg';
        $this->newsMessage->setPicUrl($picUrl);

        $this->assertEquals($picUrl, $this->newsMessage->getPicUrl());
    }

    public function testSetPicUrlWithNull(): void
    {
        $this->newsMessage->setPicUrl(null);
        $this->assertNull($this->newsMessage->getPicUrl());
    }

    public function testSetAppIdWithValidAppId(): void
    {
        $appId = 'wx123456789abcdef';
        $this->newsMessage->setAppId($appId);

        $this->assertEquals($appId, $this->newsMessage->getAppId());
    }

    public function testSetAppIdWithNull(): void
    {
        $this->newsMessage->setAppId(null);
        $this->assertNull($this->newsMessage->getAppId());
    }

    public function testSetPagePathWithValidPath(): void
    {
        $pagePath = 'pages/index/index';
        $this->newsMessage->setPagePath($pagePath);

        $this->assertEquals($pagePath, $this->newsMessage->getPagePath());
    }

    public function testSetPagePathWithNull(): void
    {
        $this->newsMessage->setPagePath(null);
        $this->assertNull($this->newsMessage->getPagePath());
    }

    public function testSetCreateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-01 12:00:00');
        $this->newsMessage->setCreateTime($dateTime);

        $this->assertEquals($dateTime, $this->newsMessage->getCreateTime());
    }

    public function testSetUpdateTimeWithDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-02 15:30:00');
        $this->newsMessage->setUpdateTime($dateTime);

        $this->assertEquals($dateTime, $this->newsMessage->getUpdateTime());
    }

    public function testToRequestArrayWithBasicData(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setTitle('Test News');

        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'news',
            'news' => [
                'articles' => [
                    [
                        'title' => 'Test News',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $this->newsMessage->toRequestArray());
    }

    public function testToRequestArrayWithAllFields(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setTitle('Complete News');
        $this->newsMessage->setDescription('News description');
        $this->newsMessage->setUrl('https://example.com/news');
        $this->newsMessage->setPicUrl('https://example.com/pic.jpg');

        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'news',
            'news' => [
                'articles' => [
                    [
                        'title' => 'Complete News',
                        'description' => 'News description',
                        'url' => 'https://example.com/news',
                        'picurl' => 'https://example.com/pic.jpg',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $this->newsMessage->toRequestArray());
    }

    public function testToRequestArrayWithMiniProgramFields(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setTitle('Mini Program News');
        $this->newsMessage->setAppId('wx123456789');
        $this->newsMessage->setPagePath('pages/news/detail');

        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'news',
            'news' => [
                'articles' => [
                    [
                        'title' => 'Mini Program News',
                        'appid' => 'wx123456789',
                        'pagepath' => 'pages/news/detail',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $this->newsMessage->toRequestArray());
    }

    public function testToRequestArrayWithToUser(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setTitle('User News');
        $this->newsMessage->setToUser(['user1', 'user2']);

        $result = $this->newsMessage->toRequestArray();

        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function testToRequestArrayWithSafeEnabled(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setTitle('Secret News');
        $this->newsMessage->setSafe(true);

        $result = $this->newsMessage->toRequestArray();

        $this->assertEquals(1, $result['safe']);
    }

    public function testRetrieveAdminArrayWithCompleteData(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setTitle('Admin News');
        $this->newsMessage->setDescription('Admin description');
        $this->newsMessage->setUrl('https://admin.example.com');
        $this->newsMessage->setPicUrl('https://admin.example.com/pic.jpg');
        $this->newsMessage->setAppId('wx987654321');
        $this->newsMessage->setPagePath('pages/admin');

        $createTime = new \DateTimeImmutable('2024-01-01 10:00:00');
        $updateTime = new \DateTimeImmutable('2024-01-01 11:00:00');
        $this->newsMessage->setCreateTime($createTime);
        $this->newsMessage->setUpdateTime($updateTime);

        $expectedArray = [
            'id' => null,
            'title' => 'Admin News',
            'description' => 'Admin description',
            'url' => 'https://admin.example.com',
            'picUrl' => 'https://admin.example.com/pic.jpg',
            'appId' => 'wx987654321',
            'pagePath' => 'pages/admin',
            'createTime' => '2024-01-01 10:00:00',
            'updateTime' => '2024-01-01 11:00:00',
            'agentid' => '1000002',
        ];

        $this->assertEquals($expectedArray, $this->newsMessage->retrieveAdminArray());
    }

    public function testRetrieveAdminArrayWithMinimalData(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setTitle('Minimal News');

        $result = $this->newsMessage->retrieveAdminArray();

        $this->assertEquals('Minimal News', $result['title']);
        $this->assertNull($result['description']);
        $this->assertNull($result['url']);
        $this->assertNull($result['picUrl']);
        $this->assertNull($result['appId']);
        $this->assertNull($result['pagePath']);
        $this->assertNull($result['createTime']);
        $this->assertNull($result['updateTime']);
    }

    public function testUserTrackingMethods(): void
    {
        $userId = 'user123';
        $ip = '192.168.1.1';

        $this->newsMessage->setCreatedBy($userId);
        $this->newsMessage->setUpdatedBy($userId);
        $this->newsMessage->setCreatedFromIp($ip);
        $this->newsMessage->setUpdatedFromIp($ip);

        $this->assertEquals($userId, $this->newsMessage->getCreatedBy());
        $this->assertEquals($userId, $this->newsMessage->getUpdatedBy());
        $this->assertEquals($ip, $this->newsMessage->getCreatedFromIp());
        $this->assertEquals($ip, $this->newsMessage->getUpdatedFromIp());
    }

    public function testEdgeCasesEmptyStrings(): void
    {
        $this->newsMessage->setTitle('');
        $this->newsMessage->setDescription('');
        $this->newsMessage->setUrl('');

        $this->assertEquals('', $this->newsMessage->getTitle());
        $this->assertEquals('', $this->newsMessage->getDescription());
        $this->assertEquals('', $this->newsMessage->getUrl());
    }

    public function testAgentTraitMethods(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setToUser(['user1', 'user2']);
        $this->newsMessage->setToParty(['dept1']);
        $this->newsMessage->setToTag(['tag1']);

        $this->assertSame($this->mockAgent, $this->newsMessage->getAgent());
        $this->assertEquals(['user1', 'user2'], $this->newsMessage->getToUser());
        $this->assertEquals(['dept1'], $this->newsMessage->getToParty());
        $this->assertEquals(['tag1'], $this->newsMessage->getToTag());
    }

    public function testMsgIdMethods(): void
    {
        $msgId = 'msg_news_123456';
        $this->newsMessage->setMsgId($msgId);

        $this->assertEquals($msgId, $this->newsMessage->getMsgId());
    }
}
