<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\MultipleTemplateMessage;

/**
 * 多选交互型模板卡片消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class MultipleTemplateMessageFixtures extends AbstractMessageFixtures
{
    public const MULTIPLE_TEMPLATE_MESSAGE_SURVEY = 'multiple-template-message-survey';
    public const MULTIPLE_TEMPLATE_MESSAGE_FEEDBACK = 'multiple-template-message-feedback';
    public const MULTIPLE_TEMPLATE_MESSAGE_CHOICE = 'multiple-template-message-choice';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建满意度调查消息
        $surveyMessage = new MultipleTemplateMessage();
        $surveyMessage->setAgent($agent);
        $surveyMessage->setTitle('满意度调查');
        $surveyMessage->setDescription('请选择您对我们服务的评价');
        $surveyMessage->setQuestionKey('satisfaction_survey');
        $surveyMessage->setOptions([
            ['id' => '1', 'text' => '非常满意'],
            ['id' => '2', 'text' => '满意'],
            ['id' => '3', 'text' => '一般'],
            ['id' => '4', 'text' => '不满意'],
        ]);
        $surveyMessage->setTaskId('task_survey_' . uniqid());
        $manager->persist($surveyMessage);
        $this->setReference(self::MULTIPLE_TEMPLATE_MESSAGE_SURVEY, $surveyMessage);

        // 创建反馈收集消息
        $feedbackMessage = new MultipleTemplateMessage();
        $feedbackMessage->setAgent($agent);
        $feedbackMessage->setTitle('功能反馈收集');
        $feedbackMessage->setDescription('您希望我们优先开发哪些功能？');
        $feedbackMessage->setQuestionKey('feature_feedback');
        $feedbackMessage->setOptions([
            ['id' => '1', 'text' => '数据分析'],
            ['id' => '2', 'text' => '移动端优化'],
            ['id' => '3', 'text' => '报表功能'],
        ]);
        $feedbackMessage->setTaskId('task_feedback_' . uniqid());
        $manager->persist($feedbackMessage);
        $this->setReference(self::MULTIPLE_TEMPLATE_MESSAGE_FEEDBACK, $feedbackMessage);

        // 创建选择题消息
        $choiceMessage = new MultipleTemplateMessage();
        $choiceMessage->setAgent($agent);
        $choiceMessage->setTitle('培训课程选择');
        $choiceMessage->setDescription('请选择您感兴趣的培训课程');
        $choiceMessage->setQuestionKey('training_choice');
        $choiceMessage->setOptions([
            ['id' => '1', 'text' => 'PHP开发'],
            ['id' => '2', 'text' => '前端技术'],
            ['id' => '3', 'text' => '项目管理'],
            ['id' => '4', 'text' => '产品设计'],
        ]);
        $choiceMessage->setTaskId('task_choice_' . uniqid());
        $manager->persist($choiceMessage);
        $this->setReference(self::MULTIPLE_TEMPLATE_MESSAGE_CHOICE, $choiceMessage);

        $manager->flush();
    }
}
