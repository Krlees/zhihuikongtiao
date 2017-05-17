<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Presenters\Admin\UserPresenter;
use App\Services\Admin\RoomService;
use App\Services\Admin\UserService;
use App\Traits\Admin\FormTraits;
use Illuminate\Http\Request;

class UsersController extends BaseController
{
    use FormTraits;

    private $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            // 过滤参数
            $data = $this->cleanAjaxPageParam($request->all());
            $results = $this->user->ajaxUserList($data);
            foreach ($results['rows'] as $k => $v) {
                $results['rows'][$k]['is_super'] = ($v['is_super'] == 1) ? '是' : '否';
            }

            return $this->responseAjaxTable($results['total'], $results['rows']);
        } else {

            $action = $this->returnActionFormat(url('admin/user/add'), url('admin/user/edit'), url('admin/user/del'));
            $reponse = $this->returnSearchFormat(url('admin/user/index'), null, $action);

            return view('admin/user/index', compact('reponse'));
        }
    }

    public function add(Request $request, UserPresenter $presenter)
    {
        if ($request->ajax()) {

            $b = $this->user->createData($request->all());
            return $b ? $this->responseData(0, '', null, url('admin/menu/index')) : $this->responseData(400);

        } else {


            $district = $this->user->getDistrict(0); // 所有一级省份
            $userList = $this->user->getUserSelects(0); // 所有一级酒店
            $this->returnFieldFormat('select', '上级酒店', 'data[pid]',
                $this->returnSelectFormat($userList, 'name', 'id'), ['id' => 'top']
            );
            $this->returnFieldFormat('text', '酒店名称', 'data[name]', '', ['dataType' => 's1-48']);
            $this->returnFieldFormat('text', '登陆账号', 'data[email]', '', ['dataType' => '*']);
            $this->returnFieldFormat('text', '密码', 'data[password]', '', ['dataType' => 's4-18']);
            $this->returnFieldFormat('text', '联系电话', 'data[phone]', '', ['dataType' => 's8-15']);
            //省市区选择框
            $this->returnFieldFormat('select', '省市区', 'data[province_id]',
                $this->returnSelectFormat($district, 'name', 'id'), ['id' => 'province']
            );
            $this->returnFieldFormat('select', '', 'data[city_id]', [], ['id' => 'city']);
            $this->returnFieldFormat('select', '', 'data[area_id]', [], ['id' => 'area']);
            $this->returnFieldFormat('text', '详细地址', 'address', '', ['dataType' => 's1-70']);

            $roles = $this->user->getAllRoles();
            $extendField = $presenter->roleList($roles);

            $reponse = $this->returnFormFormat('新建酒店', $this->formField);
            $reponse['extendField'] = $extendField;

            return view('admin/user/add', compact('reponse'));
        }
    }

    public function edit($id, Request $request, UserPresenter $presenter)
    {
        if ($request->ajax()) {

            $param = $request->all();
            $this->checkRequireParams($param['data'], ['pid', 'password', 'role']);

            $b = $this->user->updateData($id, $param);
            return $b ? $this->responseData(0) : $this->responseData(400);

        } else {
            $info = $this->user->findById($id);
            $activeRoles = $this->user->getActiveRoles($id);

            // 生成表单
            $district = $this->user->getDistrict(0); // 所有一级省份
            $city = $this->user->getDistrictFirst($info->city_id);
            $area = $this->user->getDistrictFirst($info->area_id);
            $userList = $this->user->getUserSelects(0); // 所有一级酒店

            $this->returnFieldFormat('select', '上级酒店', 'data[pid]',
                $this->returnSelectFormat($userList, 'name', 'id', $info->pid), ['id' => 'top']
            );
            $this->returnFieldFormat('text', '酒店名称', 'data[name]', $info->name, ['dataType' => 's1-48']);
            $this->returnFieldFormat('text', '登陆账号', 'data[email]', $info->email, ['dataType' => 's4-32']);
            $this->returnFieldFormat('text', '密码', 'data[password]', '', ['placeholder' => '不修改密码请为空', 'dataType' => 's0-18']);
            $this->returnFieldFormat('text', '联系电话', 'data[phone]', $info->phone, ['dataType' => 's9-15']);
            //省市区选择框
            $this->returnFieldFormat('select', '省市区', 'data[province_id]',
                $this->returnSelectFormat($district, 'name', 'id', $info->province_id),
                ['id' => 'province']
            );
            if ($city) {
                $this->returnFieldFormat('select', '', 'data[city_id]',
                    $this->returnSelectFormat([$city], 'name', 'id', $info->city_id),
                    ['id' => 'city']
                );
            }
            if ($area) {
                $this->returnFieldFormat('select', '', 'data[area_id]',
                    $this->returnSelectFormat([$area], 'name', 'id', $info->area_id),
                    ['id' => 'area']
                );
            }

            $this->returnFieldFormat('text', '详细地址', 'address', $info->area_info, ['dataType' => 's1-70']);

            // 获取用户权限
            $roles = $this->user->getAllRoles();
            $extendField = $presenter->roleList($roles, $activeRoles);

            $reponse = $this->returnFormFormat('编辑酒店', $this->formField);
            $reponse['extendField'] = $extendField;

            return view('admin/user/edit', compact('reponse', 'info'));
        }
    }

    public function del(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(",", $ids);
        }

        $results = $this->user->delData($ids);
        return $results ? $this->responseData(0, "操作成功", $results) : $this->responseData(200, "操作失败");
    }

    /**
     * 获取子用户
     * @Author Krlee
     *
     */
    public function getSubSelect($pid)
    {
        return $this->user->getUserSelects($pid);
    }

    /**
     * 获取子用户
     * @Author Krlee
     *
     */
    public function getUserRoom($id,RoomService $roomService)
    {
        return $roomService->getUserRoom($id);
    }
}
