<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Traits\SafeTrait;

class SafeTraitTest extends TestCase
{
    private SafeTraitTestClass $testObject;

    protected function setUp(): void
    {
        $this->testObject = new SafeTraitTestClass();
    }

    public function test_setSafe_withTrue(): void
    {
        $result = $this->testObject->setSafe(true);
        
        $this->assertSame($this->testObject, $result);
        $this->assertTrue($this->testObject->isSafe());
    }

    public function test_setSafe_withFalse(): void
    {
        $result = $this->testObject->setSafe(false);
        
        $this->assertSame($this->testObject, $result);
        $this->assertFalse($this->testObject->isSafe());
    }

    public function test_setSafe_withNull(): void
    {
        $this->testObject->setSafe(null);
        $this->assertNull($this->testObject->isSafe());
    }

    public function test_getSafeArray_withTrue(): void
    {
        $this->testObject->setSafe(true);
        
        $expectedArray = [
            'safe' => 1
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getSafeArray());
    }

    public function test_getSafeArray_withFalse(): void
    {
        $this->testObject->setSafe(false);
        
        $expectedArray = [
            'safe' => 0
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getSafeArray());
    }

    public function test_getSafeArray_withNull(): void
    {
        $this->testObject->setSafe(null);
        
        $expectedArray = [
            'safe' => 0
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getSafeArray());
    }

    public function test_getSafeArray_withDefaultValue(): void
    {
        // 未设置 safe 时的默认行为（默认为 false）
        $expectedArray = [
            'safe' => 0
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getSafeArray());
    }

    public function test_edgeCases_toggleSafeValue(): void
    {
        // 测试多次切换 safe 值
        $this->testObject->setSafe(true);
        $this->assertTrue($this->testObject->isSafe());
        
        $this->testObject->setSafe(false);
        $this->assertFalse($this->testObject->isSafe());
        
        $this->testObject->setSafe(true);
        $this->assertTrue($this->testObject->isSafe());
    }

    public function test_fluentInterface(): void
    {
        // 测试流畅接口
        $result = $this->testObject
            ->setSafe(true)
            ->setSafe(false)
            ->setSafe(true);
        
        $this->assertSame($this->testObject, $result);
        $this->assertTrue($this->testObject->isSafe());
    }
}

/**
 * 用于测试 SafeTrait 的具体实现类
 */
class SafeTraitTestClass
{
    use SafeTrait;
} 