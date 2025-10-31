<?php

namespace WechatWorkPushBundle\Tests\Traits;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Traits\IdTransTrait;

/**
 * @internal
 */
#[CoversClass(IdTransTrait::class)]
final class IdTransTraitTest extends TestCase
{
    private TestIdTransEntity $testObject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testObject = new TestIdTransEntity();
    }

    public function testSetEnableIdTransWithTrue(): void
    {
        $this->testObject->setEnableIdTrans(true);

        $this->assertTrue($this->testObject->isEnableIdTrans());
    }

    public function testSetEnableIdTransWithFalse(): void
    {
        $this->testObject->setEnableIdTrans(false);

        $this->assertFalse($this->testObject->isEnableIdTrans());
    }

    public function testSetEnableIdTransWithNull(): void
    {
        $this->testObject->setEnableIdTrans(null);
        $this->assertNull($this->testObject->isEnableIdTrans());
    }

    public function testGetIdTransArrayWithTrue(): void
    {
        $this->testObject->setEnableIdTrans(true);

        $expectedArray = [
            'enable_id_trans' => 1,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getIdTransArray());
    }

    public function testGetIdTransArrayWithFalse(): void
    {
        $this->testObject->setEnableIdTrans(false);

        $expectedArray = [
            'enable_id_trans' => 0,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getIdTransArray());
    }

    public function testGetIdTransArrayWithNull(): void
    {
        $this->testObject->setEnableIdTrans(null);

        $expectedArray = [
            'enable_id_trans' => 0,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getIdTransArray());
    }

    public function testGetIdTransArrayWithDefaultValue(): void
    {
        // 测试默认值（应该是 false）
        $expectedArray = [
            'enable_id_trans' => 0,
        ];

        $this->assertEquals($expectedArray, $this->testObject->getIdTransArray());
    }

    public function testEdgeCasesToggleIdTransValue(): void
    {
        // 测试多次切换 ID 转换值
        $this->testObject->setEnableIdTrans(true);
        $this->assertTrue($this->testObject->isEnableIdTrans());

        $this->testObject->setEnableIdTrans(false);
        $this->assertFalse($this->testObject->isEnableIdTrans());

        $this->testObject->setEnableIdTrans(true);
        $this->assertTrue($this->testObject->isEnableIdTrans());
    }

    public function testFluentInterface(): void
    {
        // 测试流畅接口
        $this->testObject->setEnableIdTrans(true);
        $this->testObject->setEnableIdTrans(false);
        $this->testObject->setEnableIdTrans(true);

        $this->assertTrue($this->testObject->isEnableIdTrans());
    }

    public function testInitialState(): void
    {
        // 测试初始状态
        $this->assertFalse($this->testObject->isEnableIdTrans());
    }

    public function testNullToFalseConversion(): void
    {
        // 测试 null 值被正确转换为 false
        $this->testObject->setEnableIdTrans(null);
        $result = $this->testObject->getIdTransArray();

        $this->assertEquals(0, $result['enable_id_trans']);
    }

    public function testBooleanConversion(): void
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
