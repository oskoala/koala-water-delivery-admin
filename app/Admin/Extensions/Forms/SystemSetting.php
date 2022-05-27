<?php

namespace App\Admin\Extensions\Forms;

use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Form\NestedForm;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

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

            $this->photos('sliders', '幻灯片')
                ->path("sliders")
                ->pageSize(16)
                ->nametype('datetime')
                ->limit(9)
                ->remove(true);  //可删除

            $this->time("split_time", "超过第二天送达")->help("比如设置17:00，则为17点前下单当天送达，否则第二天送达");
        });
        $this->tab("关于我们", function () {
            $this->editor("about_us", "关于我们");
        });


        $this->tab("用户服务协议", function () {
            $this->editor("user_service_agreement", "用户服务协议");
        });


        $this->tab("隐私与政策", function () {
            $this->editor("privacy_policy", "隐私与政策");
        });

        $this->tab("通知公告", function () {
            $this->table("notices", function (NestedForm $table) {
                $table->text("title", "标题");
                $table->editor("content", "内容");
                $table->date("date", "发布日期");
            })->label("通知公告");
        });

        $this->tab("核销方式", function () {
            $this->checkbox("finished_way", "核销方式")->options([
                "scan"              => "配送员扫码核销",
                "deliverer_confirm" => "配送员点击确认送达"
            ]);
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
        Cache::forget("system_config");
    }
}

