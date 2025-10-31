<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\VoiceMessageCrudController;
use WechatWorkPushBundle\Entity\VoiceMessage;

/**
 * VoiceMessageCrudController 测试
 * 验证语音消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(VoiceMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class VoiceMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcnShouldReturnVoiceMessageClass(): void
    {
        $this->assertEquals(VoiceMessage::class, VoiceMessageCrudController::getEntityFqcn());
    }

    public function testControllerHasCorrectAdminCrudAttribute(): void
    {
        $reflection = new \ReflectionClass(VoiceMessageCrudController::class);
        $attributes = $reflection->getAttributes();

        $hasAdminCrudAttribute = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'AdminCrud')) {
                $hasAdminCrudAttribute = true;

                $arguments = $attribute->getArguments();
                $this->assertArrayHasKey('routePath', $arguments);
                $this->assertEquals('/wechat-work-push/voice-message', $arguments['routePath']);
                $this->assertArrayHasKey('routeName', $arguments);
                $this->assertEquals('wechat_work_push_voice_message', $arguments['routeName']);
                break;
            }
        }

        $this->assertTrue($hasAdminCrudAttribute, 'Controller应该有AdminCrud注解');
    }

    public function testControllerUsesCorrectEntityClass(): void
    {
        $reflection = new \ReflectionClass(VoiceMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');
        $this->assertStringContainsString('VoiceMessage::class', $source);
        $this->assertStringContainsString('use WechatWorkPushBundle\Entity\VoiceMessage;', $source);
    }

    public function testControllerHasExpectedVoiceMessageFieldsConfiguration(): void
    {
        $reflection = new \ReflectionClass(VoiceMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');

        $expectedFields = [
            'mediaId',
            'agent',
            'safe',
            'enableDuplicateCheck',
            'duplicateCheckInterval',
        ];

        foreach ($expectedFields as $field) {
            $this->assertStringContainsString(
                $field,
                $source,
                "应该包含语音消息字段: {$field}"
            );
        }
    }

    public function testControllerEntityRelationship(): void
    {
        $entityClass = VoiceMessageCrudController::getEntityFqcn();
        $this->assertEquals(VoiceMessage::class, $entityClass);

        $entityReflection = new \ReflectionClass($entityClass);
        $this->assertTrue($entityReflection->isInstantiable(), 'Entity类必须可实例化');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'mediaId' => ['语音媒体ID'];
        yield 'agent' => ['应用'];
        yield 'safe' => ['保密消息'];
        yield 'enableDuplicateCheck' => ['开启重复消息检查'];
        yield 'duplicateCheckInterval' => ['重复消息检查时间间隔'];
        yield 'createTime' => ['创建时间'];
        yield 'updateTime' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'mediaId' => ['mediaId'];
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
        yield 'mediaId' => ['mediaId'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return AbstractCrudController<VoiceMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(VoiceMessageCrudController::class);
    }
}
