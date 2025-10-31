<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\TextCardMessageCrudController;
use WechatWorkPushBundle\Entity\TextCardMessage;

/**
 * TextCardMessageCrudController 测试
 * 验证文本卡片消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(TextCardMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class TextCardMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcnShouldReturnTextCardMessageClass(): void
    {
        $this->assertEquals(TextCardMessage::class, TextCardMessageCrudController::getEntityFqcn());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'title' => ['标题'];
        yield 'description' => ['描述'];
        yield 'url' => ['跳转链接'];
        yield 'btnText' => ['按钮文字'];
        yield 'agent' => ['应用'];
        yield 'enableIdTrans' => ['开启id转译'];
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
        yield 'url' => ['url'];
        yield 'btnText' => ['btnText'];
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
        yield 'title' => ['title'];
        yield 'description' => ['description'];
        yield 'url' => ['url'];
        yield 'btnText' => ['btnText'];
        yield 'agent' => ['agent'];
        yield 'enableIdTrans' => ['enableIdTrans'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return AbstractCrudController<TextCardMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(TextCardMessageCrudController::class);
    }
}
