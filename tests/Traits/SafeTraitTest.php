<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * @internal
 */
#[CoversClass(SafeTrait::class)]
final class SafeTraitTest extends TestCase
{
    private TestSafeEntity $testObject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testObject = new TestSafeEntity();
    }

    public function testSetSafeWithTrue(): void
    {
        $this->testObject->setSafe(true);

        $this->assertTrue($this->testObject->isSafe());
    }

    public function testSetSafeWithFalse(): void
    {
        $this->testObject->setSafe(false);

        $this->assertFalse($this->testObject->isSafe());
    }

    public function testSetSafeWithNull(): void
    {
        $this->testObject->setSafe(null);
        $this->assertNull($this->testObject->isSafe());
    }

    public function testGetSafeArrayWithTrue(): void
    {
        $this->testObject->setSafe(true);

        $expectedArray = [
            'safe' => 1,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getSafeArray());
    }

    public function testGetSafeArrayWithFalse(): void
    {
        $this->testObject->setSafe(false);

        $expectedArray = [
            'safe' => 0,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getSafeArray());
    }

    public function testGetSafeArrayWithNull(): void
    {
        $this->testObject->setSafe(null);

        $expectedArray = [
            'safe' => 0,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getSafeArray());
    }

    public function testGetSafeArrayWithDefaultValue(): void
    {
        // 未设置 safe 时的默认行为（默认为 false）
        $expectedArray = [
            'safe' => 0,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getSafeArray());
    }

    public function testEdgeCasesToggleSafeValue(): void
    {
        // 测试多次切换 safe 值
        $this->testObject->setSafe(true);
        $this->assertTrue($this->testObject->isSafe());

        $this->testObject->setSafe(false);
        $this->assertFalse($this->testObject->isSafe());

        $this->testObject->setSafe(true);
        $this->assertTrue($this->testObject->isSafe());
    }

    public function testFluentInterface(): void
    {
        // 测试流畅接口
        $this->testObject->setSafe(true);
        $this->testObject->setSafe(false);
        $this->testObject->setSafe(true);

        $this->assertTrue($this->testObject->isSafe());
    }
}
