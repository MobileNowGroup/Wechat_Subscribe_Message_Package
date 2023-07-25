<?php

namespace MobileNowGroup\SubscribeMessage\Interfaces;

interface ReceiveWechatNotificationInterface
{

    public function routeNotificationForOpenid(): string;

}