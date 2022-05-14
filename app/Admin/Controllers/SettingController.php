<?php


namespace App\Admin\Controllers;


use App\Admin\Extensions\Forms\SystemSetting;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;

class SettingController
{
    public function front(Content $content)
    {
        return $content->body(
            Card::make("系统设置", SystemSetting::make())
        );
    }

}
