<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Traits\IdTransTrait;

class IdTransTraitTest extends TestCase
{
    private $subject;

    protected function setUp(): void
    {
        // 创建一个使用特性的匿名类
        $this->subject = new class {
            use IdTransTrait;
        };
    }

    public function testEnableIdTrans_defaultsFalse(): void
    {
        $this->assertFalse($this->subject->isEnableIdTrans());
    }

    public function testEnableIdTrans_setAndGet(): void
    {
        $this->subject->setEnableIdTrans(true);
        $this->assertTrue($this->subject->isEnableIdTrans());
        
        $this->subject->setEnableIdTrans(false);
        $this->assertFalse($this->subject->isEnableIdTrans());
    }

    public function testGetIdTransArray_whenIdTransIsDisabled(): void
    {
        $this->subject->setEnableIdTrans(false);
        $array = $this->subject->getIdTransArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('enable_id_trans', $array);
        $this->assertEquals(0, $array['enable_id_trans']);
    }

    public function testGetIdTransArray_whenIdTransIsEnabled(): void
    {
        $this->subject->setEnableIdTrans(true);
        $array = $this->subject->getIdTransArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('enable_id_trans', $array);
        $this->assertEquals(1, $array['enable_id_trans']);
    }
} 