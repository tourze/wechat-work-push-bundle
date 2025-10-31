<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatWorkPushBundle\WechatWorkPushBundle;

/**
 * @internal
 */
#[CoversClass(WechatWorkPushBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatWorkPushBundleTest extends AbstractBundleTestCase
{
}
