<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\VoteTemplateMessageCrudController;
use WechatWorkPushBundle\Entity\VoteTemplateMessage;

/**
 * VoteTemplateMessageCrudController 测试
 * 验证投票模板消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(VoteTemplateMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class VoteTemplateMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcnShouldReturnVoteTemplateMessageClass(): void
    {
        $this->assertEquals(VoteTemplateMessage::class, VoteTemplateMessageCrudController::getEntityFqcn());
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
        yield 'questionKey' => ['选择题Key'];
        yield 'options' => ['选项列表'];
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
        yield 'questionKey' => ['questionKey'];
        // CollectionField (options) 需要JavaScript支持，在测试环境中跳过
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
        yield 'questionKey' => ['questionKey'];
        // CollectionField (options) 需要JavaScript支持，在测试环境中跳过
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * @return AbstractCrudController<VoteTemplateMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(VoteTemplateMessageCrudController::class);
    }
}
