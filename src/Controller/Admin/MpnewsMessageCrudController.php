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
use WechatWorkPushBundle\Entity\MpnewsMessage;

/**
 * @extends AbstractCrudController<MpnewsMessage>
 */
#[AdminCrud(routePath: '/wechat-work-push/mpnews-message', routeName: 'wechat_work_push_mpnews_message')]
final class MpnewsMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MpnewsMessage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID'),
            TextField::new('title', '标题')
                ->setHelp('标题，不超过128个字节，超过会自动截断（支持id转译）')
                ->setMaxLength(128),
            TextareaField::new('content', '内容')
                ->setHelp('图文消息内容')
                ->setMaxLength(65535),
            UrlField::new('thumbMediaUrl', '缩略图URL')
                ->setHelp('图文消息缩略图的url'),
            TextField::new('thumbMediaId', '缩略图媒体ID')
                ->setHelp('图文消息缩略图的media_id，可以通过素材管理接口获得')
                ->setMaxLength(100),
            TextField::new('digest', '描述')
                ->setHelp('图文消息的描述，不超过512个字节，超过会自动截断（支持id转译）')
                ->setMaxLength(512),
            UrlField::new('contentSourceUrl', '阅读原文链接')
                ->setHelp('图文消息点击"阅读原文"之后的页面链接'),
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
            ->setEntityLabelInSingular('图文消息')
            ->setEntityLabelInPlural('图文消息')
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
