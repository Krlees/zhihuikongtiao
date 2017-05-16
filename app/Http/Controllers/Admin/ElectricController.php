<?php

// +----------------------------------------------------------------------
// | 电器
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Services\Admin\DeviceService;
use App\Services\Admin\ElectricService;
use App\Services\Admin\RoomService;
use App\Traits\Admin\FormTraits;
use Auth;
use Config;
use Illuminate\Http\Request;

class ElectricController extends BaseController
{
    use FormTraits;

    private $elec;

    public function __construct(ElectricService $elec)
    {
        $this->elec = $elec;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            // 过滤参数
            $data = $this->cleanAjaxPageParam($request->all());
            $results = $this->elec->ajaxList($data);

            return $this->responseAjaxTable($results['total'], $results['rows']);

        } else {
            $action = $this->returnActionFormat(null, null, url('admin/electric/del'));
            $reponse = $this->returnSearchFormat(url('admin/electric/index'), '', $action);

            return view('admin/electric/index', compact('reponse'));
        }
    }

    /**
     * 添加电器并绑定到智能设备
     * @Author Krlee
     *
     */
    public function add($deviceId, Request $request, RoomService $roomService, DeviceService $deviceService)
    {
        if ($request->ajax()) {

            $param = $request->all();

            $param['data']['ele_id'] = 49152;
            $param['data']['device_id'] = $deviceId;

            $result = $this->elec->addData($param['data']);
            $result ?
                $this->responseData(0, '', $result, url('admin/device/index')) :
                $this->responseData(9000, "设备添加失败或已存在");

        } else {

            $brand = $this->elec->getBrand(49152);
            $info = $deviceService->get($deviceId);
            $gizwit_id = $info->user_id;
            $gizwitsCfg = Config::get('gizwits.cfg');

            return view('admin/electric/add', compact('deviceId', 'brand', 'gizwit_id', 'info', 'gizwitsCfg'));
        }

    }

    public function del(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(",", $ids);
        }

        $results = $this->elec->delData($ids);
        return $results ? $this->responseData(0, "操作成功", $results) : $this->responseData(200, "操作失败");
    }

    /**
     * 获取设备
     * @Author Krlee
     *
     */
    public function getDevice($room_id, DeviceService $deviceService)
    {
        return $deviceService->getAll($room_id);
    }

    /**
     * 获取品牌
     * @Author Krlee
     *
     */
    public function getBrand($ele_id)
    {
        $result = [];
        $arr = $this->elec->getBrand($ele_id);
        foreach ($arr as $k => $v) {
            $result[$k]['id'] = $k;
            $result[$k]['name'] = $v;
        }

        return response()->json($result);
    }


}