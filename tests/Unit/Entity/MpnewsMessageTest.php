<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\MpnewsMessage;

class MpnewsMessageTest extends TestCase
{
    private MpnewsMessage $mpnewsMessage;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->mpnewsMessage = new MpnewsMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->mpnewsMessage->getId());
    }

    public function test_getMsgType_returnsMpnews(): void
    {
        $this->assertEquals('mpnews', $this->mpnewsMessage->getMsgType());
    }

    public function test_setTitle_andGetTitle(): void
    {
        $title = '图文消息标题';
        $this->mpnewsMessage->setTitle($title);

        $this->assertEquals($title, $this->mpnewsMessage->getTitle());
    }

    public function test_setContentSourceUrl_andGetContentSourceUrl(): void
    {
        $url = 'https://example.com/article';
        $this->mpnewsMessage->setContentSourceUrl($url);

        $this->assertEquals($url, $this->mpnewsMessage->getContentSourceUrl());
    }

    public function test_setContent_andGetContent(): void
    {
        $content = '这是图文消息的内容';
        $this->mpnewsMessage->setContent($content);

        $this->assertEquals($content, $this->mpnewsMessage->getContent());
    }

    public function test_setDigest_andGetDigest(): void
    {
        $digest = '图文消息摘要';
        $this->mpnewsMessage->setDigest($digest);

        $this->assertEquals($digest, $this->mpnewsMessage->getDigest());
    }

    public function test_setThumbMediaId_andGetThumbMediaId(): void
    {
        $thumbMediaId = 'thumb_media_123';
        $this->mpnewsMessage->setThumbMediaId($thumbMediaId);

        $this->assertEquals($thumbMediaId, $this->mpnewsMessage->getThumbMediaId());
    }

    public function test_setThumbMediaUrl_andGetThumbMediaUrl(): void
    {
        $thumbMediaUrl = 'https://example.com/thumb.jpg';
        $this->mpnewsMessage->setThumbMediaUrl($thumbMediaUrl);

        $this->assertEquals($thumbMediaUrl, $this->mpnewsMessage->getThumbMediaUrl());
    }

    public function test_toRequestArray_withBasicData(): void
    {
        $this->mpnewsMessage->setAgent($this->mockAgent);
        $this->mpnewsMessage->setTitle('测试图文');
        $this->mpnewsMessage->setContent('图文内容');
        $this->mpnewsMessage->setDigest('图文摘要');
        $this->mpnewsMessage->setThumbMediaId('media123');

        $expectedArray = [
            'agentid' => '1000002',
            'safe' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'mpnews',
            'mpnews' => [
                'articles' => [
                    [
                        'title' => '测试图文',
                        'content' => '图文内容',
                        'thumb_media_id' => 'media123',
                        'digest' => '图文摘要'
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedArray, $this->mpnewsMessage->toRequestArray());
    }

    public function test_toRequestArray_withContentSourceUrl(): void
    {
        $this->mpnewsMessage->setAgent($this->mockAgent);
        $this->mpnewsMessage->setTitle('测试图文');
        $this->mpnewsMessage->setContent('图文内容');
        $this->mpnewsMessage->setThumbMediaId('media123');
        $this->mpnewsMessage->setContentSourceUrl('https://example.com/source');

        $result = $this->mpnewsMessage->toRequestArray();
        $article = $result['mpnews']['articles'][0];

        $this->assertArrayHasKey('content_source_url', $article);
        $this->assertEquals('https://example.com/source', $article['content_source_url']);
    }

    public function test_toString_returnsStringId(): void
    {
        $result = $this->mpnewsMessage->__toString();
        $this->assertNotNull($result);
    }
}