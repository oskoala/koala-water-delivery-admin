<?php

namespace App\Http\Requests;


class AddressRequest extends AbstractRequest
{
    public $scenes = [
    ];

    public function rules()
    {
        return [        //全部的验证规则
            'name'       => [
                'required',
            ],
            'phone'      => [
                'required',
                'regex:/^1[3456789][0-9]{9}$/',
            ],
            'province'   => [
                'required',
            ],
            'city'       => [
                'required',
            ],
            'district'   => [
                'required',
            ],
            'detail'     => [
                'required',
            ],
            'is_default' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            "name.required"       => "请输入收货人姓名",
            "phone.required"      => "请输入手机号",
            "province.required"   => "请选择省份",
            "city.required"       => "请选择城市",
            "district.required"   => "请选择区县",
            "detail.required"     => "请输入详细地址",
            "is_default.required" => "请选择是否是默认地址",
        ];
    }
}
