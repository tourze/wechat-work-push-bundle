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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use WechatWorkPushBundle\Entity\MultipleTemplateMessage;

/**
 * @extends AbstractCrudController<MultipleTemplateMessage>
 */
#[AdminCrud(routePath: '/wechat-work-push/multiple-template-message', routeName: 'wechat_work_push_multiple_template_message')]
final class MultipleTemplateMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MultipleTemplateMessage::class;
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
            TextField::new('questionKey', '选择题Key')
                ->setHelp('选择题key值，用户提交选项后，会产生回调事件，回调事件会带上该key值表示该题，最长支持128字节')
                ->setMaxLength(128),
            CollectionField::new('options', '选项列表')
                ->setHelp('选项列表，最多4个选项，JSON格式，如：[{"id": "1", "text": "选项1"}, {"id": "2", "text": "选项2"}]')
                ->hideOnForm(), // 在表单中隐藏，避免 Twig 模板警告
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
            ->setEntityLabelInSingular('多选模板消息')
            ->setEntityLabelInPlural('多选模板消息')
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
