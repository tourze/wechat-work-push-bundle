# WeChatWorkPushBundle

[![PHP Version](https://img.shields.io/badge/php-8.1%2B-blue.svg)]
(https://packagist.org/packages/tourze/wechat-work-push-bundle)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/tourze/php-monorepo/test.yml?branch=master)]
(https://github.com/tourze/php-monorepo/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/php-monorepo)]
(https://codecov.io/gh/tourze/php-monorepo)

[English](README.md) | [中文](README.zh-CN.md)

Enterprise WeChat application push module for Symfony applications.

## Features

- 🚀 Support for all Enterprise WeChat message types
- 📦 Easy integration with Symfony framework
- 🎯 Type-safe message entities with validation
- 🛡️ Built-in security features (safe mode, duplicate check)
- 📊 Rich template card message support
- 🧪 Comprehensive test coverage

## Installation

Install the bundle using Composer:

```bash
composer require tourze/wechat-work-push-bundle
```

## Quick Start

1. Configure the bundle in your Symfony application
2. Create and send a text message:

```php
use WechatWorkPushBundle\Entity\TextMessage;

$message = new TextMessage();
$message->setContent('Hello, Enterprise WeChat!');
$message->setAgentId(1000001);
$message->setToUser('@all');

// Send the message through your service
```

## Usage

### Supported Message Types

The bundle provides entities for all Enterprise WeChat message types:

- **Text Messages** (`TextMessage`)
- **Image Messages** (`ImageMessage`) 
- **Video Messages** (`VideoMessage`)
- **File Messages** (`FileMessage`)
- **Voice Messages** (`VoiceMessage`)
- **Markdown Messages** (`MarkdownMessage`)
- **News Messages** (`NewsMessage`)
- **Text Card Messages** (`TextCardMessage`)
- **Mini Program Messages** (`MpnewsMessage`)

### Template Card Messages

Advanced template card messages for interactive content:

- **Button Interaction** (`ButtonTemplateMessage`)
- **Vote Selection** (`VoteTemplateMessage`) 
- **Multiple Choice** (`MultipleTemplateMessage`)
- **Text Notice** (`TextNoticeTemplateMessage`)
- **News Template** (`NewsTemplateMessage`)
- **Mini Program Notice** (`MiniProgramNoticeMessage`)

### Message Configuration

All message entities support common Enterprise WeChat features:

```php
$message = new TextMessage();
$message->setContent('Your message content');
$message->setAgentId(1000001);        // Application ID
$message->setToUser('user1|user2');   // Target users
$message->setToParty('1|2');          // Target departments  
$message->setToTag('tag1|tag2');      // Target tags
$message->setSafe(1);                 // Enable safe mode
$message->setEnableDuplicateCheck(1); // Enable duplicate check
```

## Advanced Usage

### Event Listeners

The bundle provides event listeners for message sending and revoking:

- `SendMessageListener` - Handles message sending events
- `RevokeMessageListener` - Handles message revocation events

### Custom Repositories

Each message entity comes with a dedicated repository for database operations:

```php
use WechatWorkPushBundle\Repository\TextMessageRepository;

$repository = $entityManager->getRepository(TextMessage::class);
$messages = $repository->findByAgent(1000001);
```

### Request Objects

The bundle includes request objects for API interactions:

- `SendMessageRequest` - For sending messages
- `RevokeMessageRequest` - For revoking messages

## Documentation

For detailed documentation on Enterprise WeChat API:

- [Message Types](https://developer.work.weixin.qq.com/document/path/96457)
- [Template Cards](https://developer.work.weixin.qq.com/document/path/90248)
- [Message Sending](https://developer.work.weixin.qq.com/document/path/96458)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.