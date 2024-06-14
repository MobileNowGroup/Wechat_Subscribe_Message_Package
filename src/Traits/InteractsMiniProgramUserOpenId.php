<?php
namespace MobileNowGroup\SubscribeMessage\Traits;


trait InteractsMiniProgramUserOpenId
{
    public function routeNotificationForOpenid(): string
    {
        return $this->open_id;
    }

}