<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\NewsTemplateMessage;

/**
 * 新闻通知型模板卡片消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class NewsTemplateMessageFixtures extends AbstractMessageFixtures
{
    public const NEWS_TEMPLATE_MESSAGE_INDUSTRY = 'news-template-message-industry';
    public const NEWS_TEMPLATE_MESSAGE_COMPANY = 'news-template-message-company';
    public const NEWS_TEMPLATE_MESSAGE_TECH = 'news-template-message-tech';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建行业新闻消息
        $industryMessage = new NewsTemplateMessage();
        $industryMessage->setAgent($agent);
        $industryMessage->setTitle('行业动态通知');
        $industryMessage->setDescription('最新的行业发展趋势及技术更新');
        $industryMessage->setUrl('https://httpbin.org/html');
        $industryMessage->setImageUrl('https://httpbin.org/image/png');
        $industryMessage->setBtnText('查看详情');
        $industryMessage->setTaskId('task_industry_' . uniqid());
        $manager->persist($industryMessage);
        $this->setReference(self::NEWS_TEMPLATE_MESSAGE_INDUSTRY, $industryMessage);

        // 创建公司新闻消息
        $companyMessage = new NewsTemplateMessage();
        $companyMessage->setAgent($agent);
        $companyMessage->setTitle('公司新闻公告');
        $companyMessage->setDescription('公司最新动态、人事变动及重要公告');
        $companyMessage->setUrl('https://httpbin.org/html');
        $companyMessage->setImageUrl('https://httpbin.org/image/png');
        $companyMessage->setBtnText('立即阅读');
        $companyMessage->setTaskId('task_company_' . uniqid());
        $manager->persist($companyMessage);
        $this->setReference(self::NEWS_TEMPLATE_MESSAGE_COMPANY, $companyMessage);

        // 创建技术新闻消息
        $techMessage = new NewsTemplateMessage();
        $techMessage->setAgent($agent);
        $techMessage->setTitle('技术分享');
        $techMessage->setDescription('最新技术文章、开发经验与最佳实践');
        $techMessage->setUrl('https://httpbin.org/html');
        $techMessage->setImageUrl('https://httpbin.org/image/png');
        $techMessage->setBtnText('学习更多');
        $techMessage->setTaskId('task_tech_' . uniqid());
        $manager->persist($techMessage);
        $this->setReference(self::NEWS_TEMPLATE_MESSAGE_TECH, $techMessage);

        $manager->flush();
    }
}
