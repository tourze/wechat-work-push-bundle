<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\ImageMessageCrudController;
use WechatWorkPushBundle\Entity\ImageMessage;

/**
 * ImageMessageCrudController 测试
 * 验证图片消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(ImageMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class ImageMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testControllerIsInstantiable(): void
    {
        $reflection = new \ReflectionClass(ImageMessageCrudController::class);

        $this->assertTrue($reflection->isInstantiable());
        $this->assertTrue($reflection->isFinal());
    }

    public function testGetEntityFqcnShouldReturnImageMessageClass(): void
    {
        $this->assertEquals(ImageMessage::class, ImageMessageCrudController::getEntityFqcn());
    }

    public function testControllerHasRequiredConfigurationMethods(): void
    {
        $reflection = new \ReflectionClass(ImageMessageCrudController::class);

        $requiredMethods = [
            'getEntityFqcn',
            'configureFields',
        ];

        foreach ($requiredMethods as $methodName) {
            $this->assertTrue($reflection->hasMethod($methodName), "方法 {$methodName} 必须存在");

            $method = $reflection->getMethod($methodName);
            $this->assertTrue($method->isPublic(), "方法 {$methodName} 必须是public");
        }
    }

    public function testControllerHasCorrectAdminCrudAttribute(): void
    {
        $reflection = new \ReflectionClass(ImageMessageCrudController::class);
        $attributes = $reflection->getAttributes();

        $hasAdminCrudAttribute = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'AdminCrud')) {
                $hasAdminCrudAttribute = true;

                $arguments = $attribute->getArguments();
                $this->assertArrayHasKey('routePath', $arguments);
                $this->assertEquals('/wechat-work-push/image-message', $arguments['routePath']);
                $this->assertArrayHasKey('routeName', $arguments);
                $this->assertEquals('wechat_work_push_image_message', $arguments['routeName']);
                break;
            }
        }

        $this->assertTrue($hasAdminCrudAttribute, 'Controller应该有AdminCrud注解');
    }

    public function testControllerUsesCorrectEntityClass(): void
    {
        $reflection = new \ReflectionClass(ImageMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');
        $this->assertStringContainsString('ImageMessage::class', $source);
        $this->assertStringContainsString('use WechatWorkPushBundle\Entity\ImageMessage;', $source);
    }

    public function testControllerHasExpectedImageMessageFieldsConfiguration(): void
    {
        $reflection = new \ReflectionClass(ImageMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');

        $expectedFields = [
            'mediaId',
            'agent',
            'safe',
            'enableIdTrans',
            'enableDuplicateCheck',
            'duplicateCheckInterval',
            'createTime',
            'updateTime',
        ];

        foreach ($expectedFields as $field) {
            $this->assertStringContainsString(
                $field,
                $source,
                "应该包含图片消息字段: {$field}"
            );
        }
    }

    public function testControllerEntityRelationship(): void
    {
        $entityClass = ImageMessageCrudController::getEntityFqcn();
        $this->assertEquals(ImageMessage::class, $entityClass);

        $entityReflection = new \ReflectionClass($entityClass);
        $this->assertTrue($entityReflection->isInstantiable(), 'Entity类必须可实例化');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '媒体ID' => ['媒体ID'];
        yield '应用' => ['应用'];
        yield '保密消息' => ['保密消息'];
        yield '开启id转译' => ['开启id转译'];
        yield '开启重复消息检查' => ['开启重复消息检查'];
        yield '重复消息检查时间间隔' => ['重复消息检查时间间隔'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'mediaId' => ['mediaId'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableIdTrans' => ['enableIdTrans'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'mediaId' => ['mediaId'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableIdTrans' => ['enableIdTrans'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return AbstractCrudController<ImageMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(ImageMessageCrudController::class);
    }
}
