<?php

declare(strict_types=1);

namespace WechatWorkPushBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use WechatWorkPushBundle\Entity\MiniProgramNoticeMessage;

/**
 * @extends AbstractCrudController<MiniProgramNoticeMessage>
 */
#[AdminCrud(routePath: '/wechat-work-push/mini-program-notice-message', routeName: 'wechat_work_push_mini_program_notice_message')]
final class MiniProgramNoticeMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MiniProgramNoticeMessage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),
            TextField::new('appId', '小程序ID')
                ->setHelp('必须是与当前应用关联的小程序')
                ->setMaxLength(64),
            TextField::new('page', '小程序页面路径')
                ->setHelp('仅限本小程序内的页面。该字段不填则消息点击后不跳转')
                ->setMaxLength(1024),
            TextField::new('title', '消息标题')
                ->setHelp('消息标题')
                ->setMaxLength(12),
            TextField::new('description', '消息描述')
                ->setHelp('消息描述')
                ->setMaxLength(12),
            BooleanField::new('emphasisFirstItem', '放大第一个内容项')
                ->setHelp('是否放大第一个content_item'),
            CollectionField::new('contentItem', '消息内容键值对')
                ->setHelp('消息内容键值对，最多允许10个item')
                ->hideOnForm(), // 在表单中隐藏，避免 Twig 模板警告
            AssociationField::new('agent', '应用')
                ->setHelp('企业应用的id')
                ->setRequired(true),
            BooleanField::new('enableIdTrans', '开启id转译')
                ->setHelp('表示是否开启id转译'),
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
            ->setEntityLabelInSingular('小程序通知消息')
            ->setEntityLabelInPlural('小程序通知消息')
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
