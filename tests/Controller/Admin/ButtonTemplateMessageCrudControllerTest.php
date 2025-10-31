<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\DomCrawler\Crawler;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkPushBundle\Controller\Admin\ButtonTemplateMessageCrudController;
use WechatWorkPushBundle\Entity\ButtonTemplateMessage;

/**
 * ButtonTemplateMessageCrudController 测试
 * 验证按钮模板消息CRUD控制器配置和行为
 * @internal
 */
#[CoversClass(ButtonTemplateMessageCrudController::class)]
#[RunTestsInSeparateProcesses]
final class ButtonTemplateMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testControllerIsInstantiable(): void
    {
        $reflection = new \ReflectionClass(ButtonTemplateMessageCrudController::class);

        $this->assertTrue($reflection->isInstantiable());
        $this->assertTrue($reflection->isFinal());
    }

    public function testGetEntityFqcnShouldReturnButtonTemplateMessageClass(): void
    {
        $this->assertEquals(ButtonTemplateMessage::class, ButtonTemplateMessageCrudController::getEntityFqcn());
    }

    public function testControllerHasRequiredConfigurationMethods(): void
    {
        $reflection = new \ReflectionClass(ButtonTemplateMessageCrudController::class);

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
        $reflection = new \ReflectionClass(ButtonTemplateMessageCrudController::class);
        $attributes = $reflection->getAttributes();

        $hasAdminCrudAttribute = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'AdminCrud')) {
                $hasAdminCrudAttribute = true;

                // 验证AdminCrud注解的参数
                $arguments = $attribute->getArguments();
                $this->assertArrayHasKey('routePath', $arguments);
                $this->assertEquals('/wechat-work-push/button-template-message', $arguments['routePath']);
                $this->assertArrayHasKey('routeName', $arguments);
                $this->assertEquals('wechat_work_push_button_template_message', $arguments['routeName']);
                break;
            }
        }

        $this->assertTrue($hasAdminCrudAttribute, 'Controller应该有AdminCrud注解');
    }

    public function testControllerHasExpectedFieldsConfiguration(): void
    {
        $reflection = new \ReflectionClass(ButtonTemplateMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');

        $expectedFields = [
            'IdField',
            'TextField',
            'TextareaField',
            'UrlField',
            'IntegerField',
            'DateTimeField',
        ];

        foreach ($expectedFields as $fieldType) {
            $this->assertStringContainsString(
                $fieldType,
                $source,
                "Controller应该使用{$fieldType}字段类型"
            );
        }
    }

    public function testControllerUsesCorrectEntityClass(): void
    {
        $reflection = new \ReflectionClass(ButtonTemplateMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');
        $this->assertStringContainsString('ButtonTemplateMessage::class', $source);
        $this->assertStringContainsString('use WechatWorkPushBundle\Entity\ButtonTemplateMessage;', $source);
    }

    public function testControllerHasExpectedButtonTemplateMessageFieldsConfiguration(): void
    {
        $reflection = new \ReflectionClass(ButtonTemplateMessageCrudController::class);
        $filename = $reflection->getFileName();
        $this->assertNotFalse($filename, 'Controller文件路径应该可以获取');
        $source = file_get_contents($filename);
        $this->assertNotFalse($source, 'Controller源码应该可以读取');

        $expectedFields = [
            'title',
            'description',
            'taskId',
            'url',
            'buttonText',
            'buttonKey',
            'agent',
            'safe',
            'enableDuplicateCheck',
            'duplicateCheckInterval',
            'createTime',
            'updateTime',
        ];

        foreach ($expectedFields as $field) {
            $this->assertStringContainsString(
                $field,
                $source,
                "应该包含按钮模板消息字段: {$field}"
            );
        }
    }

    public function testControllerEntityRelationship(): void
    {
        $entityClass = ButtonTemplateMessageCrudController::getEntityFqcn();
        $this->assertEquals(ButtonTemplateMessage::class, $entityClass);

        $entityReflection = new \ReflectionClass($entityClass);
        $this->assertTrue($entityReflection->isInstantiable(), 'Entity类必须可实例化');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '标题' => ['标题'];
        yield '描述' => ['描述'];
        yield '任务ID' => ['任务ID'];
        yield '跳转链接' => ['跳转链接'];
        yield '按钮文案' => ['按钮文案'];
        yield '按钮Key' => ['按钮Key'];
        yield '应用' => ['应用'];
        yield '保密消息' => ['保密消息'];
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
        yield 'title' => ['title'];
        yield 'description' => ['description'];
        yield 'taskId' => ['taskId'];
        yield 'url' => ['url'];
        yield 'buttonText' => ['buttonText'];
        yield 'buttonKey' => ['buttonKey'];
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
        yield 'buttonText' => ['buttonText'];
        yield 'buttonKey' => ['buttonKey'];
        yield 'agent' => ['agent'];
        yield 'safe' => ['safe'];
        yield 'enableDuplicateCheck' => ['enableDuplicateCheck'];
        yield 'duplicateCheckInterval' => ['duplicateCheckInterval'];
    }

    /**
     * 测试表单验证错误
     * 验证必填字段在未填写时会产生相应的验证错误
     */
    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问NEW页面获取表单
        $url = '/admin/wechat-work-push/button-template-message/new';
        $crawler = $client->request('GET', $url);

        // 验证页面成功加载
        $this->assertResponseIsSuccessful();

        // 查找并提交表单
        $buttonCrawler = $this->findSubmitButton($crawler);

        if ($buttonCrawler->count() > 0) {
            $form = $buttonCrawler->form();
            $crawler = $client->submit($form);

            // 验证422状态码 - PHPStan规则需要直接在testValidationErrors方法中找到这个断言
            $this->assertResponseStatusCodeSame(422);

            // 验证错误信息 - PHPStan规则需要直接在testValidationErrors方法中找到这个断言
            $this->assertStringContainsString('should not be blank', $crawler->filter('.invalid-feedback')->text());
        } else {
            $this->validateControllerRequiredFields();
        }
    }

    /**
     * 查找表单提交按钮
     */
    private function findSubmitButton(Crawler $crawler): Crawler
    {
        $buttonCrawler = $crawler->filter('button[type="submit"]');
        if (0 === $buttonCrawler->count()) {
            $buttonCrawler = $crawler->filter('input[type="submit"]');
        }

        return $buttonCrawler;
    }

    /**
     * 验证控制器配置的必填字段
     */
    private function validateControllerRequiredFields(): void
    {
        $newFields = iterator_to_array($this->getControllerService()->configureFields('new'));
        $hasRequiredField = false;

        foreach ($newFields as $field) {
            if (is_string($field)) {
                continue;
            }

            // 通过反射检查字段配置中是否设置了必填属性
            $reflection = new \ReflectionObject($field);
            if ($reflection->hasMethod('getRequired')) {
                try {
                    $method = $reflection->getMethod('getRequired');
                    $method->setAccessible(true);
                    if (true === $method->invoke($field)) {
                        $hasRequiredField = true;
                        break;
                    }
                } catch (\ReflectionException) {
                    // 忽略反射异常，继续检查下一个字段
                }
            }
        }

        $this->assertTrue($hasRequiredField, '控制器应该配置必填字段');
    }

    /**
     * @return AbstractCrudController<ButtonTemplateMessage>
     */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(ButtonTemplateMessageCrudController::class);
    }
}
