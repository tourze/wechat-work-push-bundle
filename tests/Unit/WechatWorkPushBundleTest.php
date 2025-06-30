<?php

namespace WechatWorkPushBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatWorkPushBundle\DependencyInjection\WechatWorkPushExtension;
use WechatWorkPushBundle\WechatWorkPushBundle;

class WechatWorkPushBundleTest extends TestCase
{
    private WechatWorkPushBundle $bundle;

    protected function setUp(): void
    {
        $this->bundle = new WechatWorkPushBundle();
    }

    public function test_getContainerExtension_returnsCorrectExtension(): void
    {
        $extension = $this->bundle->getContainerExtension();
        
        $this->assertInstanceOf(WechatWorkPushExtension::class, $extension);
    }

    public function test_build_doesNotThrowException(): void
    {
        $container = new ContainerBuilder();
        
        $this->expectNotToPerformAssertions();
        $this->bundle->build($container);
    }
}