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
    public function add(Request $request, RoomService $roomService)
    {
        if ($request->ajax()) {

            $param = $request->all();
            $this->checkRequireParams($param['data'], ['ele_brand_id']);
            if (!isset($param['data']['ele_brand_id'])) {
                $this->responseData(1004);
            }

            $result = $this->elec->addData($param['data']);
            $result ?
                $this->responseData(0, '', $result, url('admin/elec/index')) :
                $this->responseData(9000, "设备添加失败或已存在");

        } else {

            $electricCfg = Config::get('gizwits.device_type');
            $roomSelect = $roomService->getAll(Auth::user()->id);

            // 表单字段
            $this->returnFieldFormat('text', '标示名（必填）', 'data[name]', '', ['dataType' => 's4-18']);
            $this->returnFieldFormat('select', '房间', '',
                $this->returnSelectFormat($roomSelect),
                ['id' => 'top']
            );
            $this->returnFieldFormat('select', '设备（必填）', 'data[device_id]', [], ['id' => 'sub']);
            $this->returnFieldFormat('select', '电器类型（必填）', 'data[ele_id]',
                $this->returnSelectFormat($electricCfg, 0, 1),
                ['id' => 'electric']
            );
            $this->returnFieldFormat('select', '电器品牌（必填）', 'data[ele_brand_id]',
                $this->returnSelectFormat($electricCfg, 0, 1),
                ['id' => 'brand']
            );

            $reponse = $this->returnFormFormat('绑定电器', $this->getFormField());

            return view('admin/electric/add', compact('reponse'));
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