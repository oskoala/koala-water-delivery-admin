<?php

namespace App\Admin\Metrics\Examples;

use App\Models\AppWaterOrder;
use App\Status\WaterOrderStatus;
use Dcat\Admin\Widgets\Metrics\Card;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class CanceledWaterOrder extends Card
{
    /**
     * 卡片底部内容.
     *
     * @var string|Renderable|\Closure
     */
    protected $footer;

    /**
     * 初始化卡片.
     */
    protected function init()
    {
        parent::init();

        $this->title('取消预约叫水数量');

        $this->height(0);
    }

    /**
     * 处理请求.
     *
     * @param Request $request
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $this->content(
            $this->getStatistics()['value']
        );
    }

    /**
     * 渲染卡片内容.
     *
     * @return string
     */
    public function renderContent()
    {
        $content = parent::renderContent();
        $url     = route("dcat.admin.app-water-order.index") . "?status=" . WaterOrderStatus::canceled;

        return <<<HTML
<a href="{$url}">
    <div class="d-flex justify-content-between align-items-center mt-1">
        <h2 class="ml-1 font-lg-1">{$content}</h2>
    </div>
</a>
HTML;
    }

    private function getStatistics()
    {
        $res = [
            "value" => 0
        ];

        $builder = AppWaterOrder::query();
        $builder->where("status", WaterOrderStatus::canceled);
        $res['value'] = $builder->count();
        return $res;
    }
}
