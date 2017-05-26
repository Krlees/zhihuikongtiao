<?php
namespace App\Services\Admin;

use App\Models\Device;
use App\Traits\Admin\GizwitTraits;
use App\Traits\Admin\UserTraits;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DeviceService extends BaseService
{
    use GizwitTraits;
    use UserTraits;

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

    public function getMany($ids)
    {
        $result = DB::table($this->device->getTable())->whereIn('id', $ids)->get()->toArray();

        return $result;
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

        $userId = $this->getCurrentUser();

        $where = [];
        if (isset($param['search'])) {
            $where = [
                ['name', 'like', "%{$param['search']}%", 'OR'],
                ['did', 'like', "%{$param['search']}%", 'OR'],
                ['mac', 'like', "%{$param['search']}%", 'OR'],
            ];
        }
        $where[] = ['user_id', '=', $userId, 'AND'];

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
        $result = false;

        /* 解除绑定 */
        $gizwitsCfg = Config::get('gizwits.cfg');

        $rows = DB::table($this->device->getTable())->whereIn('id', $ids)->get();
        foreach ($rows as $v) {
            // 1. 获取token
            $result = $this->createGizwitUser($gizwitsCfg['appid'], $v->user_id);
            if (isset($result['error_code'])) {
                return false;
            }

            $result = $this->unbingDevice($gizwitsCfg['appid'], $result['token'], ['did' => $v->did]);
            if (!empty($result['success'])) {
                $result = DB::table($this->device->getTable())->where('id', $v->id)->delete();
            }

        }

        return $result ? true : false;

    }


}