<?php

// +----------------------------------------------------------------------
// | 云码库cgi程序说明，码库在宏芯达的云服务器上运行，
// | 如果客户量产里时需要自己架设服务器，有码库合作，我们会提供新的cgi程序配套。
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Traits\Admin;

use Illuminate\Support\Facades\Cache;

trait QianhaiMdl
{
    
    /**
     * 获取支持的电器类型(cmd=ele_type)
     * @Author Krlee
     *
     * @return mixed
     */
    public function getEleType()
    {
        $result = Cache::store('file')->get('ele_type');
        if ($result) {
            return json_decode($result, true);
        }

        $result = curl_do('http://120.25.102.203:8080/cgi-bin/t.cgi?cmd=ele_type');
        Cache::store('file')->forever('ele_type', $result);

        return json_decode($result, true);
    }

    /**
     * 获取某电器下的按键（如电视）cmd="device_key"&id=8192
     * @Author Krlee
     *
     * @param string $id   电器类型，如电视id=8192
     * @return mixed
     */
    public function getDeviceKey($id = '')
    {
        $result = Cache::store('file')->get('device_key_' . $id);
        if ($result) {
            return json_decode($result, true);
        }

        $result = curl_do('http://120.25.102.203:8080/cgi-bin/t.cgi?cmd=device_key&id=' . $id);
        Cache::store('file')->forever('device_key_' . $id, $result);

        return json_decode($result, true);
    }

    /**
     * 获取电器类型下面的品牌
     * @Author Krlee
     *
     * @param string $id  电器类型，如电视id=8192
     * @return mixed
     */
    public function getDeviceBrand($id = '')
    {
        $result = Cache::store('file')->get('device_brand_' . $id);
        if ($result) {
            return json_decode($result, true);
        }

        $result = curl_do('http://120.25.102.203:8080/cgi-bin/t.cgi?cmd=device_brand&id=' . $id);

        Cache::store('file')->forever('device_brand_' . $id, $result);

        return json_decode($result, true);
    }

    /**
     * 获取电器品牌下面的数据
     * @Author Krlee
     *
     * @param string $id      电器类型，如电视id=8192
     * @param string $brand   品牌，长虹（Changhong）
     * @return mixed
     */
    public function getDeviceCount($id = '', $brand = '')
    {
        $result = Cache::store('file')->get('device_count_' . $id . '_' . $brand);
        if ($result) {
            return json_decode($result, true);
        }

        $result = curl_do('http://120.25.102.203:8080/cgi-bin/t.cgi?cmd=device_count&id=' . $id . '&brand=' . $brand);
        Cache::store('file')->forever('device_count_' . $id . '_' . $brand, $result);

        return json_decode($result, true);
    }

    //(5)获取指定电器，品牌，相应按键的数据cmd=key_val&id=8192&row=53&key=8193

    /**
     * 获取指定电器，品牌，相应按键的数据
     * @Author Krlee
     *
     * @param string $id    电器类型，如电视id=8192
     * @param string $row
     * @param string $key   按键值（某电器下的按键）
     * @return mixed
     */
    public function key_val($id = '', $row = '', $key = '')
    {
        $result = Cache::store('file')->get('key_val_' . $id . '_' . $row . '_' . $key);
        if ($result) {
            return json_decode($result, true);
        }

        $result = curl_do('http://120.25.102.203:8080/cgi-bin/t.cgi?cmd=key_val&id=' . $id . '&row=' . $row . '&key=' . $key);
        Cache::store('file')->forever('key_val_' . $id . '_' . $row . '_' . $key, $result);

        return json_decode($result, true);
    }

    //(6)获取学习数据的返回字节（方法1，112字节）
    public function getStudyData($data = '')
    {
        $result = Cache::store('file')->get('study_data_' . $data);
        if ($result) {
            return json_decode($result, true);
        }

        $result = curl_do('http://120.25.102.203:8080/cgi-bin/t.cgi?cmd=study_data&data=' . $data);
        Cache::store('file')->forever('study_data_' . $data, $result, 0);

        return json_decode($result, true);
    }

    //(7)获取电器类型下面的型号cmd=device_models&id=8192
    public function getDeviceModel($id = '')
    {
        $result = Cache::store('file')->get('device_model_' . $id);
        if ($result) {
            return json_decode($result, true);
        }

        $result = curl_do('http://120.25.102.203:8080/cgi-bin/t.cgi?cmd=device_model&id=' . $id);
        Cache::store('file')->forever('device_model_' . $id, $result);

        return json_decode($result, true);
    }

}

