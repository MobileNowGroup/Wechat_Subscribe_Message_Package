<?php

namespace MobileNowGroup\SubscribeMessage\Interfaces;

use MobileNowGroup\SubscribeMessage\Messages\WechatSubscribeMessage;

interface WechatNotification
{

    public function toWechatSubscribeMessage(object $notifiable): WechatSubscribeMessage;
}