<?php

namespace MobileNowGroup\SubscribeMessage\Channels;

use MobileNowGroup\SubscribeMessage\Interfaces\ReceiveWechatNotificationInterface;
use MobileNowGroup\SubscribeMessage\Interfaces\WechatNotification;
use MobileNowGroup\SubscribeMessage\Exceptions\WechatSubscribeMessageException;
use MobileNowGroup\SubscribeMessage\Events\WechatSubscribeMessageSent;

class WechatSubscribeMessageChannel
{
    /**
     * 发送指定的通知.
     *
     * @param mixed $notifiable
     * @param WechatNotification $notification
     * @return void
     * @throws WechatSubscribeMessageException
     */
    public function send(ReceiveWechatNotificationInterface $notifiable, WechatNotification $notification)
    {
        $to = $notifiable->routeNotificationForOpenid();

        $message = $notification->toWechatSubscribeMessage($notifiable)
            ->to($to);

        $result = $message->send();

        event(new WechatSubscribeMessageSent($result));

        if ($result['errcode'] != 0) {
            throw new WechatSubscribeMessageException($result['errmsg'], $result['errcode']);
        }
    }
}
