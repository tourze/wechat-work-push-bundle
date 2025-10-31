<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\MarkdownMessageCrudController;
use WechatWorkPushBundle\Entity\MarkdownMessage;

/**
 * MarkdownMessageCrudController 测试
 * 验证Markdown消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(MarkdownMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class MarkdownMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testControllerIsInstantiable(): void
    {
        $reflection = new \ReflectionClass(MarkdownMessageCrudController::class);

        $this->assertTrue($reflection->isInstantiable());
        $this->assertTrue($reflection->isFinal());
    }

    public function testGetEntityFqcnShouldReturnMarkdownMessageClass(): void
    {
        $this->assertEquals(MarkdownMessage::class, MarkdownMessageCrudController::getEntityFqcn());
    }

    public function testControllerHasCorrectAdminCrudAttribute(): void
    {
        $reflection = new \ReflectionClass(MarkdownMessageCrudController::class);
        $attributes = $reflection->getAttributes();

        $hasAdminCrudAttribute = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'AdminCrud')) {
                $hasAdminCrudAttribute = true;

                $arguments = $attribute->getArguments();
                $this->assertArrayHasKey('routePath', $arguments);
                $this->assertEquals('/wechat-work-push/markdown-message', $arguments['routePath']);
                $this->assertArrayHasKey('routeName', $arguments);
                $this->assertEquals('wechat_work_push_markdown_message', $arguments['routeName']);
                break;
            }
        }

        $this->assertTrue($hasAdminCrudAttribute, 'Controller应该有AdminCrud注解');
    }

    public function testControllerUsesCorrectEntityClass(): void
    {
        $reflection = new \ReflectionClass(MarkdownMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');
        $this->assertStringContainsString('MarkdownMessage::class', $source);
        $this->assertStringContainsString('use WechatWorkPushBundle\Entity\MarkdownMessage;', $source);
    }

    public function testControllerHasExpectedMarkdownMessageFieldsConfiguration(): void
    {
        $reflection = new \ReflectionClass(MarkdownMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');

        $expectedFields = [
            'content',
            'agent',
            'enableIdTrans',
            'enableDuplicateCheck',
            'duplicateCheckInterval',
        ];

        foreach ($expectedFields as $field) {
            $this->assertStringContainsString(
                $field,
                $source,
                "应该包含Markdown消息字段: {$field}"
            );
        }
    }

    public function testControllerEntityRelationship(): void
    {
        $entityClass = MarkdownMessageCrudController::getEntityFqcn();
        $this->assertEquals(MarkdownMessage::class, $entityClass);

        $entityReflection = new \ReflectionClass($entityClass);
        $this->assertTrue($entityReflection->isInstantiable(), 'Entity类必须可实例化');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'Markdown内容' => ['Markdown内容'];
        yield '应用' => ['应用'];
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
        yield 'content' => ['content'];
        yield 'agent' => ['agent'];
        yield 'enableIdTrans' => ['enableIdTrans'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'content' => ['content'];
        yield 'agent' => ['agent'];
        yield 'enableIdTrans' => ['enableIdTrans'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return AbstractCrudController<MarkdownMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(MarkdownMessageCrudController::class);
    }
}
