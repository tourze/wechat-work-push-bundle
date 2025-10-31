<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use WechatWorkPushBundle\DependencyInjection\WechatWorkPushExtension;

/**
 * @internal
 */
#[CoversClass(WechatWorkPushExtension::class)]
final class WechatWorkPushExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private WechatWorkPushExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension = new WechatWorkPushExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    public function testLoadLoadsServicesSuccessfully(): void
    {
        $configs = [];

        $this->expectNotToPerformAssertions();
        $this->extension->load($configs, $this->container);
    }

    public function testLoadWithEmptyConfig(): void
    {
        $configs = [[]];

        $this->expectNotToPerformAssertions();
        $this->extension->load($configs, $this->container);
    }
}
