<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatWorkPushBundle\Entity\ButtonTemplateMessage;
use WechatWorkPushBundle\Entity\FileMessage;
use WechatWorkPushBundle\Entity\ImageMessage;
use WechatWorkPushBundle\Entity\MarkdownMessage;
use WechatWorkPushBundle\Entity\MiniProgramNoticeMessage;
use WechatWorkPushBundle\Entity\MpnewsMessage;
use WechatWorkPushBundle\Entity\MultipleTemplateMessage;
use WechatWorkPushBundle\Entity\NewsMessage;
use WechatWorkPushBundle\Entity\NewsTemplateMessage;
use WechatWorkPushBundle\Entity\TemplateCardMessage;
use WechatWorkPushBundle\Entity\TextCardMessage;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\Entity\TextNoticeTemplateMessage;
use WechatWorkPushBundle\Entity\VideoMessage;
use WechatWorkPushBundle\Entity\VoiceMessage;
use WechatWorkPushBundle\Entity\VoteTemplateMessage;

/**
 * 企业微信应用推送后台菜单提供者
 */
#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        // 创建企业微信管理主菜单
        if (null === $item->getChild('企业微信管理')) {
            $item->addChild('企业微信管理')
                ->setAttribute('icon', 'fas fa-wechat')
            ;
        }

        $wechatMenu = $item->getChild('企业微信管理');
        if (null === $wechatMenu) {
            return;
        }

        // 添加应用推送子菜单
        if (null === $wechatMenu->getChild('应用推送')) {
            $wechatMenu->addChild('应用推送')
                ->setAttribute('icon', 'fas fa-paper-plane')
            ;
        }

        $pushMenu = $wechatMenu->getChild('应用推送');
        if (null === $pushMenu) {
            return;
        }

        // 基础消息类型
        $pushMenu->addChild('基础消息')
            ->setAttribute('icon', 'fas fa-comments')
        ;

        $basicMessageMenu = $pushMenu->getChild('基础消息');
        if (null !== $basicMessageMenu) {
            // 文本消息
            $basicMessageMenu->addChild('文本消息')
                ->setUri($this->linkGenerator->getCurdListPage(TextMessage::class))
                ->setAttribute('icon', 'fas fa-font')
            ;

            // 图片消息
            $basicMessageMenu->addChild('图片消息')
                ->setUri($this->linkGenerator->getCurdListPage(ImageMessage::class))
                ->setAttribute('icon', 'fas fa-image')
            ;

            // 语音消息
            $basicMessageMenu->addChild('语音消息')
                ->setUri($this->linkGenerator->getCurdListPage(VoiceMessage::class))
                ->setAttribute('icon', 'fas fa-microphone')
            ;

            // 视频消息
            $basicMessageMenu->addChild('视频消息')
                ->setUri($this->linkGenerator->getCurdListPage(VideoMessage::class))
                ->setAttribute('icon', 'fas fa-video')
            ;

            // 文件消息
            $basicMessageMenu->addChild('文件消息')
                ->setUri($this->linkGenerator->getCurdListPage(FileMessage::class))
                ->setAttribute('icon', 'fas fa-file')
            ;

            // Markdown消息
            $basicMessageMenu->addChild('Markdown消息')
                ->setUri($this->linkGenerator->getCurdListPage(MarkdownMessage::class))
                ->setAttribute('icon', 'fab fa-markdown')
            ;
        }

        // 图文消息类型
        $pushMenu->addChild('图文消息')
            ->setAttribute('icon', 'fas fa-newspaper')
        ;

        $newsMenu = $pushMenu->getChild('图文消息');
        if (null !== $newsMenu) {
            // 图文消息
            $newsMenu->addChild('图文消息')
                ->setUri($this->linkGenerator->getCurdListPage(NewsMessage::class))
                ->setAttribute('icon', 'fas fa-newspaper')
            ;

            // Mpnews消息
            $newsMenu->addChild('Mpnews消息')
                ->setUri($this->linkGenerator->getCurdListPage(MpnewsMessage::class))
                ->setAttribute('icon', 'fas fa-rss')
            ;

            // 文本卡片消息
            $newsMenu->addChild('文本卡片消息')
                ->setUri($this->linkGenerator->getCurdListPage(TextCardMessage::class))
                ->setAttribute('icon', 'fas fa-id-card')
            ;
        }

        // 模板消息类型
        $pushMenu->addChild('模板消息')
            ->setAttribute('icon', 'fas fa-layer-group')
        ;

        $templateMenu = $pushMenu->getChild('模板消息');
        if (null !== $templateMenu) {
            // 文本通知型模板消息
            $templateMenu->addChild('文本通知型模板消息')
                ->setUri($this->linkGenerator->getCurdListPage(TextNoticeTemplateMessage::class))
                ->setAttribute('icon', 'fas fa-bell')
            ;

            // 图文展示型模板消息
            $templateMenu->addChild('图文展示型模板消息')
                ->setUri($this->linkGenerator->getCurdListPage(NewsTemplateMessage::class))
                ->setAttribute('icon', 'fas fa-newspaper')
            ;

            // 按钮交互型模板消息
            $templateMenu->addChild('按钮交互型模板消息')
                ->setUri($this->linkGenerator->getCurdListPage(ButtonTemplateMessage::class))
                ->setAttribute('icon', 'fas fa-hand-pointer')
            ;

            // 投票选择型模板消息
            $templateMenu->addChild('投票选择型模板消息')
                ->setUri($this->linkGenerator->getCurdListPage(VoteTemplateMessage::class))
                ->setAttribute('icon', 'fas fa-vote-yea')
            ;

            // 多项选择型模板消息
            $templateMenu->addChild('多项选择型模板消息')
                ->setUri($this->linkGenerator->getCurdListPage(MultipleTemplateMessage::class))
                ->setAttribute('icon', 'fas fa-list-ul')
            ;

            // 模板卡片消息
            $templateMenu->addChild('模板卡片消息')
                ->setUri($this->linkGenerator->getCurdListPage(TemplateCardMessage::class))
                ->setAttribute('icon', 'fas fa-id-card-alt')
            ;
        }

        // 小程序消息
        $pushMenu->addChild('小程序通知消息')
            ->setUri($this->linkGenerator->getCurdListPage(MiniProgramNoticeMessage::class))
            ->setAttribute('icon', 'fas fa-mobile-alt')
        ;
    }
}
