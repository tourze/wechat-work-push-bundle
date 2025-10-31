<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\MpnewsMessageCrudController;
use WechatWorkPushBundle\Entity\MpnewsMessage;

/**
 * MpnewsMessageCrudController 测试
 * 验证图文消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(MpnewsMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class MpnewsMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testControllerIsInstantiable(): void
    {
        $reflection = new \ReflectionClass(MpnewsMessageCrudController::class);

        $this->assertTrue($reflection->isInstantiable());
        $this->assertTrue($reflection->isFinal());
    }

    public function testGetEntityFqcnShouldReturnMpnewsMessageClass(): void
    {
        $this->assertEquals(MpnewsMessage::class, MpnewsMessageCrudController::getEntityFqcn());
    }

    public function testControllerHasCorrectAdminCrudAttribute(): void
    {
        $reflection = new \ReflectionClass(MpnewsMessageCrudController::class);
        $attributes = $reflection->getAttributes();

        $hasAdminCrudAttribute = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'AdminCrud')) {
                $hasAdminCrudAttribute = true;

                $arguments = $attribute->getArguments();
                $this->assertArrayHasKey('routePath', $arguments);
                $this->assertEquals('/wechat-work-push/mpnews-message', $arguments['routePath']);
                $this->assertArrayHasKey('routeName', $arguments);
                $this->assertEquals('wechat_work_push_mpnews_message', $arguments['routeName']);
                break;
            }
        }

        $this->assertTrue($hasAdminCrudAttribute, 'Controller应该有AdminCrud注解');
    }

    public function testControllerUsesCorrectEntityClass(): void
    {
        $reflection = new \ReflectionClass(MpnewsMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');
        $this->assertStringContainsString('MpnewsMessage::class', $source);
        $this->assertStringContainsString('use WechatWorkPushBundle\Entity\MpnewsMessage;', $source);
    }

    public function testControllerEntityRelationship(): void
    {
        $entityClass = MpnewsMessageCrudController::getEntityFqcn();
        $this->assertEquals(MpnewsMessage::class, $entityClass);

        $entityReflection = new \ReflectionClass($entityClass);
        $this->assertTrue($entityReflection->isInstantiable(), 'Entity类必须可实例化');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'title' => ['标题'];
        yield 'content' => ['内容'];
        yield 'thumbMediaUrl' => ['缩略图URL'];
        yield 'thumbMediaId' => ['缩略图媒体ID'];
        yield 'digest' => ['描述'];
        yield 'contentSourceUrl' => ['阅读原文链接'];
        yield 'agent' => ['应用'];
        yield 'safe' => ['保密消息'];
        yield 'enableDuplicateCheck' => ['开启重复消息检查'];
        yield 'duplicateCheckInterval' => ['重复消息检查时间间隔'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'title' => ['title'];
        yield 'content' => ['content'];
        yield 'thumbMediaUrl' => ['thumbMediaUrl'];
        yield 'digest' => ['digest'];
        yield 'contentSourceUrl' => ['contentSourceUrl'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'title' => ['title'];
        yield 'content' => ['content'];
        yield 'thumbMediaUrl' => ['thumbMediaUrl'];
        yield 'thumbMediaId' => ['thumbMediaId'];
        yield 'digest' => ['digest'];
        yield 'contentSourceUrl' => ['contentSourceUrl'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return AbstractCrudController<MpnewsMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(MpnewsMessageCrudController::class);
    }
}
