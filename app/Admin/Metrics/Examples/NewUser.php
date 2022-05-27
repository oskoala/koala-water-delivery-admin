<?php

namespace App\Admin\Metrics\Examples;

use App\Models\AppUser;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;

class NewUser extends Line
{
    /**
     * 初始化卡片内容
     *
     * @return void
     */
    protected function init()
    {
        parent::init();

        $this->title('新用户');
        $this->dropdown([
            '7'   => '最近7天',
            '15'  => '最近半月',
            '30'  => '最近一月',
            '365' => '最近一年',
        ]);
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle(Request $request)
    {
        $statistics = $this->getStatistics($request->get('option', 7));

        $this->withContent($statistics['count']);
        // 图表数据
        $this->withChart($statistics['values']);
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     * @param array $names
     * @return $this
     */
    public function withChart(array $data)
    {
        return $this->chart([
            'series' => [
                [
                    'name' => $this->title,
                    'data' => $data,
                ],
            ],
            'xaxis'  => [
                "type" => 'datetime'
            ],
        ]);
    }

    /**
     * 设置卡片内容.
     *
     * @param string $content
     *
     * @return $this
     */
    public function withContent($content)
    {
        return $this->content(
            <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$content}</h2>
    <span class="mb-0 mr-1 text-80">{$this->title}</span>
</div>
HTML
        );
    }

    private function getStatistics($days)
    {
        $res = [
            "count"  => AppUser::query()->where("created_at", ">=", now()->subDays($days))->where("created_at", "<=", now())->count(),
            "values" => []
        ];
        for ($i = $days; $i >= 0; $i--) {
            $day_start       = now()->subDays($i)->startOfDay();
            $day_end         = now()->subDays($i)->endOfDay();
            $res['values'][] = [
                'y' => AppUser::query()
                    ->where("created_at", ">=", $day_start)
                    ->where("created_at", "<=", $day_end)->count(),
                'x' => $day_start->getTimestamp()
            ];
        }

        return $res;
    }
}
