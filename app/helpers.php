<?php

use Illuminate\Support\Arr;

if (!function_exists('custom_config')) {
    function custom_config($key = null, $value = "")
    {
        $folder = storage_path("app/config/");
        $path   = $folder . "custom.json";

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        if (file_exists($path)) {
            $config = file_get_contents($path);
            $config = json_decode($config, true);
        } else {
            file_put_contents($path, "");
            $config = file_get_contents($path);
            $config = json_decode($config, true);
        }

        if (is_array($key)) {
            // 保存
            foreach ($key as $k => $v) {
                Arr::set($config, $k, $v);
            }

            file_put_contents($path, json_encode($config));

            return;
        }

        if ($key === null) {
            return $config;
        }

        return Arr::get($config, $key, $value);
    }
}


if (!function_exists("get_unique_no")) {
    function get_unique_no($len = 0)
    {
        $int = '';

        while (strlen($int) != $len) {
            $int .= mt_rand(0, 9);
        }

        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8) . $int;
    }
}
