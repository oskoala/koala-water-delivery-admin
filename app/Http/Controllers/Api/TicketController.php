<?php


namespace App\Http\Controllers\Api;


use App\Models\AppTicketType;
use App\Models\AppUserTicket;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class TicketController
{
    /**
     * @param Request $request
     * @return mixed
     * 水票列表
     */
    public function list(Request $request)
    {
        $builder = AppTicketType::query();
        $builder->where("show", true);
        $builder->orderBy("order");

        $params = $request->input();

        if (isset($params['recommend']) && $params['recommend']) {
            $builder->where("recommend", true);
        }

        $builder->select([
            "id",
            "name",
            "image",
            "price",
            "min_buy_num",
            "show",
            "order",
            "recommend",
        ]);

        $items = $builder->paginate($params['page_size'] ?? 10);

        return Response::success($items);
    }

    /**
     * @param $id
     * 水票详情
     */
    public function show($id)
    {
        $builder = AppTicketType::query()->where("id", $id);

        $item = $builder->first();

        return Response::success($item);
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     * 我的水票
     */
    public function myTicket()
    {
        $user    = auth()->user();
        $builder = AppUserTicket::query();
        $builder->with([
            "ticket_type:id,name,image"
        ]);
        $builder->where("user_id", $user->id);
        $tickets = $builder->paginate();
        return Response::success($tickets);
    }
}
