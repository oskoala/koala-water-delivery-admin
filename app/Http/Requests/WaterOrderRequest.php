<?php


namespace App\Http\Requests;


class WaterOrderRequest extends AbstractRequest
{
    public $scenes = [
    ];

    public function rules()
    {
        return [        //全部的验证规则
            'ticket_type_id' => [
                'required',
            ],
            'num'            => [
                'required',
            ],
            'address_id'     => [
                'required',
                'exists:app_addresses,id'
            ]
        ];
    }

    public function messages()
    {
        return [
            "ticket_type_id.required" => "请选择水票",
            "num.required"            => "请填写叫水数量",
            "address_id.required"     => "请选择配送地址",
            "address_id.exists"       => "配送地址不存在",
        ];
    }
}
