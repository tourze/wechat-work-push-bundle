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
use WechatWorkPushBundle\Entity\NewsTemplateMessage;

/**
 * @extends AbstractCrudController<NewsTemplateMessage>
 */
#[AdminCrud(routePath: '/wechat-work-push/news-template-message', routeName: 'wechat_work_push_news_template_message')]
final class NewsTemplateMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NewsTemplateMessage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID'),
            TextField::new('title', '标题')
                ->setHelp('标题，不超过128个字节，超过会自动截断')
                ->setMaxLength(128),
            TextareaField::new('description', '描述')
                ->setHelp('描述，不超过512个字节，超过会自动截断')
                ->setMaxLength(512),
            TextField::new('taskId', '任务ID')
                ->setHelp('任务id，同一个应用发送的任务id不能重复，只能由数字、字母和"_-@"组成，最长128字节')
                ->setMaxLength(128),
            UrlField::new('url', '跳转链接')
                ->setHelp('点击后跳转的链接。最长2048字节，请确保包含了协议头(http/https)'),
            UrlField::new('imageUrl', '图片链接')
                ->setHelp('图片的url'),
            TextField::new('btnText', '底部按钮文字')
                ->setHelp('底部按钮文字，默认为"详情"')
                ->setMaxLength(64),
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
            ->setEntityLabelInSingular('新闻模板消息')
            ->setEntityLabelInPlural('新闻模板消息')
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
