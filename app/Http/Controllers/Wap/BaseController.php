<?php

namespace App\Http\Controllers\Wap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
