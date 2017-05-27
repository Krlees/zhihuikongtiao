<?php

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Services\Admin;

use App\Models\Strategy;
use App\Traits\Admin\UserTraits;
use DB;

class StrategyService extends BaseService
{
    use UserTraits;

    protected $strategy;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function addData($data)
    {
        $data['user_id'] = $this->getCurrentUser();
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
            $rows[$k]['temp'] = $v['temp'] . '-' . $v['temp_end'];
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

    public function setStrategyLog($deviceIds, $baetTemp, $outTemp, $inTemp)
    {
        $scale = ($baetTemp * 0.5) + ($outTemp * 0.2) + ($inTemp * 0.3);

        try {
            foreach ($deviceIds as $deviceId) {
                $result = DB::table('strategy_log')->insert([
                    'device_id' => $deviceId,
                    'out_temp' => $outTemp,
                    'in_temp' => $inTemp,
                    'scale' => $scale,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            return $result ? true : false;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 检测策略
     * @Author Krlee
     *
     */
    public function get($inTemp)
    {
        $userId = $this->getCurrentUser();

        $nowHour = date('H:i:00');

        $result = DB::table($this->strategy->getTable())->where('user_id', $userId)->where('temp', '<=', $inTemp)->where('temp_end', '>=', $inTemp)->first(['temp', 'is_humidity']);
        if ($result)
            return obj2arr($result);


        $result = DB::table($this->strategy->getTable())->where('user_id', $userId)->where('start_time', '>=', $nowHour)->where('end_time', '<=', $nowHour)->first(['temp', 'is_humidity']);
        $result = obj2arr($result);

        return $result;
    }


}