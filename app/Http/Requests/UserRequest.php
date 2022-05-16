<?php

namespace App\Http\Requests;


class UserRequest extends AbstractRequest
{
    public $scenes = [
        'update_info' => 'nickname,mobile',
    ];

    public function rules()
    {
        return [        //全部的验证规则
            'nickname' => [
                'required',
            ],
            'mobile'   => [
                'required',
                'regex:/^1[3456789][0-9]{9}$/',
            ],
        ];
    }

    public function messages()
    {
        return [
            "nickname.required" => "请输入昵称",
            "mobile.required"   => "请输入手机号",
            "mobile.regex"      => "手机号格式有误",
        ];
    }
}
