# 企业微信推送模块

[![PHP Version](https://img.shields.io/badge/php-8.1%2B-blue.svg)](https://packagist.org/packages/tourze/wechat-work-push-bundle)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/tourze/php-monorepo/test.yml?branch=master)]
(https://github.com/tourze/php-monorepo/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/php-monorepo)]
(https://codecov.io/gh/tourze/php-monorepo)

[English](README.md) | [中文](README.zh-CN.md)

企业微信应用推送模块，专为 Symfony 应用程序设计。

## 功能特性

- 🚀 支持所有企业微信消息类型
- 📦 与 Symfony 框架轻松集成
- 🎯 类型安全的消息实体与验证
- 🛡️ 内置安全功能（安全模式、重复检查）
- 📊 丰富的模板卡片消息支持
- 🧪 全面的测试覆盖

## 安装

使用 Composer 安装包：

```bash
composer require tourze/wechat-work-push-bundle
```

## 快速开始

1. 在您的 Symfony 应用程序中配置包
2. 创建并发送文本消息：

```php
use WechatWorkPushBundle\Entity\TextMessage;

$message = new TextMessage();
$message->setContent('你好，企业微信！');
$message->setAgentId(1000001);
$message->setToUser('@all');

// 通过您的服务发送消息
```

## 使用方法

### 支持的消息类型

该包为所有企业微信消息类型提供实体：

- **文本消息** (`TextMessage`)
- **图片消息** (`ImageMessage`) 
- **视频消息** (`VideoMessage`)
- **文件消息** (`FileMessage`)
- **语音消息** (`VoiceMessage`)
- **Markdown消息** (`MarkdownMessage`)
- **图文消息** (`NewsMessage`)
- **文本卡片消息** (`TextCardMessage`)
- **小程序消息** (`MpnewsMessage`)

### 模板卡片消息

用于交互式内容的高级模板卡片消息：

- **按钮交互型** (`ButtonTemplateMessage`)
- **投票选择型** (`VoteTemplateMessage`) 
- **多项选择型** (`MultipleTemplateMessage`)
- **文本通知型** (`TextNoticeTemplateMessage`)
- **图文展示型** (`NewsTemplateMessage`)
- **小程序通知型** (`MiniProgramNoticeMessage`)

### 消息配置

所有消息实体都支持企业微信的通用功能：

```php
$message = new TextMessage();
$message->setContent('您的消息内容');
$message->setAgentId(1000001);        // 应用ID
$message->setToUser('user1|user2');   // 目标用户
$message->setToParty('1|2');          // 目标部门
$message->setToTag('tag1|tag2');      // 目标标签
$message->setSafe(1);                 // 启用安全模式
$message->setEnableDuplicateCheck(1); // 启用重复检查
```

## 高级用法

### 事件监听器

该包提供用于消息发送和撤回的事件监听器：

- `SendMessageListener` - 处理消息发送事件
- `RevokeMessageListener` - 处理消息撤回事件

### 自定义仓库

每个消息实体都配备专用的数据库操作仓库：

```php
use WechatWorkPushBundle\Repository\TextMessageRepository;

$repository = $entityManager->getRepository(TextMessage::class);
$messages = $repository->findByAgent(1000001);
```

### 请求对象

该包包含用于 API 交互的请求对象：

- `SendMessageRequest` - 用于发送消息
- `RevokeMessageRequest` - 用于撤回消息

## 依赖项

该包依赖以下组件：

- Symfony 6.4+
- Doctrine ORM 3.0+
- PHP 8.1+

## 高级功能

### 消息特性 (Traits)

包含多个可复用的消息特性：

- `AgentTrait` - 应用代理功能
- `SafeTrait` - 安全模式功能  
- `DuplicateCheckTrait` - 重复检查功能
- `IdTransTrait` - ID转换功能

### 验证约束

所有实体都包含完整的验证约束：

- 字符串长度限制验证
- URL格式验证
- 数组计数验证
- 防止数据库溢出

## 文档

企业微信 API 详细文档：

- [消息类型](https://developer.work.weixin.qq.com/document/path/96457)
- [模板卡片](https://developer.work.weixin.qq.com/document/path/90248)
- [消息推送](https://developer.work.weixin.qq.com/document/path/96458)

## 许可证

本项目采用 MIT 许可证 - 详见 [LICENSE](LICENSE) 文件。

## 贡献

欢迎贡献！请随时提交 Pull Request。