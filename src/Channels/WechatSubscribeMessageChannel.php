<?php

namespace MobileNowGroup\SubscribeMessage\Channels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use MobileNowGroup\SubscribeMessage\Exceptions\WechatSubscribeMessageException;
use MobileNowGroup\SubscribeMessage\Events\WechatSubscribeMessageSent;

class WechatSubscribeMessageChannel
{
    /**
     * 发送指定的通知.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if ($notifiable instanceof Model) {
            $to = $notifiable->openid ?? $notifiable->routeNotificationForOpenid($notification);
        }

        $message = $notification->toWechatSubscribeMessage($notifiable)
            ->to($to);

        $result = \EasyWeChat::miniProgram()->subscribe_message->send($message->toArray());
        event(new WechatSubscribeMessageSent($result));

        if ($result['errcode'] != 0) {
            throw new WechatSubscribeMessageException($result['errmsg'], $result['errcode']);
        }
    }
}
