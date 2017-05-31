<?php

// +----------------------------------------------------------------------
// | 设备管理
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Services\Admin\DeviceService;
use App\Services\Admin\QianhaiService;
use App\Services\Admin\RoomService;
use App\Services\Admin\UserService;
use App\Traits\Admin\GizwitTraits;
use App\Traits\Admin\QianhaiMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;


class DeviceController extends BaseController
{

    use QianhaiMdl;
    use GizwitTraits;

    protected $device;

    public function __construct(DeviceService $device)
    {
        parent::__construct();
        $this->device = $device;
    }

    //
    public function index($bool,Request $request)
    {
        if ($request->ajax()) {

            $param = $this->cleanAjaxPageParam($request->all());
            $param['bool'] = $bool;

            $results = $this->device->ajaxList($param);

            return $this->responseAjaxTable($results['total'], $results['rows']);

        } else {

            $action = $this->returnActionFormat(url('admin/device/add'), url('admin/device/edit'), url('admin/device/del'));
            $reponse = $this->returnSearchFormat(url('admin/device/index'), null, $action);

            return view('admin/device/index', compact('reponse'));
        }
    }

    public function adjust($id, QianhaiService $qianhaiService)
    {

        $info = $this->device->get($id);
        $gizwitsCfg = Config::get('gizwits.cfg');

        $gizwit_id = $info->user_id;
        $sync_cmd = implode(",", $qianhaiService->sync_cmd);

        return view('admin/device/adjust', compact('info', 'gizwitsCfg', 'gizwit_id', 'sync_cmd'));
    }

    /**
     * 批量设置
     * @Author Krlee
     *
     * @param $ids
     * @param Request $request
     * @param QianhaiService $qianhaiService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adjustAll($id, Request $request, QianhaiService $qianhaiService)
    {
        if (strpos($id, ",") !== false) {
            $ids = explode(",", $id);
        } else {
            $ids[] = $id;
        }

        $info = $this->device->getMany($ids);
        $gizwit_id = $info[0]->user_id;
        $sync_cmd = implode(",", $qianhaiService->sync_cmd);

        $gizwitsCfg = Config::get('gizwits.cfg');

        return view('admin/device/adjust_all', compact('info', 'gizwitsCfg', 'gizwit_id', 'sync_cmd'));
    }

    /**
     * 添加并绑定设备
     * @Author Krlee
     *
     */
    public function add(Request $request, UserService $userService, RoomService $roomService)
    {
        if ($request->ajax()) {

            $param = $request->all();
            $this->checkRequireParams($param['data']);

            $result = $this->device->addData($param['data']);
            $result ?
                $this->responseData(0, '', $result, url('admin/device/index')) :
                $this->responseData(9000, "设备添加失败或已存在");

        } else {
            // 判断是否是超级管理员
            if (Auth::user()->hasRole('admin')) {
                $user = $userService->getUserSelects(0);
                $this->returnFieldFormat('select', '酒店', 'data[user_id]',
                    $this->returnSelectFormat($user),
                    ['id' => 'top']
                );
                $this->returnFieldFormat('select', '', 'data[user_id]', [], ['id' => 'sub']);
            }

            $roomSelect = $roomService->getAll(Auth::user()->id);
            $this->returnFieldFormat('select', '房间', 'data[room_id]',
                $this->returnSelectFormat($roomSelect),
                ['id' => 'room']
            );
            $this->returnFieldFormat('text', '设备别名', 'data[name]', '', ['dataType' => 's1-20']);
            $this->returnFieldFormat('text', 'mac地址', 'data[mac]', '', ['dataType' => '*']);

            $reponse = $this->returnFormFormat('添加设备', $this->getFormField());

            return view('admin/device/add', compact('reponse'));
        }

    }

    /**
     * 更新设备
     * @Author Krlee
     *
     */
    public function edit($id, Request $request, RoomService $roomService)
    {
        if ($request->ajax()) {
            $param = $request->all();
            $this->checkRequireParams($param);

            $result = $this->device->updateData($id, $param['data']);
            $result ? $this->responseData(0, '', '', url('admin/device/index')) : $this->responseData(9000);
        } else {

            $info = $this->device->get($id);

            $roomSelect = $roomService->getAll(Auth::user()->id);
            $this->returnFieldFormat('select', '房间', 'data[room_id]',
                $this->returnSelectFormat($roomSelect, 'name', 'id', $info->room_id),
                ['id' => 'room']
            );
            $this->returnFieldFormat('text', '设备标识名', 'data[name]');

            $reponse = $this->returnFormFormat('编辑设备', $this->getFormField());

            return view('admin/device/edit', compact('reponse'));
        }

    }

    public function del(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(",", $ids);
        }

        $results = $this->device->delData($ids);
        return $results ? $this->responseData(0, "操作成功", $results) : $this->responseData(200, "操作失败");
    }

    /**
     * 冷热实况
     * @Author Krlee
     *
     */
    public function live(Request $request)
    {
        if ($request->ajax()) {

            $param = $request->all();
            $result = $this->device->ajaxList($param['data']);

            $this->responseData(0, '', $result);

        } else {
            $provinceData = $this->device->getDistrict(0);
            foreach ($provinceData as $k => $v) {
                $provinceSelect[$v['id']] = $v['name'];
            }
            ksort($provinceSelect);

            return view('admin/device/live', compact('provinceSelect'));
        }
    }

    public function setting()
    {
        return view('admin/device/setting');
    }

    public function saveFirstSyncCmd(Request $request)
    {
        $cmd = $request->input('cmd');
        $cmd = explode(",", $cmd);

        \Session::put('RAW_SMARTHOME', $cmd);
        \Session::save();

        $this->responseData(0, '', session('RAW_SMARTHOME'));
    }

    public function saveState(Request $request)
    {
        $cmd = $request->input('cmd');
    }

    /**
     * 获取发送命令(空调)
     * @Author Krlee
     *
     */
    public function getGizwitCmd($id, Request $request, QianhaiService $qianhaiService)
    {
        $data = $request->all();
        $airCmd[0] = (int)$data['temp'];
        $airCmd[1] = (int)$data['wind_rate'];
        $airCmd[2] = (int)$data['wind_direction'];
        $airCmd[3] = (int)$data['auto_wind_direction'];
        $airCmd[4] = ($data['power'] == "true") ? 1 : 0;
        $airCmd[5] = 1;
        $airCmd[6] = (int)$data['mode'];

        $cmd = $qianhaiService->getAirCmd($airCmd);

        $this->device->addAdjustLog($id, compact('cmd'));

        // 存储用户设置的定时时间
        if (array_get($data, 'times')) {
            $device = $this->device->get($id);

            foreach ($data['times'] as $v) {
                $cron[$v] = [
                    'cmd' => json_encode($airCmd),
                    'did' => $device->did,
                    'hour' => (int)$v,
                    'user_id' => $device->user_id,
                    'created_at' => date('Y-m-d')
                ];

                $this->device->addCron($cron);
            }

            Cache::store('file')->put('device_cron_' . date('Y-m-d'), $cron, 60 * 24);
        }

        return ['RAW_SMARTHOME' => $cmd];
    }

    public function getDataCount($deviceId, Request $request)
    {
        $brand = $request->input('brand_id');
        $list = $this->getDeviceCount(49152, $brand);
        $list = $list['root'];
        $count = count($list);

        return compact('list', 'count');
    }

    public function setDataCount($deviceId, Request $request, QianhaiService $qianhaiService)
    {
        $ele_count = $request->input('ele_count');
        $device_key = Config::get('gizwits.electrical.49152');

        $save_cmd = $qianhaiService->build_cmd(49152, 48, 1);
        $save_cmd[4] = 7;
        $save_cmd[5] = 1;//设备类型
        $save_cmd[14] = 1;//空调只保存一个就可以
        $k = 0;
        for ($i = 0; $i < count($device_key); $i++) {
            $key_val = $this->key_val(49152, $ele_count, $device_key[$i]['kv']);

            $key_val['root'] = $qianhaiService->ReloadAirKeyValue($key_val['root']);

            $save_cmd[14 + $k + 1] = 1;//自定义键值，空调都是1
            $save_cmd[14 + $k + 2] = count($key_val['root']);
            for ($j = 0; $j < count($key_val['root']); $j++) {
                $save_cmd[14 + $k + 3 + $j] = $key_val['root'][$j];
            }
            $k += count($key_val['root']) + 2;
            break;//空调只传一个
        }
        $save_cmd[2] += $k + 1;

        return ['RAW_SMARTHOME' => $save_cmd];
    }

    public function sendElectricCmd(Request $request, QianhaiService $qianhaiService)
    {
        $ele_count = $request->input('ele_count');
        $device_type = $qianhaiService->get_device_type(49152);
        $key_data = $this->key_val(49152, $ele_count, 49153);

        $cmd = $qianhaiService->build_cmd(49152, 32, 1, $key_data['root']);
        $cmd[4] = 7;//遥控数据传输
        $cmd[5] = 1;//类型
        $cmd[6] = 1;//自定键值, 1为开机
        $cmd = $qianhaiService->check_sum($cmd, $cmd[2]);
        foreach ($cmd as $k => $v) {
            $cmd[$k] = (int)$v;
        }


        return ['RAW_SMARTHOME' => $cmd];
    }

    public function getWeather()
    {
        $cityCode = $this->device->getNowCity();
        $result = $this->device->getNowWeather($cityCode);

        return $result;
    }

    public function getUserToken(Request $request)
    {
        $appId = Config::get('gizwits.cfg.appid');
        $gizwitId = $request->input('phone_id');
        $result = $this->createGizwitUser($appId, $gizwitId);

        return $result;
    }


}
