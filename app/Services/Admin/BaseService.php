<?php
namespace App\Services\Admin;

use App\Jobs\SendEmail;
use App\Traits\Admin\FormTraits;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Route;

class BaseService
{

    public function sendSystemErrorMail($mail, $e)
    {
        $exceptionData = [
            'method' => Route::current()->getActionName(),
            'info' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
        dispatch(new SendEmail($mail, $exceptionData));
    }

    /**
     * 子父递归数据
     *
     * @param $menus
     * @param int $pid
     * @return array|string
     */
    public function childArr($data, $parentValue = 0, $primaryKey = 'id', $parnentKey = 'pid')
    {
        $arr = [];
        if (empty($data)) {
            return [];
        }

        foreach ($data as $key => $v) {
            if ($v[$parnentKey] == $parentValue) {
                $arr[$key] = $v;
                $arr[$key]['child'] = self::childArr($data, $v[$primaryKey]);
            }
        }
        return $arr;
    }

    /**
     * 获取省市区
     * @Author Krlee
     *
     */
    public function getDistrict($upid = 0)
    {
        $list = Cache::store('file')->get('district_' . $upid);
        if (empty($list)) {
            $list = DB::table('district')->where('upid', $upid)->orderBy('id', 'desc')->get()->toArray();
            foreach ($list as $k => $v) {
                $v = get_object_vars($v);
                $list[$k] = $v;
            }
            Cache::store('file')->forever('district_' . $upid, $list);
        }

        return $list;
    }


    /**
     * 获取省市区的名称
     * @Author Krlee
     *
     * @param $id
     */
    public function getDistrictName($id)
    {
        $district = DB::table('district')->where('id', $id)->first(['name']);

        return $district->name;
    }

    /**
     * 获取省市区
     * @Author Krlee
     *
     */
    public function getDistrictFirst($id)
    {
        $list = DB::table('district')->where('id', $id)->first();
        if (empty($list)) {
            return [];
        }

        return get_object_vars($list);
    }

}