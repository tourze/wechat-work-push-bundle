<?php

namespace WechatWorkPushBundle\Tests\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Model\AppMessage;

/**
 * @internal
 */
#[CoversClass(AppMessage::class)]
final class AppMessageTest extends TestCase
{
    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(AppMessage::class));
    }

    public function testInterfaceHasRequiredMethods(): void
    {
        $reflection = new \ReflectionClass(AppMessage::class);

        $this->assertTrue($reflection->hasMethod('getMsgType'));
        $this->assertTrue($reflection->hasMethod('getMsgId'));
        $this->assertTrue($reflection->hasMethod('setMsgId'));
        $this->assertTrue($reflection->hasMethod('getAgent'));
        $this->assertTrue($reflection->hasMethod('toRequestArray'));
    }

    public function testGetMsgTypeReturnsString(): void
    {
        $reflection = new \ReflectionClass(AppMessage::class);
        $method = $reflection->getMethod('getMsgType');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('string', $returnType instanceof \ReflectionNamedType ? $returnType->getName() : 'unknown');
    }

    public function testToRequestArrayReturnsArray(): void
    {
        $reflection = new \ReflectionClass(AppMessage::class);
        $method = $reflection->getMethod('toRequestArray');
        $returnType = $method->getReturnType();

        $this->assertNotNull($returnType);
        $this->assertEquals('array', $returnType instanceof \ReflectionNamedType ? $returnType->getName() : 'unknown');
    }
}
