<?php

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Services\Admin;

use App\Models\Strategy;
use DB;

class StrategyService extends BaseService
{
    protected $strategy;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function addData($data)
    {
        return DB::table($this->strategy->getTable())->insert($data);
    }

    public function delData($ids)
    {
        try {
            DB::table($this->strategy->getTable())->whereIn('id', $ids)->delete();

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function ajaxList($param)
    {
        $strategyDb = DB::table($this->strategy->getTable());
        $sort = $param['sort'] ?: $this->strategy->getKeyName();
        $rows = $strategyDb->offset($param['offset'])->limit($param['limit'])
            ->orderBy($sort, $param['order'])
            ->get()
            ->toArray();
        $rows = obj2arr($rows);
        $total = $strategyDb->count();
        foreach ($rows as $k => $v) {
            $rows[$k]['times'] = $v['start_time'] . '-' . $v['end_time'];
        }

        return compact('rows', 'total');
    }

    /**
     * 检查温度区间
     * @Author Krlee
     *
     */
    public function checkTemp($temp)
    {
        return ($temp >= 16 && $temp <= 30) ? true : false;
    }

    /**
     * 按照每天记录
     * @Author Krlee
     *
     */
    public function useChart()
    {
        $nowDate = date("Y-m-d 00:00:00");
        $tomorrow = get_future_datetime($nowDate);
        $list = DB::table($this->strategy->getStrategyLog())->whereBetween('created_at', [$nowDate, $tomorrow])->get()->toArray();
        $list = obj2arr($list);

        return $list;
    }


}