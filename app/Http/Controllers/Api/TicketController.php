<?php


namespace App\Http\Controllers\Api;


use App\Http\Services\TicketPackageOrderService;
use App\Models\AppTicketPackage;
use App\Models\AppTicketPackageOrder;
use Illuminate\Http\Request;

class TicketController
{
    /**
     * @param Request $request
     * @return mixed
     * 水票包列表
     */
    public function list(Request $request)
    {
        $builder = AppTicketPackage::query();
        $builder->where("show", true);
        $builder->orderBy("order");

        $params = $request->input();

        if (isset($params['recommend']) && $params['recommend']) {
            $builder->where("recommend", true);
        }

        $builder->select([
            "id",
            "title",
            "image",
            "ticket_type_id",
            "num",
            "price",
            "scribing_price",
            "show",
            "order",
            "recommend",
        ]);

        $builder->with([
            "type:id,name"
        ]);

        $items = $builder->paginate($params['page_size'] ?? 10);

        return \Response::success($items);
    }

    /**
     * @param $id
     * 水票包详情
     */
    public function show($id)
    {
        $builder = AppTicketPackage::query()->where("id", $id)->with([
            "type:id,name",
        ]);

        $item = $builder->first();

        return \Response::success($item);
    }
}
