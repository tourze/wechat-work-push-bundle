<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\VideoMessageCrudController;
use WechatWorkPushBundle\Entity\VideoMessage;

/**
 * VideoMessageCrudController 测试
 * 验证视频消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(VideoMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class VideoMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcnShouldReturnVideoMessageClass(): void
    {
        $this->assertEquals(VideoMessage::class, VideoMessageCrudController::getEntityFqcn());
    }

    public function testControllerHasCorrectAdminCrudAttribute(): void
    {
        $reflection = new \ReflectionClass(VideoMessageCrudController::class);
        $attributes = $reflection->getAttributes();

        $hasAdminCrudAttribute = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'AdminCrud')) {
                $hasAdminCrudAttribute = true;

                $arguments = $attribute->getArguments();
                $this->assertArrayHasKey('routePath', $arguments);
                $this->assertEquals('/wechat-work-push/video-message', $arguments['routePath']);
                $this->assertArrayHasKey('routeName', $arguments);
                $this->assertEquals('wechat_work_push_video_message', $arguments['routeName']);
                break;
            }
        }

        $this->assertTrue($hasAdminCrudAttribute, 'Controller应该有AdminCrud注解');
    }

    public function testControllerUsesCorrectEntityClass(): void
    {
        $reflection = new \ReflectionClass(VideoMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');
        $this->assertStringContainsString('VideoMessage::class', $source);
        $this->assertStringContainsString('use WechatWorkPushBundle\Entity\VideoMessage;', $source);
    }

    public function testControllerHasExpectedVideoMessageFieldsConfiguration(): void
    {
        $reflection = new \ReflectionClass(VideoMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');

        $expectedFields = [
            'mediaId',
            'title',
            'description',
            'agent',
            'safe',
            'enableDuplicateCheck',
            'duplicateCheckInterval',
        ];

        foreach ($expectedFields as $field) {
            $this->assertStringContainsString(
                $field,
                $source,
                "应该包含视频消息字段: {$field}"
            );
        }
    }

    public function testControllerEntityRelationship(): void
    {
        $entityClass = VideoMessageCrudController::getEntityFqcn();
        $this->assertEquals(VideoMessage::class, $entityClass);

        $entityReflection = new \ReflectionClass($entityClass);
        $this->assertTrue($entityReflection->isInstantiable(), 'Entity类必须可实例化');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'mediaId' => ['视频媒体ID'];
        yield 'title' => ['视频标题'];
        yield 'description' => ['视频描述'];
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
        yield 'mediaId' => ['mediaId'];
        yield 'title' => ['title'];
        yield 'description' => ['description'];
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
        yield 'title' => ['title'];
        yield 'description' => ['description'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return AbstractCrudController<VideoMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(VideoMessageCrudController::class);
    }
}
