<?php

namespace WechatWorkPushBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use WechatWorkPushBundle\Model\AppMessage;

class AppMessageTest extends TestCase
{
    public function test_interface_exists(): void
    {
        $this->assertTrue(interface_exists(AppMessage::class));
    }

    public function test_interface_hasRequiredMethods(): void
    {
        $reflection = new ReflectionClass(AppMessage::class);
        
        $this->assertTrue($reflection->hasMethod('getMsgType'));
        $this->assertTrue($reflection->hasMethod('getMsgId'));
        $this->assertTrue($reflection->hasMethod('setMsgId'));
        $this->assertTrue($reflection->hasMethod('getAgent'));
        $this->assertTrue($reflection->hasMethod('toRequestArray'));
    }

    public function test_getMsgType_returnsString(): void
    {
        $reflection = new ReflectionClass(AppMessage::class);
        $method = $reflection->getMethod('getMsgType');
        $returnType = $method->getReturnType();
        
        $this->assertNotNull($returnType);
        $this->assertEquals('string', $returnType instanceof \ReflectionNamedType ? $returnType->getName() : 'unknown');
    }

    public function test_toRequestArray_returnsArray(): void
    {
        $reflection = new ReflectionClass(AppMessage::class);
        $method = $reflection->getMethod('toRequestArray');
        $returnType = $method->getReturnType();
        
        $this->assertNotNull($returnType);
        $this->assertEquals('array', $returnType instanceof \ReflectionNamedType ? $returnType->getName() : 'unknown');
    }
}