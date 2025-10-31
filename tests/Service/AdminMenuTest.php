<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Service;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\MockObject\MockObject;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatWorkPushBundle\Service\AdminMenu;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $service;

    /** @var MockObject&LinkGeneratorInterface */
    private LinkGeneratorInterface $linkGenerator;

    protected function onSetUp(): void
    {
        $this->linkGenerator = $this->createMock(LinkGeneratorInterface::class);
        self::getContainer()->set(LinkGeneratorInterface::class, $this->linkGenerator);
        $this->service = self::getService(AdminMenu::class);
    }

    public function testServiceCanBeInstantiated(): void
    {
        $this->assertInstanceOf(AdminMenu::class, $this->service);
    }

    public function testInvokeMethodAddsWeChatWorkPushMenuItems(): void
    {
        // Configure linkGenerator to return URLs for all entity types
        $this->linkGenerator
            ->expects($this->exactly(16))
            ->method('getCurdListPage')
            ->willReturn('/admin/test-url')
        ;

        /** @var MockObject&ItemInterface $menuItem */
        /** @var ItemInterface&MockObject $menuItem */
        $menuItem = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $wechatWorkMenu */
        /** @var ItemInterface&MockObject $wechatWorkMenu */
        $wechatWorkMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $pushMenu */
        /** @var ItemInterface&MockObject $pushMenu */
        $pushMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $basicMessageMenu */
        /** @var ItemInterface&MockObject $basicMessageMenu */
        $basicMessageMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $newsMenu */
        /** @var ItemInterface&MockObject $newsMenu */
        $newsMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $templateMenu */
        /** @var ItemInterface&MockObject $templateMenu */
        $templateMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $subMenuItem */
        /** @var ItemInterface&MockObject $subMenuItem */
        $subMenuItem = $this->createMock(ItemInterface::class);

        // Mock the main menu structure
        $menuItem->expects($this->exactly(2))
            ->method('getChild')
            ->with('企业微信管理')
            ->willReturnOnConsecutiveCalls(null, $wechatWorkMenu)
        ;

        $menuItem->expects($this->once())
            ->method('addChild')
            ->with('企业微信管理')
            ->willReturn($wechatWorkMenu)
        ;

        $wechatWorkMenu->expects($this->once())
            ->method('setAttribute')
            ->with('icon', 'fas fa-wechat')
            ->willReturn($wechatWorkMenu)
        ;

        // Mock the push menu structure
        $wechatWorkMenu->expects($this->exactly(2))
            ->method('getChild')
            ->with('应用推送')
            ->willReturnOnConsecutiveCalls(null, $pushMenu)
        ;

        $wechatWorkMenu->expects($this->once())
            ->method('addChild')
            ->with('应用推送')
            ->willReturn($pushMenu)
        ;

        $pushMenu->expects($this->once())
            ->method('setAttribute')
            ->with('icon', 'fas fa-paper-plane')
            ->willReturn($pushMenu)
        ;

        // Mock the sub-menu creation: 基础消息, 图文消息, 模板消息, 小程序通知消息 (4 main menu items)
        $pushMenu->expects($this->exactly(4))
            ->method('addChild')
            ->willReturn($subMenuItem)
        ;

        // Mock sub-menu getChild calls
        $pushMenu->expects($this->exactly(3))
            ->method('getChild')
            ->willReturnMap([
                ['基础消息', $basicMessageMenu],
                ['图文消息', $newsMenu],
                ['模板消息', $templateMenu],
            ])
        ;

        // Mock basic message menu (6 items: 文本消息, 图片消息, 语音消息, 视频消息, 文件消息, Markdown消息)
        $basicMessageMenu->expects($this->exactly(6))
            ->method('addChild')
            ->willReturn($subMenuItem)
        ;

        // Mock news menu (3 items: 图文消息, Mpnews消息, 文本卡片消息)
        $newsMenu->expects($this->exactly(3))
            ->method('addChild')
            ->willReturn($subMenuItem)
        ;

        // Mock template menu (6 items: 文本通知型, 图文展示型, 按钮交互型, 投票选择型, 多项选择型, 模板卡片)
        $templateMenu->expects($this->exactly(6))
            ->method('addChild')
            ->willReturn($subMenuItem)
        ;

        // Total setUri calls: 6 (basic) + 3 (news) + 6 (template) + 1 (miniprogram) = 16
        $subMenuItem->expects($this->exactly(16))
            ->method('setUri')
            ->willReturn($subMenuItem)
        ;

        // Total setAttribute calls: 3 (main menus) + 16 (sub menu items) = 19
        $subMenuItem->expects($this->exactly(19))
            ->method('setAttribute')
            ->willReturn($subMenuItem)
        ;

        // Invoke the service
        $this->service->__invoke($menuItem);
    }

    public function testInvokeMethodWithExistingWeChatWorkMenu(): void
    {
        // Configure linkGenerator to return URLs for all entity types
        $this->linkGenerator
            ->expects($this->exactly(16))
            ->method('getCurdListPage')
            ->willReturn('/admin/test-url')
        ;

        /** @var MockObject&ItemInterface $menuItem */
        /** @var ItemInterface&MockObject $menuItem */
        $menuItem = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $wechatWorkMenu */
        /** @var ItemInterface&MockObject $wechatWorkMenu */
        $wechatWorkMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $pushMenu */
        /** @var ItemInterface&MockObject $pushMenu */
        $pushMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $basicMessageMenu */
        /** @var ItemInterface&MockObject $basicMessageMenu */
        $basicMessageMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $newsMenu */
        /** @var ItemInterface&MockObject $newsMenu */
        $newsMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $templateMenu */
        /** @var ItemInterface&MockObject $templateMenu */
        $templateMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $subMenuItem */
        /** @var ItemInterface&MockObject $subMenuItem */
        $subMenuItem = $this->createMock(ItemInterface::class);

        // Mock existing menu
        $menuItem->expects($this->exactly(2))
            ->method('getChild')
            ->with('企业微信管理')
            ->willReturn($wechatWorkMenu)
        ;

        $menuItem->expects($this->never())
            ->method('addChild')
        ;

        // Mock the push menu structure
        $wechatWorkMenu->expects($this->exactly(2))
            ->method('getChild')
            ->with('应用推送')
            ->willReturnOnConsecutiveCalls(null, $pushMenu)
        ;

        $wechatWorkMenu->expects($this->once())
            ->method('addChild')
            ->with('应用推送')
            ->willReturn($pushMenu)
        ;

        $pushMenu->expects($this->once())
            ->method('setAttribute')
            ->with('icon', 'fas fa-paper-plane')
            ->willReturn($pushMenu)
        ;

        // Mock the sub-menu creation
        $pushMenu->expects($this->exactly(4))
            ->method('addChild')
            ->willReturn($subMenuItem)
        ;

        // Mock sub-menu getChild calls
        $pushMenu->expects($this->exactly(3))
            ->method('getChild')
            ->willReturnMap([
                ['基础消息', $basicMessageMenu],
                ['图文消息', $newsMenu],
                ['模板消息', $templateMenu],
            ])
        ;

        // Mock basic message menu (6 items)
        $basicMessageMenu->expects($this->exactly(6))
            ->method('addChild')
            ->willReturn($subMenuItem)
        ;

        // Mock news menu (3 items)
        $newsMenu->expects($this->exactly(3))
            ->method('addChild')
            ->willReturn($subMenuItem)
        ;

        // Mock template menu (6 items)
        $templateMenu->expects($this->exactly(6))
            ->method('addChild')
            ->willReturn($subMenuItem)
        ;

        // Total setUri calls: 16
        $subMenuItem->expects($this->exactly(16))
            ->method('setUri')
            ->willReturn($subMenuItem)
        ;

        // Total setAttribute calls: 19
        $subMenuItem->expects($this->exactly(19))
            ->method('setAttribute')
            ->willReturn($subMenuItem)
        ;

        // Invoke the service
        $this->service->__invoke($menuItem);
    }

    public function testInvokeMethodReturnsEarlyWhenWeChatMenuIsNull(): void
    {
        /** @var MockObject&ItemInterface $menuItem */
        /** @var ItemInterface&MockObject $menuItem */
        $menuItem = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $wechatWorkMenu */
        /** @var ItemInterface&MockObject $wechatWorkMenu */
        $wechatWorkMenu = $this->createMock(ItemInterface::class);

        // Mock the main menu structure - return null for existing wechat menu
        $menuItem->expects($this->exactly(2))
            ->method('getChild')
            ->with('企业微信管理')
            ->willReturnOnConsecutiveCalls(null, null)
        ;

        $menuItem->expects($this->once())
            ->method('addChild')
            ->with('企业微信管理')
            ->willReturn($wechatWorkMenu)
        ;

        $wechatWorkMenu->expects($this->once())
            ->method('setAttribute')
            ->with('icon', 'fas fa-wechat')
            ->willReturn($wechatWorkMenu)
        ;

        // Should return early, no further calls expected

        // Invoke the service
        $this->service->__invoke($menuItem);
    }

    public function testInvokeMethodReturnsEarlyWhenPushMenuIsNull(): void
    {
        /** @var MockObject&ItemInterface $menuItem */
        /** @var ItemInterface&MockObject $menuItem */
        $menuItem = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $wechatWorkMenu */
        /** @var ItemInterface&MockObject $wechatWorkMenu */
        $wechatWorkMenu = $this->createMock(ItemInterface::class);

        /** @var MockObject&ItemInterface $pushMenu */
        /** @var ItemInterface&MockObject $pushMenu */
        $pushMenu = $this->createMock(ItemInterface::class);

        // Mock the main menu structure
        $menuItem->expects($this->exactly(2))
            ->method('getChild')
            ->with('企业微信管理')
            ->willReturnOnConsecutiveCalls(null, $wechatWorkMenu)
        ;

        $menuItem->expects($this->once())
            ->method('addChild')
            ->with('企业微信管理')
            ->willReturn($wechatWorkMenu)
        ;

        $wechatWorkMenu->expects($this->once())
            ->method('setAttribute')
            ->with('icon', 'fas fa-wechat')
            ->willReturn($wechatWorkMenu)
        ;

        // Mock the push menu structure - return null for push menu
        $wechatWorkMenu->expects($this->exactly(2))
            ->method('getChild')
            ->with('应用推送')
            ->willReturnOnConsecutiveCalls(null, null)
        ;

        $wechatWorkMenu->expects($this->once())
            ->method('addChild')
            ->with('应用推送')
            ->willReturn($pushMenu)
        ;

        $pushMenu->expects($this->once())
            ->method('setAttribute')
            ->with('icon', 'fas fa-paper-plane')
            ->willReturn($pushMenu)
        ;

        // Should return early, no further calls expected

        // Invoke the service
        $this->service->__invoke($menuItem);
    }

    public function testServiceImplementsMenuProviderInterface(): void
    {
        $this->assertInstanceOf(MenuProviderInterface::class, $this->service);
    }
}
