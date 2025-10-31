<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;

/**
 * @internal
 */
#[CoversClass(DuplicateCheckTrait::class)]
final class DuplicateCheckTraitTest extends TestCase
{
    private TestDuplicateCheckEntity $testObject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testObject = new TestDuplicateCheckEntity();
    }

    public function testSetEnableDuplicateCheckWithTrue(): void
    {
        $this->testObject->setEnableDuplicateCheck(true);

        $this->assertTrue($this->testObject->isEnableDuplicateCheck());
    }

    public function testSetEnableDuplicateCheckWithFalse(): void
    {
        $this->testObject->setEnableDuplicateCheck(false);

        $this->assertFalse($this->testObject->isEnableDuplicateCheck());
    }

    public function testSetEnableDuplicateCheckWithNull(): void
    {
        $this->testObject->setEnableDuplicateCheck(null);
        $this->assertNull($this->testObject->isEnableDuplicateCheck());
    }

    public function testSetDuplicateCheckIntervalWithValidInterval(): void
    {
        $interval = 3600;
        $this->testObject->setDuplicateCheckInterval($interval);

        $this->assertEquals($interval, $this->testObject->getDuplicateCheckInterval());
    }

    public function testSetDuplicateCheckIntervalWithNull(): void
    {
        $this->testObject->setDuplicateCheckInterval(null);
        $this->assertNull($this->testObject->getDuplicateCheckInterval());
    }

    public function testSetDuplicateCheckIntervalWithZero(): void
    {
        $this->testObject->setDuplicateCheckInterval(0);
        $this->assertEquals(0, $this->testObject->getDuplicateCheckInterval());
    }

    public function testSetDuplicateCheckIntervalWithMaxValue(): void
    {
        $maxInterval = 604800; // 7天
        $this->testObject->setDuplicateCheckInterval($maxInterval);
        $this->assertEquals($maxInterval, $this->testObject->getDuplicateCheckInterval());
    }

    public function testGetDuplicateCheckArrayWithEnabledTrue(): void
    {
        $this->testObject->setEnableDuplicateCheck(true);
        $this->testObject->setDuplicateCheckInterval(3600);

        $expectedArray = [
            'enable_duplicate_check' => 1,
            'duplicate_check_interval' => 3600,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function testGetDuplicateCheckArrayWithEnabledFalse(): void
    {
        $this->testObject->setEnableDuplicateCheck(false);
        $this->testObject->setDuplicateCheckInterval(3600);

        $expectedArray = [
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 3600,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function testGetDuplicateCheckArrayWithNullEnabled(): void
    {
        $this->testObject->setEnableDuplicateCheck(null);
        $this->testObject->setDuplicateCheckInterval(1800);

        $expectedArray = [
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function testGetDuplicateCheckArrayWithDefaultValues(): void
    {
        // 测试默认值：enable_duplicate_check = false, duplicate_check_interval = 1800
        $expectedArray = [
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function testGetDuplicateCheckArrayWithNullInterval(): void
    {
        $this->testObject->setEnableDuplicateCheck(true);
        $this->testObject->setDuplicateCheckInterval(null);

        $expectedArray = [
            'enable_duplicate_check' => 1,
            'duplicate_check_interval' => null,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getDuplicateCheckArray());
    }

    public function testEdgeCasesMinInterval(): void
    {
        $this->testObject->setDuplicateCheckInterval(1);
        $this->assertEquals(1, $this->testObject->getDuplicateCheckInterval());
    }

    public function testEdgeCasesLargeInterval(): void
    {
        $largeInterval = 999999;
        $this->testObject->setDuplicateCheckInterval($largeInterval);
        $this->assertEquals($largeInterval, $this->testObject->getDuplicateCheckInterval());
    }

    public function testFluentInterface(): void
    {
        $this->testObject->setEnableDuplicateCheck(true);
        $this->testObject->setDuplicateCheckInterval(7200);

        $this->assertTrue($this->testObject->isEnableDuplicateCheck());
        $this->assertEquals(7200, $this->testObject->getDuplicateCheckInterval());
    }

    public function testToggleEnabledState(): void
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
