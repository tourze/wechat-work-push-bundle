<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\NewsTemplateMessageCrudController;
use WechatWorkPushBundle\Entity\NewsTemplateMessage;

/**
 * NewsTemplateMessageCrudController 测试
 * 验证图文模板消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(NewsTemplateMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class NewsTemplateMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcnShouldReturnNewsTemplateMessageClass(): void
    {
        $this->assertEquals(NewsTemplateMessage::class, NewsTemplateMessageCrudController::getEntityFqcn());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'title' => ['标题'];
        yield 'description' => ['描述'];
        yield 'taskId' => ['任务ID'];
        yield 'url' => ['跳转链接'];
        yield 'imageUrl' => ['图片链接'];
        yield 'btnText' => ['底部按钮文字'];
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
        yield 'description' => ['description'];
        yield 'taskId' => ['taskId'];
        yield 'url' => ['url'];
        yield 'imageUrl' => ['imageUrl'];
        yield 'btnText' => ['btnText'];
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
        yield 'description' => ['description'];
        yield 'taskId' => ['taskId'];
        yield 'url' => ['url'];
        yield 'imageUrl' => ['imageUrl'];
        yield 'btnText' => ['btnText'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return AbstractCrudController<NewsTemplateMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(NewsTemplateMessageCrudController::class);
    }
}
