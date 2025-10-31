<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\MpnewsMessage;

/**
 * @internal
 */
#[CoversClass(MpnewsMessage::class)]
final class MpnewsMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new MpnewsMessage();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'title' => ['title', '测试图文消息标题'],
            'content' => ['content', '<h1>图文消息内容</h1><p>这是一条详细的图文消息内容。</p>'],
            'thumbMediaUrl' => ['thumbMediaUrl', 'https://example.com/images/thumb.jpg'],
            'thumbMediaId' => ['thumbMediaId', 'thumb_media_1234567890abcdef'],
            'digest' => ['digest', '这是图文消息的摘要描述，用于展示消息概要信息'],
            'contentSourceUrl' => ['contentSourceUrl', 'https://example.com/articles/source-page'],
            'createTime' => ['createTime', new \DateTimeImmutable('2024-01-01 10:00:00')],
            'updateTime' => ['updateTime', new \DateTimeImmutable('2024-01-01 11:00:00')],
            'createdBy' => ['createdBy', 'user123'],
            'updatedBy' => ['updatedBy', 'user456'],
            'createdFromIp' => ['createdFromIp', '192.168.1.100'],
            'updatedFromIp' => ['updatedFromIp', '192.168.1.101'],
            'msgId' => ['msgId', 'msg_mpnews_20240101_001'],
            'toUser' => ['toUser', ['user1', 'user2', 'user3']],
            'toParty' => ['toParty', ['dept1', 'dept2']],
            'toTag' => ['toTag', ['tag1', 'tag2']],
            'safe' => ['safe', true],
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 3600],
        ];
    }

    private MpnewsMessage $mpnewsMessage;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mpnewsMessage = new MpnewsMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->mpnewsMessage->getId());
    }

    public function testGetMsgTypeReturnsMpnews(): void
    {
        $this->assertEquals('mpnews', $this->mpnewsMessage->getMsgType());
    }

    public function testSetTitleAndGetTitle(): void
    {
        $title = '图文消息标题';
        $this->mpnewsMessage->setTitle($title);

        $this->assertEquals($title, $this->mpnewsMessage->getTitle());
    }

    public function testSetContentSourceUrlAndGetContentSourceUrl(): void
    {
        $url = 'https://example.com/article';
        $this->mpnewsMessage->setContentSourceUrl($url);

        $this->assertEquals($url, $this->mpnewsMessage->getContentSourceUrl());
    }

    public function testSetContentAndGetContent(): void
    {
        $content = '这是图文消息的内容';
        $this->mpnewsMessage->setContent($content);

        $this->assertEquals($content, $this->mpnewsMessage->getContent());
    }

    public function testSetDigestAndGetDigest(): void
    {
        $digest = '图文消息摘要';
        $this->mpnewsMessage->setDigest($digest);

        $this->assertEquals($digest, $this->mpnewsMessage->getDigest());
    }

    public function testSetThumbMediaIdAndGetThumbMediaId(): void
    {
        $thumbMediaId = 'thumb_media_123';
        $this->mpnewsMessage->setThumbMediaId($thumbMediaId);

        $this->assertEquals($thumbMediaId, $this->mpnewsMessage->getThumbMediaId());
    }

    public function testSetThumbMediaUrlAndGetThumbMediaUrl(): void
    {
        $thumbMediaUrl = 'https://example.com/thumb.jpg';
        $this->mpnewsMessage->setThumbMediaUrl($thumbMediaUrl);

        $this->assertEquals($thumbMediaUrl, $this->mpnewsMessage->getThumbMediaUrl());
    }

    public function testToRequestArrayWithBasicData(): void
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
                        'digest' => '图文摘要',
                    ],
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $this->mpnewsMessage->toRequestArray());
    }

    public function testToRequestArrayWithContentSourceUrl(): void
    {
        $this->mpnewsMessage->setAgent($this->mockAgent);
        $this->mpnewsMessage->setTitle('测试图文');
        $this->mpnewsMessage->setContent('图文内容');
        $this->mpnewsMessage->setThumbMediaId('media123');
        $this->mpnewsMessage->setContentSourceUrl('https://example.com/source');

        $result = $this->mpnewsMessage->toRequestArray();
        $this->assertArrayHasKey('mpnews', $result);
        $mpnews = $result['mpnews'];
        $this->assertIsArray($mpnews);
        $this->assertArrayHasKey('articles', $mpnews);
        $articles = $mpnews['articles'];
        $this->assertIsArray($articles);
        $this->assertArrayHasKey(0, $articles);
        $article = $articles[0];
        $this->assertIsArray($article);

        $this->assertArrayHasKey('content_source_url', $article);
        $this->assertEquals('https://example.com/source', $article['content_source_url']);
    }
}
