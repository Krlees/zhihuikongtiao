<?php

if (!function_exists('humanFilesize')) {
    /**
     * 返回更好的尺寸
     *
     * @param $bytes
     * @param int $decimals
     * @return string
     */
    function human_filesize($bytes, $decimals = 2)
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
}

if (!function_exists('isImage')) {
    /**
     * 判断文件的MIME类型是否为图片
     */
    function isImage($mimeType)
    {
        return starts_with($mimeType, 'image/');
    }
}

if (!function_exists('arrayAddField')) {
    /**
     * 在数组中插入指定的key和值 <递归>
     * @param $array
     * @param $filed
     * @param $value
     *
     * @return array
     */
    function array_add_field($array, $key, $value = true)
    {

        if (empty($array)) {
            return [];
        }

        foreach ($array as $k => $v) {
            $array[$k][$key] = $value;
            if (is_array($v)) {
                array_add_field($v, $key, $value);
            }
        }

        return $array;

    }
}

if (!function_exists('array2xml')) {
    /**
     * 数组转XML
     * @Author Krlee
     *
     * @param $arr
     * @return string
     */
    function array2xml($arr)
    {
        $xml = '<xml>';
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
            } else {
                $xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
            }
        }
        $xml .= '</xml>';
        return $xml;
    }
}


if (!function_exists('custom_config')) {
    /**
     * 自定义错误码
     * @Author Krlee
     *
     * @param $code
     * @return mixed
     */
    function custom_config($code)
    {
        $arr = [
            '0' => '操作成功',
            '1004' => '缺少必须参数',
            '9000' => '数据库插入失败'
        ];

        return array_get($arr, $code, 80001);
    }
}

if (!function_exists('objToArr')) {
    /**
     * 过滤掉数组里面的对象，全部转为数组【只支持对象或二维数组】
     * @Author Krlee
     *
     * @param $arr   二维数组/对象
     * @return array
     */
    function cleanArrayObj($arr)
    {
        if(is_object($arr)){
            $arr = get_object_vars($arr);
        }
        elseif (is_array($arr)){
            foreach ($arr as $k=>$v){
                if(is_object($v)){
                    $arr[$k] = get_object_vars($v);
                }
            }
        }

        return $arr;
    }
}

if (!function_exists('curl_do')) {
    function curl_do($url, $header = '', $data = '', $method = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($header)) curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        if ($method) curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
}




