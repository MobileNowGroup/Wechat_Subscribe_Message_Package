<?php

use PHPUnit\Framework\TestCase;

final class JsonTemplateEngineTest extends TestCase
{
    public function testGet()
    {
        $path = './stubs/views/messageTemplate.json';
        $data = [
            'event' => 'xxx活动',
            'weight' => 200.23,
            'code1' => 'ABCDabcd',
            'code2' => '!@#$',
            'password' => 'ABab!@123',
            'time' => '2019年10月1日 15:01',
            'date' => '2024年10月1日~2024年10月7日',
            'price' => '66.66元',
            'phone_number' => '+86-0766-66888866',
            'car_number' => '粤A8Z888挂',
            'user' => 'Johan',
            'status' => '配送中',
            'position' => '客厅',
            'people' => 20,
        ];

        $engine = new \MobileNowGroup\SubscribeMessage\Views\Engines\JsonTemplatesEngine();
        $content = json_decode($engine->get($path, $data), true);

        $this->assertEquals($data['event'], $content['thing1']['value']);
        $this->assertEquals($data['weight'], $content['number2']['value']);
        $this->assertEquals($data['code1'], $content['letter3']['value']);
        $this->assertEquals($data['code2'], $content['symbol4']['value']);
        $this->assertEquals($data['password'], $content['character_string5']['value']);
        $this->assertEquals($data['time'], $content['time6']['value']);
        $this->assertEquals($data['date'], $content['date7']['value']);
        $this->assertEquals($data['price'], $content['amount8']['value']);
        $this->assertEquals($data['phone_number'], $content['phone_number9']['value']);
        $this->assertEquals($data['car_number'], $content['car_number10']['value']);
        $this->assertEquals($data['user'], $content['name11']['value']);
        $this->assertEquals($data['status'], $content['phrase12']['value']);
        $this->assertEquals($data['position'], $content['enum13']['value']);
        $this->assertEquals($data['people'], $content['amount12']['value']);
    }
}