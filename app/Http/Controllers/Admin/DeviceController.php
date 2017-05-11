<?php

// +----------------------------------------------------------------------
// | 设备管理
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;

use App\Services\Admin\DeviceService;
use App\Services\Admin\QianhaiService;
use App\Services\Admin\RoomService;
use App\Services\Admin\UserService;
use App\Traits\Admin\QianhaiMdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;


class DeviceController extends BaseController
{

    use QianhaiMdl;

    protected $device;

    public function __construct(DeviceService $device)
    {
        parent::__construct();
        $this->device = $device;
    }

    //
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $param = $this->cleanAjaxPageParam($request->all());
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
        $gizwit_id = $this->users['gizwit_id'];
        $gizwit_id = '13172166171';
        $sync_cmd = implode(",", $qianhaiService->sync_cmd);

        return view('admin/Device/adjust', compact('info', 'gizwitsCfg', 'gizwit_id', 'sync_cmd'));
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

            $result = $this->device->addData(Auth::user(), $param['data']);
            $result ?
                $this->responseData(0, '', $result, url('admin/device/index')) :
                $this->responseData(9000, "设备添加失败或已存在");

        } else {
            // 判断是否是超级管理员
            if (Auth::user()->hasRole('admin')) {
                $user = $userService->getUserSelects(0);
                $this->returnFieldFormat('select', '酒店', '',
                    $this->returnSelectFormat($user),
                    ['id' => 'top']
                );
                $this->returnFieldFormat('select', '', '', [], ['id' => 'sub']);
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
     * 能耗统计
     * @Author Krlee
     *
     */
    public function chart(Request $request, UserService $userService)
    {

        if ($request->ajax()) {

            $param = $this->cleanAjaxPageParam($request->all());
            $results = $this->device->getAjaxChartList(array_get($param, 'data'));

            return $this->responseAjaxTable($results['total'], $results['rows']);

        } else {
            $provinceData = $this->device->getDistrict(0);
            foreach ($provinceData as $k => $v) {
                $provinceSelect[$v['id']] = $v['name'];
            }
            ksort($provinceSelect);

            $user2 = $userService->getLevelUser(1);
            $user3 = $userService->getLevelUser(2);
            foreach ($user2 as $v) {
                $user2_select[$v['id']] = $v['name'];
            }
            foreach ($user3 as $v) {
                $user3_select[$v['id']] = $v['name'];
            }
            $user2_select[0] = '-请选择-';
            $user3_select[0] = '-请选择-';
            ksort($user2_select);
            ksort($user3_select);

            $chartData[0] = $this->device->getChartForHour(2, 'use_energy');
            $chartData[1] = $this->device->getChartForHour(2, 'no_use_energy');

            $reponse = $this->returnSearchFormat(url('admin/device/chart'));

            // 根据不用角色展示不同模板
            return view('admin/device/chart', compact('provinceSelect', 'reponse', 'user2_select', 'user3_select', 'chartData'));

        }
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
        $airCmd[4] = $data['power'] ? 1 : 0;
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


}
