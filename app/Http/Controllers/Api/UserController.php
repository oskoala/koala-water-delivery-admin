<?php


namespace App\Http\Controllers\Api;

use App\Models\AppUser;
use EasyWeChat\Factory;
use Illuminate\Http\Request;

class UserController
{
    public function login(Request $request)
    {

        $config = [
            'app_id'        => config("wechat.app_id"),
            'secret'        => config("wechat.app_secret"),

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file'  => __DIR__ . '/wechat.log',
            ],
        ];

        $app = Factory::miniProgram($config);

        $code          = $request->input("code");
        $encryptedData = $request->input("encryptedData");
        $iv            = $request->input("iv");

        $session = $app->auth->session($code);

        $openid        = $session['openid'];
        $session_key   = $session['session_key'];
        $decryptedData = $app->encryptor->decryptData($session_key, $iv, $encryptedData); // 此处不解密也可以 因为此处用不到union id

        if (!AppUser::query()->where("openid", $openid)->exists()) {
            AppUser::query()->create([
                "openid"   => $openid,
                "nickname" => $decryptedData['nickName'],
                "avatar"   => $decryptedData['avatarUrl'],
                "mobile"   => "",
            ]);
        }

    }
}
