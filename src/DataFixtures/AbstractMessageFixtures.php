<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;

abstract class AbstractMessageFixtures extends Fixture
{
    private ?AgentInterface $agent = null;

    protected function getOrCreateAgent(ObjectManager $manager): AgentInterface
    {
        if (null !== $this->agent) {
            return $this->agent;
        }

        // 创建 Corp
        $corp = new Corp();
        $corp->setName('测试企业_' . uniqid());
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setCorpSecret('test_corp_secret_' . uniqid());
        $manager->persist($corp);

        // 创建 Agent
        $agent = new Agent();
        $agent->setAgentId('100001');
        $agent->setName('测试应用_' . uniqid());
        $agent->setSecret('test_secret_' . uniqid());
        $agent->setCorp($corp);
        $manager->persist($agent);
        $manager->flush();

        $this->agent = $agent;

        return $agent;
    }
}
