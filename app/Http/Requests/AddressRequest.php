<?php

namespace App\Http\Requests;


class AddressRequest extends AbstractRequest
{
    public $scenes = [
    ];

    public function rules()
    {
        return [        //全部的验证规则
            'name'     => [
                'required',
            ],
            'phone'    => [
                'required',
                'regex:/^1[3456789][0-9]{9}$/',
            ],
            'village'  => [
                'required',
            ],
            'building' => [
                'required',
            ],
            'unit'     => [
                'required',
            ],
            'room'     => [
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            "name.required"     => "请输入收货人姓名",
            "phone.required"    => "请输入手机号",
            "village.required"  => "请输入小区",
            "building.required" => "请输入楼号",
            "unit.required"     => "请输入单元",
            "room.required"     => "请输入房间号",
        ];
    }
}
