<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Models\AppUser;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class UserController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * 登录方法
     */
    public function login(Request $request)
    {
        $config = [
            'app_id'        => config("wechat.app_id"), // 小程序APPID
            'secret'        => config("wechat.app_secret"), // 小程序密钥

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

        // 用户不存在就创建
        if (!AppUser::query()->where("openid", $openid)->exists()) {
            $user = AppUser::query()->create([
                "openid"   => $openid,
                "nickname" => $decryptedData['nickName'],
                "avatar"   => $decryptedData['avatarUrl'],
                "mobile"   => "",
            ]);
        } else { // 用户存在则获取到这个用户信息
            $user = AppUser::query()->where("openid", $openid)->first();
        }

        return Response::success([
            'user'  => $user,
            'token' => auth("api")->tokenById($user->id),  // 生成token信息
        ]);
    }

    public function info(Request $request)
    {
        $user_id = auth()->id();
        return Response::success(
            AppUser::query()->find($user_id)
        );
    }

    public function infoUpdate(UserRequest $userRequest)
    {
        $userRequest->scene("update_info")->validate();
        $user = auth()->user();
        $user->update($userRequest->only([
            "nickname",
            "mobile",
        ]));
        return Response::success();
    }
}
