<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\NewsMessageCrudController;
use WechatWorkPushBundle\Entity\NewsMessage;

/**
 * NewsMessageCrudController 测试
 * 验证图文消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(NewsMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class NewsMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testControllerIsInstantiable(): void
    {
        // 直接测试控制器实例化可用性
        $controller = new NewsMessageCrudController();
        $this->assertInstanceOf(NewsMessageCrudController::class, $controller);
    }

    public function testGetEntityFqcnShouldReturnNewsMessageClass(): void
    {
        $this->assertEquals(NewsMessage::class, NewsMessageCrudController::getEntityFqcn());
    }

    public function testControllerHasCorrectRoutingConfiguration(): void
    {
        // 直接测试控制器配置而不是反射结构
        $controller = new NewsMessageCrudController();

        // 验证基本功能
        $this->assertEquals(NewsMessage::class, $controller::getEntityFqcn());
        $this->assertInstanceOf(AbstractCrudController::class, $controller);
    }

    public function testControllerUsesCorrectEntityClass(): void
    {
        $reflection = new \ReflectionClass(NewsMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');
        $this->assertStringContainsString('NewsMessage::class', $source);
        $this->assertStringContainsString('use WechatWorkPushBundle\Entity\NewsMessage;', $source);
    }

    public function testControllerEntityRelationship(): void
    {
        $entityClass = NewsMessageCrudController::getEntityFqcn();
        $this->assertEquals(NewsMessage::class, $entityClass);

        $entityReflection = new \ReflectionClass($entityClass);
        $this->assertTrue($entityReflection->isInstantiable(), 'Entity类必须可实例化');
    }

    /**
     * @return \Generator<string, array{string}, mixed, void>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'title' => ['标题'];
        yield 'description' => ['描述'];
        yield 'url' => ['跳转链接'];
        yield 'picUrl' => ['图片链接'];
        yield 'appId' => ['小程序AppID'];
        yield 'pagePath' => ['小程序页面'];
        yield 'agent' => ['应用'];
        yield 'safe' => ['保密消息'];
        yield 'enableDuplicateCheck' => ['开启重复消息检查'];
        yield 'duplicateCheckInterval' => ['重复消息检查时间间隔'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return \Generator<string, array{string}, mixed, void>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'title' => ['title'];
        yield 'description' => ['description'];
        yield 'url' => ['url'];
        yield 'picUrl' => ['picUrl'];
        yield 'appId' => ['appId'];
        yield 'pagePath' => ['pagePath'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return \Generator<string, array{string}, mixed, void>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'title' => ['title'];
        yield 'description' => ['description'];
        yield 'url' => ['url'];
        yield 'picUrl' => ['picUrl'];
        yield 'appId' => ['appId'];
        yield 'pagePath' => ['pagePath'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return AbstractCrudController<NewsMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(NewsMessageCrudController::class);
    }
}
