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
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use WechatWorkPushBundle\Entity\VideoMessage;

/**
 * @extends AbstractCrudController<VideoMessage>
 */
#[AdminCrud(routePath: '/wechat-work-push/video-message', routeName: 'wechat_work_push_video_message')]
final class VideoMessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VideoMessage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID'),
            TextField::new('mediaId', '视频媒体ID')
                ->setHelp('视频媒体文件id，可以调用上传临时素材接口获取')
                ->setMaxLength(100),
            TextField::new('title', '视频标题')
                ->setHelp('视频消息的标题，不超过128个字节，超过会自动截断')
                ->setMaxLength(128),
            TextareaField::new('description', '视频描述')
                ->setHelp('视频消息的描述，不超过512个字节，超过会自动截断')
                ->setMaxLength(512),
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
            ->setEntityLabelInSingular('视频消息')
            ->setEntityLabelInPlural('视频消息')
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
