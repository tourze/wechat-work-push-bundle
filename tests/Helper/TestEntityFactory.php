<?php

namespace WechatWorkPushBundle\Tests\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;

/**
 * 测试实体工厂
 *
 * 用于创建测试所需的实体，处理接口实现问题
 */
class TestEntityFactory
{
    /**
     * @return AgentInterface
     */
    public static function createTestAgent(EntityManagerInterface $entityManager): AgentInterface
    {
        // 尝试获取已生成的测试实体类
        $agentClass = self::getTestEntityClass(AgentInterface::class);

        // 创建实例
        /** @var AgentInterface $agent */
        $agent = new $agentClass();

        // 设置基本属性
        if (method_exists($agent, 'setAgentId')) {
            $agent->setAgentId('test-agent-' . uniqid());
        }
        if (method_exists($agent, 'setName')) {
            $agent->setName('Test Agent ' . uniqid());
        }

        // 持久化
        $entityManager->persist($agent);

        return $agent;
    }

    /**
     * @return CorpInterface
     */
    public static function createTestCorp(EntityManagerInterface $entityManager): CorpInterface
    {
        // 尝试获取已生成的测试实体类
        $corpClass = self::getTestEntityClass(CorpInterface::class);

        // 创建实例
        /** @var CorpInterface $corp */
        $corp = new $corpClass();

        // 设置基本属性
        if (method_exists($corp, 'setCorpId')) {
            $corp->setCorpId('test-corp-' . uniqid());
        }
        if (method_exists($corp, 'setName')) {
            $corp->setName('Test Corp ' . uniqid());
        }
        if (method_exists($corp, 'setCorpSecret')) {
            $corp->setCorpSecret('test-secret-' . uniqid());
        }

        // 持久化
        $entityManager->persist($corp);

        return $corp;
    }

    /**
     * 获取接口的测试实现类
     */
    private static function getTestEntityClass(string $interface): string
    {
        // 首先尝试使用静态测试类
        $staticClass = match ($interface) {
            AgentInterface::class => 'WechatWorkPushBundle\Tests\Entity\TestAgent',
            CorpInterface::class => 'WechatWorkPushBundle\Tests\Entity\TestCorp',
            default => null,
        };

        if (null !== $staticClass && class_exists($staticClass)) {
            return $staticClass;
        }

        // 如果静态类不存在，让 symfony-testing-framework 动态生成
        // 这里会通过 resolve_target_entities 配置自动处理
        return $interface;
    }
}
