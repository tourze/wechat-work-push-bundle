<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\MiniProgramNoticeMessage;

class MiniProgramNoticeMessageTest extends TestCase
{
    private MiniProgramNoticeMessage $miniProgramNotice;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->miniProgramNotice = new MiniProgramNoticeMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->miniProgramNotice->getId());
    }

    public function test_getMsgType_returnsMiniprogram(): void
    {
        $this->assertEquals('miniprogram_notice', $this->miniProgramNotice->getMsgType());
    }

    public function test_setTitle_andGetTitle(): void
    {
        $title = '小程序通知';
        $this->miniProgramNotice->setTitle($title);

        $this->assertEquals($title, $this->miniProgramNotice->getTitle());
    }

    public function test_setAppId_andGetAppId(): void
    {
        $appId = 'wx1234567890123456';
        $result = $this->miniProgramNotice->setAppId($appId);

        $this->assertSame($this->miniProgramNotice, $result);
        $this->assertEquals($appId, $this->miniProgramNotice->getAppId());
    }

    public function test_setPage_andGetPage(): void
    {
        $page = 'pages/index/index';
        $this->miniProgramNotice->setPage($page);

        $this->assertEquals($page, $this->miniProgramNotice->getPage());
    }

    public function test_setDescription_andGetDescription(): void
    {
        $description = '小程序通知描述';
        $this->miniProgramNotice->setDescription($description);

        $this->assertEquals($description, $this->miniProgramNotice->getDescription());
    }

    public function test_toRequestArray_withBasicData(): void
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
                'description' => '测试描述'
            ]
        ];

        $this->assertEquals($expectedArray, $this->miniProgramNotice->toRequestArray());
    }


    public function test_toString_returnsStringId(): void
    {
        $result = $this->miniProgramNotice->__toString();
        $this->assertNotNull($result);
    }
}