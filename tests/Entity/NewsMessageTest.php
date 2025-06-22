<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\NewsMessage;

class NewsMessageTest extends TestCase
{
    private NewsMessage $newsMessage;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->newsMessage = new NewsMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->newsMessage->getId());
    }

    public function test_getMsgType_returnsNews(): void
    {
        $this->assertEquals('news', $this->newsMessage->getMsgType());
    }

    public function test_setTitle_withValidTitle(): void
    {
        $title = '这是新闻标题';
        $this->newsMessage->setTitle($title);

        $this->assertEquals($title, $this->newsMessage->getTitle());
    }

    public function test_setTitle_withMaxLength(): void
    {
        $title = str_repeat('标', 64); // 最大长度128字节，每个中文字符2字节
        $this->newsMessage->setTitle($title);

        $this->assertEquals($title, $this->newsMessage->getTitle());
    }

    public function test_setDescription_withValidDescription(): void
    {
        $description = '这是新闻描述内容';
        $this->newsMessage->setDescription($description);

        $this->assertEquals($description, $this->newsMessage->getDescription());
    }

    public function test_setDescription_withNull(): void
    {
        $this->newsMessage->setDescription(null);
        $this->assertNull($this->newsMessage->getDescription());
    }

    public function test_setDescription_withMaxLength(): void
    {
        $description = str_repeat('描', 256); // 最大长度512字节
        $this->newsMessage->setDescription($description);

        $this->assertEquals($description, $this->newsMessage->getDescription());
    }

    public function test_setUrl_withValidUrl(): void
    {
        $url = 'https://example.com/news/123';
        $this->newsMessage->setUrl($url);

        $this->assertEquals($url, $this->newsMessage->getUrl());
    }

    public function test_setUrl_withNull(): void
    {
        $this->newsMessage->setUrl(null);
        $this->assertNull($this->newsMessage->getUrl());
    }

    public function test_setUrl_withMaxLength(): void
    {
        $url = 'https://example.com/' . str_repeat('path/', 100);
        $this->newsMessage->setUrl($url);

        $this->assertEquals($url, $this->newsMessage->getUrl());
    }

    public function test_setPicUrl_withValidUrl(): void
    {
        $picUrl = 'https://example.com/image.jpg';
        $this->newsMessage->setPicUrl($picUrl);

        $this->assertEquals($picUrl, $this->newsMessage->getPicUrl());
    }

    public function test_setPicUrl_withNull(): void
    {
        $this->newsMessage->setPicUrl(null);
        $this->assertNull($this->newsMessage->getPicUrl());
    }

    public function test_setAppId_withValidAppId(): void
    {
        $appId = 'wx123456789abcdef';
        $this->newsMessage->setAppId($appId);

        $this->assertEquals($appId, $this->newsMessage->getAppId());
    }

    public function test_setAppId_withNull(): void
    {
        $this->newsMessage->setAppId(null);
        $this->assertNull($this->newsMessage->getAppId());
    }

    public function test_setPagePath_withValidPath(): void
    {
        $pagePath = 'pages/index/index';
        $this->newsMessage->setPagePath($pagePath);

        $this->assertEquals($pagePath, $this->newsMessage->getPagePath());
    }

    public function test_setPagePath_withNull(): void
    {
        $this->newsMessage->setPagePath(null);
        $this->assertNull($this->newsMessage->getPagePath());
    }

    public function test_setCreateTime_withDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-01 12:00:00');
        $this->newsMessage->setCreateTime($dateTime);

        $this->assertEquals($dateTime, $this->newsMessage->getCreateTime());
    }

    public function test_setUpdateTime_withDateTime(): void
    {
        $dateTime = new \DateTimeImmutable('2024-01-02 15:30:00');
        $this->newsMessage->setUpdateTime($dateTime);

        $this->assertEquals($dateTime, $this->newsMessage->getUpdateTime());
    }

    public function test_toRequestArray_withBasicData(): void
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
                        'title' => 'Test News'
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedArray, $this->newsMessage->toRequestArray());
    }

    public function test_toRequestArray_withAllFields(): void
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
                        'picurl' => 'https://example.com/pic.jpg'
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedArray, $this->newsMessage->toRequestArray());
    }

    public function test_toRequestArray_withMiniProgramFields(): void
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
                        'pagepath' => 'pages/news/detail'
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedArray, $this->newsMessage->toRequestArray());
    }

    public function test_toRequestArray_withToUser(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setTitle('User News');
        $this->newsMessage->setToUser(['user1', 'user2']);

        $result = $this->newsMessage->toRequestArray();

        $this->assertArrayHasKey('touser', $result);
        $this->assertEquals('user1|user2', $result['touser']);
    }

    public function test_toRequestArray_withSafeEnabled(): void
    {
        $this->newsMessage->setAgent($this->mockAgent);
        $this->newsMessage->setTitle('Secret News');
        $this->newsMessage->setSafe(true);

        $result = $this->newsMessage->toRequestArray();

        $this->assertEquals(1, $result['safe']);
    }

    public function test_retrieveAdminArray_withCompleteData(): void
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
            'agentid' => '1000002'
        ];

        $this->assertEquals($expectedArray, $this->newsMessage->retrieveAdminArray());
    }

    public function test_retrieveAdminArray_withMinimalData(): void
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

    public function test_userTrackingMethods(): void
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

    public function test_edgeCases_emptyStrings(): void
    {
        $this->newsMessage->setTitle('');
        $this->newsMessage->setDescription('');
        $this->newsMessage->setUrl('');

        $this->assertEquals('', $this->newsMessage->getTitle());
        $this->assertEquals('', $this->newsMessage->getDescription());
        $this->assertEquals('', $this->newsMessage->getUrl());
    }

    public function test_agentTraitMethods(): void
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

    public function test_msgIdMethods(): void
    {
        $msgId = 'msg_news_123456';
        $result = $this->newsMessage->setMsgId($msgId);

        $this->assertSame($this->newsMessage, $result);
        $this->assertEquals($msgId, $this->newsMessage->getMsgId());
    }
}
