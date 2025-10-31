<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\FileMessageCrudController;
use WechatWorkPushBundle\Entity\FileMessage;

/**
 * FileMessageCrudController 测试
 * 验证文件消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(FileMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class FileMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testControllerIsInstantiable(): void
    {
        $reflection = new \ReflectionClass(FileMessageCrudController::class);

        $this->assertTrue($reflection->isInstantiable());
        $this->assertTrue($reflection->isFinal());
    }

    public function testGetEntityFqcnShouldReturnFileMessageClass(): void
    {
        $this->assertEquals(FileMessage::class, FileMessageCrudController::getEntityFqcn());
    }

    public function testControllerHasCorrectAdminCrudAttribute(): void
    {
        $reflection = new \ReflectionClass(FileMessageCrudController::class);
        $attributes = $reflection->getAttributes();

        $hasAdminCrudAttribute = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'AdminCrud')) {
                $hasAdminCrudAttribute = true;

                $arguments = $attribute->getArguments();
                $this->assertArrayHasKey('routePath', $arguments);
                $this->assertEquals('/wechat-work-push/file-message', $arguments['routePath']);
                $this->assertArrayHasKey('routeName', $arguments);
                $this->assertEquals('wechat_work_push_file_message', $arguments['routeName']);
                break;
            }
        }

        $this->assertTrue($hasAdminCrudAttribute, 'Controller应该有AdminCrud注解');
    }

    public function testControllerUsesCorrectEntityClass(): void
    {
        $reflection = new \ReflectionClass(FileMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');
        $this->assertStringContainsString('FileMessage::class', $source);
        $this->assertStringContainsString('use WechatWorkPushBundle\Entity\FileMessage;', $source);
    }

    public function testControllerHasExpectedFileMessageFieldsConfiguration(): void
    {
        $reflection = new \ReflectionClass(FileMessageCrudController::class);
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
        ];

        foreach ($expectedFields as $field) {
            $this->assertStringContainsString(
                $field,
                $source,
                "应该包含文件消息字段: {$field}"
            );
        }
    }

    public function testControllerEntityRelationship(): void
    {
        $entityClass = FileMessageCrudController::getEntityFqcn();
        $this->assertEquals(FileMessage::class, $entityClass);

        $entityReflection = new \ReflectionClass($entityClass);
        $this->assertTrue($entityReflection->isInstantiable(), 'Entity类必须可实例化');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '文件媒体ID' => ['文件媒体ID'];
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
     * @return AbstractCrudController<FileMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(FileMessageCrudController::class);
    }
}
