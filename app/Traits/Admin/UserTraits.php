<?php

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Traits\Admin;


trait UserTraits
{
    public function getCurrentUser($param=null)
    {
        if (\Auth::user()->hasRole('admin') && $param) {
            $where = [];
            if (array_get($param, 'province_id')) {
                $where[] = ['province_id', '=', $param['province_id']];
            }
            if (array_get($param, 'city_id')) {
                $where[] = ['city_id', '=', $param['city_id']];
            }
            if (array_get($param, 'area_id')) {
                $where[] = ['area_id', '=', $param['area_id']];
            }
            if (array_get($param, 'username')) {
                $where[] = ['name', '=', $param['username']];
            }

            // 查询酒店
            $userId = \DB::table('users')->where($where)->first(['id']);
            $userId = $userId->id;

        } elseif (array_get($param, 'user_id')) {
            $userId = $param['user_id'];
        } else {
            $userId = \Auth::user()->id;
        }

        return $userId;
    }


}