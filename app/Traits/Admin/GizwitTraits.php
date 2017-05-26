<?php

// +----------------------------------------------------------------------
// | 机智云openapi基本库
// |    1. 创建用户，获取token
// |    2. 绑定设备
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Traits\Admin;


use Illuminate\Support\Facades\Cache;

trait GizwitTraits
{

    /**
     * 登录匿名用户，获取token
     * @Author Krlee
     *
     * @param $appId
     * @param $gizwitId  用户表中机智云唯一ID
     * @return array
     */
    public function createGizwitUser($appId, $gizwitId)
    {
        $result = Cache::store('file')->get('user_token_' . $appId . $gizwitId);
        if (!empty($result)) {
            return $result;
        }

        $header = [
            "x-gizwits-application-id:" . $appId
        ];
        $data = ['phone_id' => $gizwitId];

        $res = curl_do('http://api.gizwits.com/app/users', $header, json_encode($data));
        $result = json_decode($res, true);
        Cache::store('file')->put('user_token_' . $appId . $gizwitId, $result, 6 * 24 * 60);

        return $result;
    }

    /**
     * 根据mac地址绑定设备，返回设备状态信息
     * @Author Krlee
     *
     * @param $appId
     * @param $product_key
     * @param $product_secret
     * @param $userToken
     * @param $mac
     * @param string $remack
     * @param string $dev_alias
     * @return array
     */
    public function bingDevice($appId, $product_key, $product_secret, $userToken, $mac, $remack = '', $dev_alias = '')
    {
        $nowTime = time();
        $header = [
            'x-gizwits-application-id: ' . $appId,
            'x-gizwits-signature: ' . $this->_createSign($nowTime, $product_secret),
            'x-gizwits-timestamp: ' . $nowTime,
            'x-gizwits-user-token: ' . $userToken
        ];

        $data = compact('product_key', 'mac', 'remack', 'dev_alias');

        $res = curl_do('http://api.gizwits.com/app/bind_mac', $header, json_encode($data));

        return json_decode($res, true);
    }

    public function unbingDevice($appId, $userToken, $dids)
    {
        $nowTime = time();
        $header = [
            'x-gizwits-application-id: ' . $appId,
            'x-gizwits-user-token: ' . $userToken
        ];

        $body = ['devices' => [$dids]];

        $res = curl_do('http://api.gizwits.com/app/bindings', $header, json_encode($body), 'DELETE');

        return json_decode($res, true);
    }

    /**
     * 获取服务商绑定的设备数据
     * @Author Krlee
     *
     */
    public function updateGizwitDevice($appId, $userToken)
    {
        $nowTime = time();
        $header = [
            'x-gizwits-application-id: ' . $appId,
            'x-gizwits-user-token: ' . $userToken
        ];

        $res = curl_do('http://api.gizwits.com/app/bindings?show_disabled=1&limit=20&skip=0', $header);

        return json_decode($res, true);
    }

    /**
     * 远程控制设备
     * @Author Krlee
     *
     */
    public function sendControlGiz($appId, $userToken, $did, $cmd)
    {
        $header = [
            'x-gizwits-application-id: ' . $appId,
            'x-gizwits-user-token: ' . $userToken
        ];

        $str = "";
        foreach ($cmd as $v){
            $str .= substr('0'.dechex($v),-2);
        }

        // 关闭
        //$str = "11111111111111110000101100010000000000010000000100011010000000110000000100000001000000100000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
        $body = ['attrs' => ['RAW_SMARTHOME' => $str]];

        $res = curl_do('http://api.gizwits.com/app/control/' . $did, $header, json_encode($body));

        return json_decode($res, true);
    }

    /**
     * 获取历史数据
     * @Author Krlee
     *
     */
    public function getHistoryData($appId, $userToken, $did)
    {
        $url = 'http://api.gizwits.com/app/devices/' . $did . '/raw_data?type=online&start_time=1495532386&end_time=1495618813&skip=0&limit=100&sort=desc';
        $header = [
            'x-gizwits-application-id: ' . $appId,
            'x-gizwits-user-token: ' . $userToken
        ];

        $res = curl_do($url, $header);

        return json_decode($res, true);
    }


    /**
     * 生产sign
     * @Author Krlee
     *
     */
    private function _createSign($timestamp, $secret)
    {
        return strtolower(md5($secret . $timestamp));
    }
}