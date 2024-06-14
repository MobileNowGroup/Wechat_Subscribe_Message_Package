# EasyWechat subscribe message notification for Laravel

使用 EasyWechat 的模板消息功能发送 Laravel 消息通知。

`3.0以上版本将使用 __w7corp/easywechat__ 替换原来的 __overtrue/laravel_wechat__ 包`

## 安装

```shell
composer config repositories.mobilenowgroup/subscribe-message git git@github.com:MobileNowGroup/Wechat_Subscribe_Message_Package.git
```

```shell
composer require mobilenowgroup/subscribe-message:"^7.0"
```
## 使用

### 创建通知：

```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use MobileNowGroup\SubscribeMessage\Interfaces\WechatNotification;
use MobileNowGroup\SubscribeMessage\Messages\WechatSubscribeMessage;
use MobileNowGroup\SubscribeMessage\Channels\WechatSubscribeMessageChannel;

class WechatSubScribeMessageNotification extends Notification implements WechatNotification
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

### User Model

```php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MobileNowGroup\SubscribeMessage\Interfaces\ReceiveWechatNotificationInterface;
use MobileNowGroup\SubscribeMessage\Traits\InteractsMiniProgramUserOpenId;

class User extends Authenticatable implements ReceiveWechatNotificationInterface
{
    use HasApiTokens, HasFactory, Notifiable, InteractsMiniProgramUserOpenId;
    
    
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

### 使用View
写在前面：这是一个可选择的优化方案。旨在通过使用view，减少业务逻辑中无语义化的字段。
#### 使用示例：
第一步： 在 resources/views目录下创建一个视图JSON文件，例如：
        resources/views/subscription-message/orderSucessfullyReminder.json

```json
{
  "character_string1": {
    "value": "{{ name }}"
  },
  "date2": {
    "value": "{{ date }}"
  },
  "amount3": {
    "value": "{{ amount }}"
  }
}
```
 第二步：检查你的Notifications下发送订阅消息的类并确定是否更新：
修改原来的SendSubscriptionMessage.php的构造方法和toWechatSubscribeMessage()方法，修改之后是这样的：

```php
/**
     * Create a new notification instance.
     * SendSubscriptionMessage constructor.
     * @param string $templateId
     * @param string $path
     * @param array $data
     * @param string|null $view
     */
    public function __construct(string $templateId, string $path, array $data, ?string $view = null)
    {
        $this->templateId = $templateId;
        $this->path = $path;
        $this->data = $data;
        $this->view = $view;
    }
```

```php
/**
     * @param $notifiable
     * @return WechatSubscribeMessage
     */
    public function toWechatSubscribeMessage($notifiable): WechatSubscribeMessage
    {
        $templateId = $this->templateId;
        $path = $this->path;
        $data = $this->data;

        $wechatSubscribeMesssage = (new WechatSubscribeMessage)
            ->setTemplateId($templateId)
            ->setPage($path)
            ->setState(config('glenmorangie.mini-program.state'));

        if (empty($this->view)) {
            $wechatSubscribeMesssage->setData($data);
        } else {
            $wechatSubscribeMesssage->view($this->view, $data);
        }

        return $wechatSubscribeMesssage;
    }
```

第三步：实例化SendSubscriptionMessage的时候，在构造方法中最后一个参数里传入JSON视图文件的"路径"就可以了。 使用view前后的传参变化，
原来：
```php
$user->notify(new SendSubscriptionMessage(
                'tempate_id',
                '/pages/home/index',
                [
                    'thing1' => [
                        'value' => $name
                    ],
                    'time2' => [
                        'value' => date('Y-m-d H:i:s'),
                    ],
                    'thing3' => [
                        'value' => $amount,
                    ]
                ]
            ));
```
使用view后：
```php
$user->notify(new SendSubscriptionMessage(
                'tempate_id',
                '/pages/home/index',
                [
                    'name' => $name,
                    'date' => date('Y-m-d H:i:s'),
                    'amount' => $amount
                ]
            , 'subscription-message/orderSucessfullyReminder'));

```
