<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\VoteTemplateMessage;

/**
 * 投票交互型模板卡片消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class VoteTemplateMessageFixtures extends AbstractMessageFixtures
{
    public const VOTE_TEMPLATE_MESSAGE_ELECTION = 'vote-template-message-election';
    public const VOTE_TEMPLATE_MESSAGE_PROPOSAL = 'vote-template-message-proposal';
    public const VOTE_TEMPLATE_MESSAGE_DECISION = 'vote-template-message-decision';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建选举投票消息
        $electionMessage = new VoteTemplateMessage();
        $electionMessage->setAgent($agent);
        $electionMessage->setTitle('部门代表选举');
        $electionMessage->setDescription('请为您支持的同事投票');
        $electionMessage->setQuestionKey('department_election');
        $electionMessage->setOptions([
            ['id' => '1', 'text' => '张三'],
            ['id' => '2', 'text' => '李四'],
            ['id' => '3', 'text' => '王五'],
            ['id' => '4', 'text' => '赵六'],
        ]);
        $electionMessage->setTaskId('task_election_' . uniqid());
        $manager->persist($electionMessage);
        $this->setReference(self::VOTE_TEMPLATE_MESSAGE_ELECTION, $electionMessage);

        // 创建提案投票消息
        $proposalMessage = new VoteTemplateMessage();
        $proposalMessage->setAgent($agent);
        $proposalMessage->setTitle('新政策提案');
        $proposalMessage->setDescription('请对以下新政策提案进行投票');
        $proposalMessage->setQuestionKey('policy_proposal');
        $proposalMessage->setOptions([
            ['id' => '1', 'text' => '完全支持'],
            ['id' => '2', 'text' => '支持'],
            ['id' => '3', 'text' => '中立'],
            ['id' => '4', 'text' => '反对'],
        ]);
        $proposalMessage->setTaskId('task_proposal_' . uniqid());
        $manager->persist($proposalMessage);
        $this->setReference(self::VOTE_TEMPLATE_MESSAGE_PROPOSAL, $proposalMessage);

        // 创建决策投票消息
        $decisionMessage = new VoteTemplateMessage();
        $decisionMessage->setAgent($agent);
        $decisionMessage->setTitle('项目决策投票');
        $decisionMessage->setDescription('请选择下一个季度的主要项目');
        $decisionMessage->setQuestionKey('project_decision');
        $decisionMessage->setOptions([
            ['id' => '1', 'text' => '产品升级'],
            ['id' => '2', 'text' => '技术改造'],
            ['id' => '3', 'text' => '市场拓展'],
        ]);
        $decisionMessage->setTaskId('task_decision_' . uniqid());
        $manager->persist($decisionMessage);
        $this->setReference(self::VOTE_TEMPLATE_MESSAGE_DECISION, $decisionMessage);

        $manager->flush();
    }
}
