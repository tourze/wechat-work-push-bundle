<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;

class DuplicateCheckTraitTest extends TestCase
{
    private $subject;

    protected function setUp(): void
    {
        // 创建一个使用特性的匿名类
        $this->subject = new class {
            use DuplicateCheckTrait;
        };
    }

    public function testEnableDuplicateCheck_defaultsFalse(): void
    {
        $this->assertFalse($this->subject->isEnableDuplicateCheck());
    }

    public function testEnableDuplicateCheck_setAndGet(): void
    {
        $this->subject->setEnableDuplicateCheck(true);
        $this->assertTrue($this->subject->isEnableDuplicateCheck());
        
        $this->subject->setEnableDuplicateCheck(false);
        $this->assertFalse($this->subject->isEnableDuplicateCheck());
    }

    public function testDuplicateCheckInterval_defaultsTo1800(): void
    {
        $this->assertEquals(1800, $this->subject->getDuplicateCheckInterval());
    }

    public function testDuplicateCheckInterval_setAndGet(): void
    {
        $interval = 3600;
        $this->subject->setDuplicateCheckInterval($interval);
        $this->assertEquals($interval, $this->subject->getDuplicateCheckInterval());
    }

    public function testGetDuplicateCheckArray_whenDuplicateCheckIsDisabled(): void
    {
        $this->subject->setEnableDuplicateCheck(false);
        $array = $this->subject->getDuplicateCheckArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('enable_duplicate_check', $array);
        $this->assertEquals(0, $array['enable_duplicate_check']);
        $this->assertArrayHasKey('duplicate_check_interval', $array);
        $this->assertEquals(1800, $array['duplicate_check_interval']);
    }

    public function testGetDuplicateCheckArray_whenDuplicateCheckIsEnabled(): void
    {
        $this->subject->setEnableDuplicateCheck(true);
        $this->subject->setDuplicateCheckInterval(3600);
        $array = $this->subject->getDuplicateCheckArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('enable_duplicate_check', $array);
        $this->assertEquals(1, $array['enable_duplicate_check']);
        $this->assertArrayHasKey('duplicate_check_interval', $array);
        $this->assertEquals(3600, $array['duplicate_check_interval']);
    }

    public function testGetDuplicateCheckArray_withDefaultInterval(): void
    {
        $this->subject->setEnableDuplicateCheck(true);
        $array = $this->subject->getDuplicateCheckArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('enable_duplicate_check', $array);
        $this->assertEquals(1, $array['enable_duplicate_check']);
        $this->assertArrayHasKey('duplicate_check_interval', $array);
        $this->assertEquals(1800, $array['duplicate_check_interval']);
    }
} 