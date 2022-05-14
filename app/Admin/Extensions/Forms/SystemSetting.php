<?php

namespace App\Admin\Extensions\Forms;

use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Illuminate\Support\Arr;

class SystemSetting extends Form implements LazyRenderable
{
    use LazyWidget;

    /**
     * 处理表单请求.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function handle(array $input)
    {
        foreach (Arr::dot($input) as $k => $v) {
            $this->update($k, $v);
        }
        return $this->response()->success('设置成功');
    }

    /**
     * 构建表单.
     */
    public function form()
    {
        $this->tab("基本信息", function () {
            $this->text("name", "站点名称");
        });
    }

    /**
     * 返回表单数据.
     *
     * @return array
     */
    public function default()
    {
        return custom_config();
    }

    /**
     * 更新配置.
     *
     * @param string $key
     * @param string $value
     */
    protected function update($key, $value)
    {
        custom_config([$key => $value]);
    }
}

