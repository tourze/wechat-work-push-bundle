<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Traits\IdTransTrait;

class IdTransTraitTest extends TestCase
{
    private IdTransTraitTestClass $testObject;

    protected function setUp(): void
    {
        $this->testObject = new IdTransTraitTestClass();
    }

    public function test_setEnableIdTrans_withTrue(): void
    {
        $result = $this->testObject->setEnableIdTrans(true);
        
        $this->assertSame($this->testObject, $result);
        $this->assertTrue($this->testObject->isEnableIdTrans());
    }

    public function test_setEnableIdTrans_withFalse(): void
    {
        $result = $this->testObject->setEnableIdTrans(false);
        
        $this->assertSame($this->testObject, $result);
        $this->assertFalse($this->testObject->isEnableIdTrans());
    }

    public function test_setEnableIdTrans_withNull(): void
    {
        $this->testObject->setEnableIdTrans(null);
        $this->assertNull($this->testObject->isEnableIdTrans());
    }

    public function test_getIdTransArray_withTrue(): void
    {
        $this->testObject->setEnableIdTrans(true);
        
        $expectedArray = [
            'enable_id_trans' => 1
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getIdTransArray());
    }

    public function test_getIdTransArray_withFalse(): void
    {
        $this->testObject->setEnableIdTrans(false);
        
        $expectedArray = [
            'enable_id_trans' => 0
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getIdTransArray());
    }

    public function test_getIdTransArray_withNull(): void
    {
        $this->testObject->setEnableIdTrans(null);
        
        $expectedArray = [
            'enable_id_trans' => 0
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getIdTransArray());
    }

    public function test_getIdTransArray_withDefaultValue(): void
    {
        // 测试默认值（应该是 false）
        $expectedArray = [
            'enable_id_trans' => 0
        ];
        
        $this->assertEquals($expectedArray, $this->testObject->getIdTransArray());
    }

    public function test_edgeCases_toggleIdTransValue(): void
    {
        // 测试多次切换 ID 转换值
        $this->testObject->setEnableIdTrans(true);
        $this->assertTrue($this->testObject->isEnableIdTrans());
        
        $this->testObject->setEnableIdTrans(false);
        $this->assertFalse($this->testObject->isEnableIdTrans());
        
        $this->testObject->setEnableIdTrans(true);
        $this->assertTrue($this->testObject->isEnableIdTrans());
    }

    public function test_fluentInterface(): void
    {
        // 测试流畅接口
        $result = $this->testObject
            ->setEnableIdTrans(true)
            ->setEnableIdTrans(false)
            ->setEnableIdTrans(true);
        
        $this->assertSame($this->testObject, $result);
        $this->assertTrue($this->testObject->isEnableIdTrans());
    }

    public function test_initialState(): void
    {
        // 测试初始状态
        $this->assertFalse($this->testObject->isEnableIdTrans());
    }

    public function test_nullToFalseConversion(): void
    {
        // 测试 null 值被正确转换为 false
        $this->testObject->setEnableIdTrans(null);
        $result = $this->testObject->getIdTransArray();
        
        $this->assertEquals(0, $result['enable_id_trans']);
    }

    public function test_booleanConversion(): void
    {
        // 测试布尔值正确转换为整数
        $this->testObject->setEnableIdTrans(true);
        $result = $this->testObject->getIdTransArray();
        $this->assertEquals(1, $result['enable_id_trans']);
        
        $this->testObject->setEnableIdTrans(false);
        $result = $this->testObject->getIdTransArray();
        $this->assertEquals(0, $result['enable_id_trans']);
    }
}

/**
 * 用于测试 IdTransTrait 的具体实现类
 */
class IdTransTraitTestClass
{
    use IdTransTrait;
} 