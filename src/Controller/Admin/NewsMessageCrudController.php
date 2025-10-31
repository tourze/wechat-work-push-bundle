<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use WechatWorkPushBundle\Entity\NewsMessage;

/**
 * @extends AbstractCrudController<NewsMessage>
 */
#[AdminCrud(routePath: '/wechat-work-push/news-message', routeName: 'wechat_work_push_news_message')]
final class NewsMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NewsMessage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID'),
            TextField::new('title', '标题')
                ->setHelp('标题，不超过128个字节，超过会自动截断（支持id转译）')
                ->setMaxLength(128),
            TextareaField::new('description', '描述')
                ->setHelp('描述，不超过512个字节，超过会自动截断（支持id转译）')
                ->setMaxLength(512),
            UrlField::new('url', '跳转链接')
                ->setHelp('点击后跳转的链接。最长2048字节，请确保包含了协议头(http/https)，小程序或者url必须填写一个'),
            UrlField::new('picUrl', '图片链接')
                ->setHelp('图文消息的图片链接，最长2048字节，支持JPG、PNG格式，较好的效果为大图 1068*455，小图150*150'),
            TextField::new('appId', '小程序AppID')
                ->setHelp('小程序appid，必须是与当前应用关联的小程序，appid和pagepath必须同时填写，填写后会忽略url字段')
                ->setMaxLength(64),
            TextField::new('pagePath', '小程序页面')
                ->setHelp('点击消息卡片后的小程序页面，最长128字节，仅限本小程序内的页面。appid和pagepath必须同时填写，填写后会忽略url字段')
                ->setMaxLength(128),
            AssociationField::new('agent', '应用')
                ->setHelp('企业应用的id'),
            BooleanField::new('safe', '保密消息')
                ->setHelp('表示是否是保密消息'),
            BooleanField::new('enableDuplicateCheck', '开启重复消息检查')
                ->setHelp('表示是否开启重复消息检查'),
            IntegerField::new('duplicateCheckInterval', '重复消息检查时间间隔')
                ->setHelp('重复消息检查的时间间隔，默认1800s'),
            DateTimeField::new('createTime', '创建时间')
                ->hideOnForm(),
            DateTimeField::new('updateTime', '更新时间')
                ->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('新闻消息')
            ->setEntityLabelInPlural('新闻消息')
            ->setDefaultSort(['id' => 'DESC'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(DateTimeFilter::new('createTime', '创建时间'))
            ->add(DateTimeFilter::new('updateTime', '更新时间'))
        ;
    }
}
