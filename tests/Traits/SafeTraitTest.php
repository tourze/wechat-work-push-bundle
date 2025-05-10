<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Traits\SafeTrait;

class SafeTraitTest extends TestCase
{
    private $subject;

    protected function setUp(): void
    {
        // 创建一个使用特性的匿名类
        $this->subject = new class {
            use SafeTrait;
        };
    }

    public function testSafe_defaultsFalse(): void
    {
        $this->assertFalse($this->subject->isSafe());
    }

    public function testSafe_setAndGet(): void
    {
        $this->subject->setSafe(true);
        $this->assertTrue($this->subject->isSafe());
        
        $this->subject->setSafe(false);
        $this->assertFalse($this->subject->isSafe());
    }

    public function testGetSafeArray_whenSafeIsDisabled(): void
    {
        $this->subject->setSafe(false);
        $array = $this->subject->getSafeArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('safe', $array);
        $this->assertEquals(0, $array['safe']);
    }

    public function testGetSafeArray_whenSafeIsEnabled(): void
    {
        $this->subject->setSafe(true);
        $array = $this->subject->getSafeArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('safe', $array);
        $this->assertEquals(1, $array['safe']);
    }
} 