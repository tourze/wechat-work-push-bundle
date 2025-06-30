<?php

namespace WechatWorkPushBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatWorkPushBundle\DependencyInjection\WechatWorkPushExtension;

class WechatWorkPushExtensionTest extends TestCase
{
    private WechatWorkPushExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new WechatWorkPushExtension();
        $this->container = new ContainerBuilder();
    }

    public function test_load_loadsServicesSuccessfully(): void
    {
        $configs = [];
        
        $this->expectNotToPerformAssertions();
        $this->extension->load($configs, $this->container);
    }

    public function test_load_withEmptyConfig(): void
    {
        $configs = [[]];
        
        $this->expectNotToPerformAssertions();
        $this->extension->load($configs, $this->container);
    }
}