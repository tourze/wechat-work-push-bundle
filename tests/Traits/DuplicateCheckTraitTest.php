<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;

class DuplicateCheckTraitTest extends TestCase
{
    private DuplicateCheckTraitTestClass $testObject;

    protected function setUp(): void
    {
        $this->testObject = new DuplicateCheckTraitTestClass();
    }

    public function test_setEnableDuplicateCheck_withTrue(): void
    {
        $result = $this->testObject->setEnableDuplicateCheck(true);
        
        $this->assertSame($this->testObject, $result);
        $this->assertTrue($this->testObject->isEnableDuplicateCheck());
    }

    public function test_setEnableDuplicateCheck_withFalse(): void
    {
        $result = $this->testObject->setEnableDuplicateCheck(false);
        
        $this->assertSame($this->testObject, $result);
        $this->assertFalse($this->testObject->isEnableDuplicateCheck());
    }

    public function test_setEnableDuplicateCheck_withNull(): void
    {
        $this->testObject->setEnableDuplicateCheck(null);
        $this->assertNull($this->testObject->isEnableDuplicateCheck());
    }

    public function test_setDuplicateCheckInterval_withValidInterval(): void
    {
        $interval = 3600;
        $result = $this->testObject->setDuplicateCheckInterval($interval);
        
        $this->assertSame($this->testObject, $result);
        $this->assertEquals($interval, $this->testObject->getDuplicateCheckInterval());
    }

    public function test_setDuplicateCheckInterval_withNull(): void
    {
        $this->testObject->setDuplicateCheckInterval(null);
        $this->assertNull($this->testObject->getDuplicateCheckInterval());
    }

    public function test_setDuplicateCheckInterval_withZero(): void
    {
        $this->testObject->setDuplicateCheckInterval(0);
        $this->assertEquals(0, $this->testObject->getDuplicateCheckInterval());
    }

    public function test_setDuplicateCheckInterval_withMaxValue(): void
    {
        $maxInterval = 604800; // 7天
        $this->testObject->setDuplicateCheckInterval($maxInterval);
        $this->assertEquals($maxInterval, $this->testObject->getDuplicateCheckInterval());
    }

    public function test_getDuplicateCheckArray_withEnabledTrue(): void
    {
        $this->testObject->setEnableDuplicateCheck(true);
        $this->testObject->setDuplicateCheckInterval(3600);
        
        $expectedArray = [
            'enable_duplicate_check' => 1,
            'duplicate_check_interval' => 3600
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function test_getDuplicateCheckArray_withEnabledFalse(): void
    {
        $this->testObject->setEnableDuplicateCheck(false);
        $this->testObject->setDuplicateCheckInterval(3600);
        
        $expectedArray = [
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 3600
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function test_getDuplicateCheckArray_withNullEnabled(): void
    {
        $this->testObject->setEnableDuplicateCheck(null);
        $this->testObject->setDuplicateCheckInterval(1800);
        
        $expectedArray = [
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function test_getDuplicateCheckArray_withDefaultValues(): void
    {
        // 测试默认值：enable_duplicate_check = false, duplicate_check_interval = 1800
        $expectedArray = [
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function test_getDuplicateCheckArray_withNullInterval(): void
    {
        $this->testObject->setEnableDuplicateCheck(true);
        $this->testObject->setDuplicateCheckInterval(null);
        
        $expectedArray = [
            'enable_duplicate_check' => 1,
            'duplicate_check_interval' => null
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function test_edgeCases_minInterval(): void
    {
        $this->testObject->setDuplicateCheckInterval(1);
        $this->assertEquals(1, $this->testObject->getDuplicateCheckInterval());
    }

    public function test_edgeCases_largeInterval(): void
    {
        $largeInterval = 999999;
        $this->testObject->setDuplicateCheckInterval($largeInterval);
        $this->assertEquals($largeInterval, $this->testObject->getDuplicateCheckInterval());
    }

    public function test_fluentInterface(): void
    {
        $result = $this->testObject
            ->setEnableDuplicateCheck(true)
            ->setDuplicateCheckInterval(7200);
        
        $this->assertSame($this->testObject, $result);
        $this->assertTrue($this->testObject->isEnableDuplicateCheck());
        $this->assertEquals(7200, $this->testObject->getDuplicateCheckInterval());
    }

    public function test_toggleEnabledState(): void
    {
        // 测试多次切换启用状态
        $this->testObject->setEnableDuplicateCheck(true);
        $this->assertTrue($this->testObject->isEnableDuplicateCheck());
        
        $this->testObject->setEnableDuplicateCheck(false);
        $this->assertFalse($this->testObject->isEnableDuplicateCheck());
        
        $this->testObject->setEnableDuplicateCheck(true);
        $this->assertTrue($this->testObject->isEnableDuplicateCheck());
    }
}

/**
 * 用于测试 DuplicateCheckTrait 的具体实现类
 */
class DuplicateCheckTraitTestClass
{
    use DuplicateCheckTrait;
} 