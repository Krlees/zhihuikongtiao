<?php

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Services\Admin;

use App\Services\Admin\BaseService;
use App\Traits\Admin\UserTraits;
use DB;

class ChartService extends BaseService
{
    use UserTraits;

    public function getAjaxReport($param)
    {
        $userId = $this->getCurrentUser($param);
        $where = "";
        if (isset($param['start'])) {
            $where = "and date > '{$param['start']}' ";
        }
        if (isset($param['end'])) {
            $where = "and date < '{$param['end']}' ";
        }

        $result = DB::select("select a.*,b.name as device_name,(select name from users u where u.id=b.user_id) as username from device_air_use_log a JOIN device b on a.device_id=b.id where b.user_id= :user_id {$where} limit {$param['offset']},{$param['limit']}", ['user_id' => $userId]);
        $result = obj2arr($result);

        $baseCfg = get_setting('base', 'base');
        $baseCfg = json_decode($baseCfg, true);
        foreach ($result as $k => $v) {
            $result[$k]['comsume'] = $baseCfg['comsume'] * $v['all_time'];
            $result[$k]['fee'] = $baseCfg['comsume'] * $baseCfg['fee'] * $v['all_time'];
        }

        return ['total' => count($result), 'rows' => $result];
    }

    /**
     * 获取能耗统计
     * @Author Krlee
     *
     */
    public function getAjaxChartList($param)
    {
        $userId = $this->getCurrentUser($param);

        // 初始化返回格式数据
        for ($i = 0; $i <= 4; $i++) {
            $result[$i] = ['use_energy' => 0, 'all_time' => 0];
        }
        $result[0]['times'] = '总时';
        $result[1]['times'] = '一年';
        $result[2]['times'] = '三个月';
        $result[3]['times'] = '一个月';
        $result[4]['times'] = '每天';

        $nowDate = date('Y-m-d');
        $old_Date1 = date('Y-m-d', strtotime("-1 month"));
        $old_Date3 = date('Y-m-d', strtotime("-3 month"));
        $old_year = date('Y-m-d', strtotime("-1 year"));

        //if (!$result) {
        $deviceIds = DB::table('device')->where('user_id', $userId)
            ->get(['device.id'])
            ->toArray();
        $deviceIds = cleanArrayObj($deviceIds);
        $ids = array_column($deviceIds, 'id');
        if (empty($ids)) {
            return ['rows' => [], 'total' => 0];
        }

        $sql = DB::raw("select date,year(date) as year,month(date) as month,sum(use_energy) as use_energy,sum(all_time) as all_time
                        from device_air_use_log 
                        where date>='" . date('Y-01-01') . "' and device_id in (" . implode(",", $ids) . ")
                        group by year,month
                    ");
        $chartdata = DB::select($sql);
        foreach ($chartdata as $v) {
            // 当天统计
            if ($v->date >= $nowDate && $v->date < date('Y-m-d 23:59:59')) {
                $result[4] = $this->_helpChart($result, $v, 4);
            }

            // 一个月累计统计
            if (($v->date >= $old_Date1) && ($v->date <= $nowDate)) {
                $result[3] = $this->_helpChart($result, $v, 3);
            }

            // 三个月累计统计
            if ($v->date >= $old_Date3 && $v->date <= $nowDate) {
                $result[2] = $this->_helpChart($result, $v, 2);
            }

            // 一年累计统计
            if ($v->date >= $old_Date3 && $v->date <= $nowDate) {
                $result[1] = $this->_helpChart($result, $v, 2);
            }

            // 全部累计统计
            $result[0] = $this->_helpChart($result, $v, 2);
        }

        // 返回统计所需的格式
        foreach ($result as $k => $v) {
            $whereDate = date('Y-m-d', strtotime("-{$k} month"));
            $result[$k]['all_device_count'] = DB::table('device_air_use_log')->whereIn('device_id', $ids)
                ->where('date', '>=', $whereDate)
                ->where('date', '<=', $nowDate)
                ->distinct()
                ->count("device_id");

            $result[$k]['adjust_count'] = DB::table('device_air_adjust_log')->whereIn('device_id', $ids)
                ->where('created_at', '>=', $whereDate)
                ->where('created_at', '<=', $nowDate)
                ->count();
            $result[$k]['use_energy_scale'] = $v['use_energy'] ? (floor($v['use_energy'] / $v['all_time'] * 10000) / 10000 * 100) . '%' : 0;
        }



        return ['rows' => $result, 'total' => 1];
    }

    /**
     * 辅助能耗统计
     * @Author Krlee
     *
     */
    private function _helpChart($result, $v, $k)
    {
        $result[$k]['use_energy'] += $v->use_energy;
        $result[$k]['all_time'] += $v->all_time;

        return $result[$k];
    }

    /**
     * 根据小时段做区间数组
     * @Author Krlee
     *
     * @param int $hour
     * @param $field
     * @return array
     */
    public function getChartForHour($param, $hour = 1, $field)
    {
        if (!$field)
            return [];

        $userId = $this->getCurrentUser($param);
        $data = $this->getChartHour($userId);
        if (empty($data)) {
            return [];
        }

        $result = [];
        $total = 0;
        for ($i = 1; $i <= 24; $i++) {
            if ($i % $hour == 0) {
                $total += $data[$i - 1][$field] ?: 0;
                $result[] += $total;
                $total = 0;
            } else {
                $total += $data[$i - 1][$field] ?: 0;
            }
        }

        return $result;
    }

    /**
     * 按小时统计能耗数据
     * @Author Krlee
     *
     */
    public function getChartHour($userId)
    {
        $deviceIds = DB::table('device')->where(['user_id' => $userId])->get(['device.id'])->toArray();
        $deviceIds = cleanArrayObj($deviceIds);
        $ids = array_column($deviceIds, 'id');
        if (empty($ids)) {
            return [];
        }

        $sql = DB::raw("select date,year(date) as year,month(date) as month,day(date) as day,hour(date) as hour,sum(use_energy) as use_energy,sum(all_time) as all_time
                        from device_air_use_log 
                        where date>='" . date('Y-01-01') . "' and date<'" . date('Y-m-d 23:59:59') . "' and device_id in (" . implode(",", $ids) . ")
                        group by year,month,day,hour
                    ");
        $chartdata = DB::select($sql);
        $chartdata = cleanArrayObj($chartdata);

        return $chartdata;
    }


}