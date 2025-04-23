<?php

namespace WechatWorkPushBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;

#[AsPermission(title: '企业微信推送')]
class WechatWorkPushBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            \WechatWorkBundle\WechatWorkBundle::class => ['all' => true],
            \WechatWorkMediaBundle\WechatWorkMediaBundle::class => ['all' => true],
        ];
    }
}
