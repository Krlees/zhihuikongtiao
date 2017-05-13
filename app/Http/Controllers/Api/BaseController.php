<?php

namespace App\Http\Controllers\Api;

use App\Services\Admin\BaseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class BaseController extends Controller
{
    public $member;

    public function __construct()
    {

    }

    /**
     * 统一回调
     *
     * @param $code     状态码
     * @param $msg      提示文字
     * @param $data     数据
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public function responseApi($code = 0, $msg = '', $data = [])
    {

        if (!$msg) {
            $msg = custom_config($code);
        }

        echo json_encode(compact('code', 'msg', 'data'));
        exit;
    }

    /**
     * 获取省市区
     * @Author Krlee
     *
     */
    public function getDistrict($level = 1, $upid = 0, BaseService $base)
    {
        return $base->getDistrict($upid,$level);
    }
}
