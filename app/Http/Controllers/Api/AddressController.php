<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\AddressRequest;
use App\Http\Services\TencentMapService;
use App\Models\AppAddress;
use Jiannei\Response\Laravel\Support\Facades\Response;

class AddressController
{
    /**
     * @return mixed
     * 查看收获地址列表
     */
    public function index()
    {
        $user_id = auth()->id();
        $address = AppAddress::query()->where("user_id", $user_id)->paginate();
        return \Response::success(
            $address
        );
    }

    /**
     * @param AddressRequest $addressRequest
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * 新增收获地址
     */
    public function store(AddressRequest $addressRequest)
    {
        $addressRequest->validate();
        $params            = $addressRequest->only([
            "name",
            "phone",
            "province",
            "city",
            "district",
            "detail",
            "is_default",
        ]);
        $params['user_id'] = auth()->id();

        $address = AppAddress::query()->create($params);

        // 解析地址 获取经纬度坐标
        try {
            $addressDetail = $address->province . $address->city . $address->district . $address->detail;
            $mapService    = new TencentMapService();
            $position      = $mapService->geocoder($addressDetail);
            if ($position['lat'] != 0 && $position['lon'] != 0) {
                $address->update([
                    "lat" => $position['lat'],
                    "lon" => $position['lon'],
                ]);
            }
        } catch (\Exception $exception) {
        }

        if ($params['is_default']) {
            AppAddress::query()->where("user_id", auth()->id())->update([
                "is_default" => false,
            ]);
            $user = auth()->user();
            $user->update([
                "address_id" => $address->id
            ]);
        }

        return Response::success();
    }

    /**
     * @param $id
     * @return mixed
     * 收获地址详细信息
     */
    public function show($id)
    {
        return Response::success(
            AppAddress::query()->find($id)
        );
    }

    /**
     * @param $id
     * @param AddressRequest $addressRequest
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * 修改收获地址
     */
    public function update($id, AddressRequest $addressRequest)
    {
        $addressRequest->validate();
        $params  = $addressRequest->only([
            "name",
            "phone",
            "province",
            "city",
            "district",
            "detail",
            "is_default",
        ]);
        $user_id = auth()->id();
        $address = AppAddress::query()->where("user_id", $user_id)->where("id", $id)->first();
        if (!$address) {
            abort(422, '修改的地址不存在');
        }

        $address->update($params);

        // 解析地址 获取经纬度坐标
        try {
            $addressDetail = $address->province . $address->city . $address->district . $address->detail;
            $mapService    = new TencentMapService();
            $position      = $mapService->geocoder($addressDetail);
            if ($position['lat'] != 0 && $position['lon'] != 0) {
                $address->update([
                    "lat" => $position['lat'],
                    "lon" => $position['lon'],
                ]);
            }
        } catch (\Exception $exception) {

        }

        if ($params['is_default']) {
            AppAddress::query()->where("user_id", auth()->id())->update([
                "is_default" => false,
            ]);
            $user = auth()->user();
            $user->update([
                "address_id" => $id
            ]);
        }
        return Response::success();
    }

    /**
     * @param $id
     * @return mixed
     * 删除收获地址
     */
    public function destroy($id)
    {
        $user_id = auth()->id();
        AppAddress::query()->where("user_id", $user_id)->where("id", $id)->delete();
        return Response::success();
    }
}
