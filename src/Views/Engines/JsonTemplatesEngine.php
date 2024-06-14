<?php

namespace MobileNowGroup\SubscribeMessage\Views\Engines;

use Illuminate\Contracts\View\Engine;

class JsonTemplatesEngine implements Engine
{
    /**
     * @param string $path
     * @param array $data
     * @return array|false|string|string[]
     */
    public function get($path, array $data = [])
    {
        // 错误处理
        if (!file_exists($path)) {
            throw new \Exception("文件 {$path} 不存在!");
        }

        $json = file_get_contents($path);
        return $this->replaceVariables($json, $data);
    }


    /**
     * @param $template
     * @param array $data
     * @return array|false|string|string[]
     */
    public function replaceVariables($template, array $data)
    {
        $variables = array_keys($data);

        foreach ($variables as $variable) {
            // 类型检查
            if (!is_string($data[$variable]) && !is_numeric($data[$variable])) {
                throw new \Exception("无法识别的数据类型!模板数据应为字符串或数字");
            }

            $template = str_replace("{{ $variable }}", $data[$variable], $template);
        }

        return $template;
    }
}