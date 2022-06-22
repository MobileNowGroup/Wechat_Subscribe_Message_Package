# EasyWechat subscribe message notification for Laravel

使用 EasyWechat 的模板消息功能发送 Laravel 消息通知。

## 安装

```shell
composer require mobilenowgroup/subscribe-message
```

## 使用

### 创建通知：

```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use MobileNowGroup\SubscribeMessage\Message\WechatSubScribeMessage;
use MobileNowGroup\SubscribeMessage\Channels\WechatSubscribeMessageChannel;

class WechatSubScribeMessageNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return [WechatSubscribeMessageChannel::class];
    }

    public function toWechatSubscribeMessage($notifiable)
    {
        return (new WechatSubscribeMessage)
            ->setTemplateId('template_id')
            ->setPage('page')
            ->setData([
                'keyword1' => 'keyword1',
                'keyword2' => 'keyword2',
                'keyword3' => 'keyword3',
                'keyword4' => 'keyword4',
            ]);
    }
}
```

### 发送

```php
$user->notify(new WechatSubscribeMessageNotification($formId));

```
### 发布事件监听
```
php artisan vendor:publish --provider="MobileNowGroup\SubscribeMessage\WechatSubscribeMessageServiceProvider" 
```

### Events
    添加如下代码到 App\Providers\EventServiceProvider
```php
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
  
        'MobileNowGroup\SubscribeMessage\WechatSubscribeMessageSent' => [
            'App\Listeners\WechatSubscribeMessageListener',
        ],
    ];
```