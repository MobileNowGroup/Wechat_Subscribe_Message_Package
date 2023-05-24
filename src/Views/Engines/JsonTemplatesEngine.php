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
        $json = file_get_contents($path);
        $template = json_decode($json, true);

        $content = $this->replaceVariables($template, $data);
        return $content;
    }


    /**
     * @param $template
     * @param array $data
     * @return array|false|string|string[]
     */
    public function replaceVariables($template, array $data)
    {
        $content = json_encode($template);
        $variables = array_keys($data);

        foreach ($variables as $variable) {
            if (is_string($data[$variable]) || is_array($data[$variable])) {
                $content = str_replace("{{ $variable }}", $data[$variable], $content);
            }
        }

        return $content;
    }
}