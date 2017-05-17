<?php
namespace App\Services\Admin;

use App\Models\Device;
use App\Traits\Admin\GizwitTraits;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DeviceService extends BaseService
{
    use GizwitTraits;

    protected $device;

    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    /**
     * 获取某个设备的详细
     * @Author Krlee
     *
     * @param $id
     * @return mixed|static
     */
    public function get($id)
    {
        return DB::table($this->device->getTable())->find($id);
    }

    /**
     * 获取房间下的所有智能设备
     * @Author Krlee
     *
     * @param $room_id 房间id
     *
     */
    public function getAll($room_id)
    {
        $result = DB::table($this->device->getTable())->where('room_id', $room_id)->get()->toArray();

        return cleanArrayObj($result);
    }

    /**
     * ajax查询设备列表
     * @Author Krlee
     *
     * @param $param
     */
    public function ajaxList($param)
    {
        $where = [];
        if (isset($param['search'])) {
            $where = [
                ['name', 'like', "%{$param['search']}%", 'OR'],
                ['did', 'like', "%{$param['search']}%", 'OR'],
                ['mac', 'like', "%{$param['search']}%", 'OR'],
            ];
        }

        $deviceDb = DB::table($this->device->getTable());
        $sort = $param['sort'] ?: $this->device->getKeyName();
        $rows = $deviceDb->where($where)->offset($param['offset'])->limit($param['limit'])
            ->orderBy($sort, $param['order'])
            ->get()
            ->toArray();
        $rows = cleanArrayObj($rows);

        $total = $deviceDb->where($where)->count();

        return compact('rows', 'total');
    }

    /**
     * 根据小时段做区间数组
     * @Author Krlee
     *
     * @param int $hour
     * @param $field
     * @return array
     */
    public function getChartForHour($hour = 1, $field)
    {
        if (!$field)
            return [];

        $data = $this->getChartHour();

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
    public function getChartHour()
    {
        $userId = \Auth::user()->id;

        $deviceIds = DB::table('device')->where(['user_id' => $userId])->get(['device.id'])->toArray();
        $deviceIds = cleanArrayObj($deviceIds);
        $ids = array_column($deviceIds, 'id');
        if (empty($ids)) {
            return [];
        }

        $sql = DB::raw("select date,year(date) as year,month(date) as month,day(date) as day,hour(date) as hour,sum(use_energy) as use_energy,sum(no_use_energy) as no_use_energy,sum(all_time) as all_time
                        from device_air_use_log 
                        where date>='" . date('Y-01-01') . "' and date<'" . date('Y-m-d 23:59:59') . "' and device_id in (" . implode(",", $ids) . ")
                        group by year,month,day,hour
                    ");
        $chartdata = DB::select($sql);
        $chartdata = cleanArrayObj($chartdata);

        return $chartdata;
    }

    /**
     * 获取能耗统计
     * @Author Krlee
     *
     */
    public function getAjaxChartList($param)
    {
        if (\Auth::user()->hasRole('admin') && $param) {
            $where = [];
            if ($param['province_id']) {
                $where[] = ['province_id', '=', $param['province_id']];
            }
            if ($param['city_id']) {
                $where[] = ['city_id', '=', $param['city_id']];
            }
            if ($param['area_id']) {
                $where[] = ['area_id', '=', $param['area_id']];
            }
            if (isset($param['username'])) {
                $where[] = ['name', '=', $param['username']];
            }

            // 查询酒店
            $userId = DB::table('users')->where($where)->first(['id']);
            $userId = $userId->id;

        } elseif (array_get($param, 'user_id')) {
            $userId = $param['user_id'];
        } else {
            $userId = \Auth::user()->id;
        }

        // 初始化返回格式数据
        for ($i = 0; $i <= 4; $i++) {
            $result[$i] = ['use_energy' => 0, 'no_use_energy' => 0, 'all_time' => 0];
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

        $sql = DB::raw("select date,year(date) as year,month(date) as month,sum(use_energy) as use_energy,sum(no_use_energy) as no_use_energy,sum(all_time) as all_time
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
            $result[$k]['use_energy_count'] = $v['use_energy'] - $v['no_use_energy'];
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
        $result[$k]['no_use_energy'] += $v->no_use_energy;
        $result[$k]['all_time'] += $v->all_time;

        return $result[$k];
    }

    /**
     * 获取【冷热实况】检索的数据
     * @Author Krlee
     *
     * @param $search
     */
    public function getLiveSearch($search)
    {
        $where = [];
        if (isset($search['province_id'])) {
            $where[] = ['province_id', 'eq', $search['province_id']];
        }
        if (isset($search['city_id'])) {
            $where[] = ['city_id', 'eq', $search['city_id']];
        }
        if (isset($search['area_id'])) {
            $where[] = ['area_id', 'eq', $search['area_id']];
        }
        if (isset($search['username'])) {
            $where[] = ['name', 'like', "%{$search['search']}%"];
        }

        $rows = DB::table($this->device->getTable())->where($where)->first(['id']);
        $userId = $rows->id; // 要检索的用户id


    }

    /**
     * 定位当前的城市
     * @Author Krlee
     *
     */
    public function getNowCity()
    {
        $url = 'http://api.map.baidu.com/location/ip?ak=pByihVCvPliqWIRAOOhPLtue&coor=bd09ll';
        $result = curl_do($url);
        $res = json_decode($result, true);

        $city = $res['content']['address_detail']['city'];
        $city = str_replace("市", "", $city);

        $result = DB::table('weather')->where('name', $city)->first(['code']);

        return $result->code;
    }

    public function getNowWeather($cityCode)
    {
        $url = 'http://www.weather.com.cn/data/cityinfo/' . $cityCode . '.html';

        return curl_do($url);
    }

    /**
     * 添加设备
     * @Author Krlee
     *
     */
    public function addData($data)
    {
        $gizwitsCfg = Config::get('gizwits.cfg');

        $gizwit_id = array_get($data, 'user_id') ?: \Auth::user()->id;
        if (!$gizwit_id) {
            return false;
        }

        // 1. 获取token
        $result = $this->createGizwitUser($gizwitsCfg['appid'], $gizwit_id);
        if (isset($result['error_code'])) {
            return false;
        }

        $gizwitUser = $result;
        $minutes = ($result['expire_at'] - time()) / 60;

        // 2. 绑定设备,获取设备状态
        $result = $this->bingDevice($gizwitsCfg['appid'], $gizwitsCfg['productkey'], $gizwitsCfg['productsecret'], $gizwitUser['token'], $data['mac']);
        if (isset($result['error_code'])) {
            return false;
        }

        // 3. 设备信息入库
        try {
            $id = DB::table($this->device->getTable())->insertGetId([
                'did' => $result['did'],
                'mac' => $result['mac'],
                'user_id' => $gizwit_id ?: 0,
                'product_key' => $result['product_key'],
                'passcode' => $result['passcode'],
                'room_id' => array_get($data, 'room_id', 0),
                'name' => array_get($data, 'name', ''),
                'data' => json_encode($result),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $id;

        } catch (QueryException $e) {
            return false;
        }

        return false;
    }

    /**
     * 记录设备调整日志
     * @Author Krlee
     *
     * @param $data
     * @return bool
     */
    public function addAdjustLog($id, $data)
    {
        try {
            $data['cmd'] = json_encode($data['cmd']);
            $data['device_id'] = $id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $affected = DB::table('device_air_adjust_log')->insert($data);
            return $affected ? true : false;

        } catch (QueryException $e) {
            return false;
        }

        return true;
    }

    /**
     * 记录定时任务数据
     * @Author Krlee
     *
     * @param $data
     * @return bool
     */
    public function addCron($data)
    {
        try {
            $affected = DB::table('device_crontab')->insert($data);
            return $affected ? true : false;

        } catch (QueryException $e) {
            return false;
        }

        return true;
    }

    /**
     * 更新设备
     * @Author Krlee
     *
     * @param $data
     * @return bool
     */
    public function updateData($id, $data)
    {
        try {
            $affected = DB::table($this->device->getTable())->where(['id' => $id])->update($data);
            return $affected ? true : false;

        } catch (QueryException $e) {
            return false;
        }

        return true;
    }

    /**
     * 删除设备
     * @Author Krlee
     *
     * @param $ids
     * @return int
     */
    public function delData($ids)
    {
        return DB::table($this->device->getTable())->whereIn('id', $ids)->delete();
    }


}