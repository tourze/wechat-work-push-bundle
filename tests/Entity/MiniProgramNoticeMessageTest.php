<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\MiniProgramNoticeMessage;

/**
 * @internal
 */
#[CoversClass(MiniProgramNoticeMessage::class)]
final class MiniProgramNoticeMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new MiniProgramNoticeMessage();
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
            'msgId' => ['msgId', 'MSG_20240901_001'],
            'toUser' => ['toUser', ['zhangsan', 'lisi']],
            'toParty' => ['toParty', ['1', '2']],
            'toTag' => ['toTag', ['开发组', '产品组']],
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 3600],

            // MiniProgramNoticeMessage自身属性
            'appId' => ['appId', 'wx123456789abcdef0'],
            'page' => ['page', 'pages/detail/detail?id=123'],
            'title' => ['title', '小程序通知标题'],
            'description' => ['description', '小程序通知描述'],
            'emphasisFirstItem' => ['emphasisFirstItem', true],
            'contentItem' => ['contentItem', [
                ['key' => '时间', 'value' => '2024-09-01 10:00'],
                ['key' => '地点', 'value' => '会议室A'],
                ['key' => '主题', 'value' => '项目讨论'],
            ]],
        ];
    }

    private MiniProgramNoticeMessage $miniProgramNotice;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->miniProgramNotice = new MiniProgramNoticeMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->miniProgramNotice->getId());
    }

    public function testGetMsgTypeReturnsMiniprogram(): void
    {
        $this->assertEquals('miniprogram_notice', $this->miniProgramNotice->getMsgType());
    }

    public function testSetTitleAndGetTitle(): void
    {
        $title = '小程序通知';
        $this->miniProgramNotice->setTitle($title);

        $this->assertEquals($title, $this->miniProgramNotice->getTitle());
    }

    public function testSetAppIdAndGetAppId(): void
    {
        $appId = 'wx1234567890123456';
        $this->miniProgramNotice->setAppId($appId);

        $this->assertEquals($appId, $this->miniProgramNotice->getAppId());
    }

    public function testSetPageAndGetPage(): void
    {
        $page = 'pages/index/index';
        $this->miniProgramNotice->setPage($page);

        $this->assertEquals($page, $this->miniProgramNotice->getPage());
    }

    public function testSetDescriptionAndGetDescription(): void
    {
        $description = '小程序通知描述';
        $this->miniProgramNotice->setDescription($description);

        $this->assertEquals($description, $this->miniProgramNotice->getDescription());
    }

    public function testToRequestArrayWithBasicData(): void
    {
        $this->miniProgramNotice->setAgent($this->mockAgent);
        $this->miniProgramNotice->setTitle('测试小程序');
        $this->miniProgramNotice->setAppId('wx1234567890123456');
        $this->miniProgramNotice->setPage('pages/test/test');
        $this->miniProgramNotice->setDescription('测试描述');

        $expectedArray = [
            'agentid' => '1000002',
            'enable_id_trans' => 0,
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'miniprogram_notice',
            'miniprogram_notice' => [
                'appid' => 'wx1234567890123456',
                'title' => '测试小程序',
                'page' => 'pages/test/test',
                'description' => '测试描述',
            ],
        ];

        $this->assertEquals($expectedArray, $this->miniProgramNotice->toRequestArray());
    }

    public function testToStringReturnsStringId(): void
    {
        $result = $this->miniProgramNotice->__toString();
        $this->assertNotNull($result);
    }
}
