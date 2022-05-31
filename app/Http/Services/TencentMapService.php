<?php


namespace App\Http\Services;


use GuzzleHttp\Client;

class TencentMapService
{
    /**
     * @param $path
     * @param $params
     * @return string
     * 获取签名
     */
    private function sign($path, $params)
    {
        $secret_key = env("TENCENT_MAP_SECRET_KET");
        arsort($params);
        return md5($path . "?" . urldecode(http_build_query($params)) . $secret_key);
    }

    /**
     * @param $address
     * @return array|int[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     * 地址解析
     */
    public function geocoder($address)
    {
        $domain = "https://apis.map.qq.com";
        $path   = "/ws/geocoder/v1/";
        $params = [
            "key"     => env("TENCENT_MAP_KET"),
            "address" => $address,
        ];

        $sign = $this->sign($path, $params);

        $params['sig'] = $sign;
        $url           = $domain . $path . "?" . http_build_query($params);

        $client   = new Client();
        $response = $client->get($url);
        $resArr   = json_decode($response->getBody()->getContents(), true);
        if (isset($resArr['status']) && $resArr['status'] == 0) {
            return [
                "lat" => $resArr['result']['location']['lat'],
                "lon" => $resArr['result']['location']['lng'],
            ];
        }
        return [
            "lng" => 0,
            "lat" => 0,
        ];
    }
}
