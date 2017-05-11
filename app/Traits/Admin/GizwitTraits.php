<?php

// +----------------------------------------------------------------------
// | 机智云openapi基本库
// |    1. 创建用户，获取token
// |    2. 绑定设备
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Traits\Admin;


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
        $header = [
            "x-gizwits-application-id:" . $appId
        ];
        $data = ['phone_id' => $gizwitId];

        $res = curl_do('http://api.gizwits.com/app/users', $header, json_encode($data));

        return json_decode($res, true);
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

        $body["raw"] = $cmd;

        $res = curl_do('http://api.gizwits.com/app/control/' . $did, $header, json_encode($body));

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