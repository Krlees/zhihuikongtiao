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

if (!function_exists('xml2array')) {
    /**
     * 将xml转为array
     */
    function xml2array($xml)
    {
        // 将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
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
        if (is_object($arr)) {
            $arr = get_object_vars($arr);
        } elseif (is_array($arr)) {
            foreach ($arr as $k => $v) {
                if (is_object($v)) {
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

/**
 * 获取配置
 * @param <string> $classify 分类名
 * @param <string> $name 配置名
 * @return <string|array>
 */
function get_setting($classify = '', $key = '')
{

    $setting = array();
    $data = \DB::table('setting')->get()->toArray();
    $data = cleanArrayObj($data);
    foreach ($data as $item) {
        $setting[$item['classify']][$item['key']] = $item['value'];
    }

    return $classify ? ($key ? $setting[$classify][$key] : $setting[$classify]) : $setting;
}

/**
 * 保存配置
 * @param <string> $classify 分类名
 * @param <string> $name 配置键
 * @param <mixed> $value 配置值
 * @return <bool>
 */
function set_setting($classify, $key, $value)
{
    $mod_setting = \DB::table('setting');
    $count = \DB::table('setting')->where(array('key' => $key, 'classify' => $classify))->count();
    if ($count > 0) {
        try {
            $affected = $mod_setting->where(['key' => $key, 'classify' => $classify])->update(['value' => $value]);
            return $affected ? true : false;
        } catch (\Exception $e) {
            return false;
        }

    } else {
        try {
            $affected = $mod_setting->insert(['key' => $key, 'value' => $value, 'classify' => $classify]);
            return $affected ? true : false;
        } catch (\Exception $e) {
            return false;
        }
    }

}

if (!function_exists('obj2arr')) {
    /**
     * 将pdo查询的结果对象转为数组array
     * @Author Krlee
     *
     * @param $obj
     * @return array
     */
    function obj2arr($obj)
    {
        return json_decode(json_encode($obj), true);
    }
}

if (!function_exists('get_future_datetime')) {
    /**
     * desc
     * @Author Krlee
     *
     * @param $nowDate
     * @param $days       未来第几天
     * @param string $do 未来还是之前，+代表未来,-代表之前
     */
    function get_future_datetime($nowDate = null, $days = 1, $do = "+")
    {
        if ($do != "+" || $do != "-") {
            $do = "+";
        }

        if (is_null($nowDate)) {
            $nowDate = time();
        }

        return date("Y-m-d H:i:s", strtotime("{$do} {$days}days", strtotime($nowDate)));
    }
}


function decto_bin($datalist, $bin)
{
    static $arr = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'F');
    if (!is_array($datalist)) $datalist = array($datalist);
    if ($bin == 10) return $datalist; //相同进制忽略
    $bytelen = ceil(16 / $bin); //获得如果是$bin进制，一个字节的长度
    $aOutChar = array();
    foreach ($datalist as $num) {
        $t = "";
        $num = intval($num);
        if ($num === 0) continue;
        while ($num > 0) {
            $t = $arr[$num % $bin] . $t;
            $num = floor($num / $bin);
        }
        $tlen = strlen($t);
        if ($tlen % $bytelen != 0) {
            $pad_len = $bytelen - $tlen % $bytelen;
            $t = str_pad("", $pad_len, "0", STR_PAD_LEFT) . $t; //不足一个字节长度，自动前面补充0
        }
        $aOutChar[] = $t;
    }
    return $aOutChar;
}




