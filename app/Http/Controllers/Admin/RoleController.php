<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\User;
use App\Presenters\Admin\RolePresenter;
use App\Services\Admin\RoleService;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    private $role;

    public function __construct(RoleService $role)
    {
        $this->role = $role;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            // 过滤参数
            $data = $this->cleanAjaxPageParam($request->all());
            $results = $this->role->ajaxRoleList($data);

            return $this->responseAjaxTable($results['total'], $results['rows']);
        } else {

            $action = $this->returnAutoAction('admin/role');
            $reponse = $this->returnSearchFormat(url('admin/role/index'), null, $action);

            return view('admin/role/index', compact('reponse'));
        }
    }

    public function getInfo($id)
    {
        //$this->role->getInfo($id);
    }

    public function add(Request $request, RolePresenter $presenter)
    {
        if ($request->ajax()) {


            $b = $this->role->createData($request->all());
            return $b ? $this->responseData(0,'','',url('admin/role/index')) : $this->responseData(400);

        } else {

            $this->returnFieldFormat('text', '标识', 'data[name]');
            $this->returnFieldFormat('text', '角色名称', 'data[display_name]');
            $this->returnFieldFormat('textarea', '描述', 'data[description]');

            $reponse = $this->returnFormFormat('添加角色', $this->formField);

            $perms = $this->role->getGroupPermission(); // 获取所有权限数据
            $reponse['extendField'] = $presenter->permissionList($perms['admin']); //生成权限组视图

            return view('admin/role/add', compact('reponse', 'permissions'));
        }
    }

    public function edit($id, Request $request, RolePresenter $presenter)
    {
        if ($request->ajax()) {
            $b = $this->role->updateData($id, $request->all());
            return $b ? $this->responseData(0,'','',url('admin/role/index')) : $this->responseData(400);

        } else {
            // 角色信息
            $info = $this->role->findById($id);
            $activePerms = $this->role->findByPerms($id);
            $activePerms = array_column($activePerms, 'id'); //角色已有的权限
            $perms = $this->role->getGroupPermission(); // 获取所有权限数据

            // 输出视图字段
            $this->returnFieldFormat('text', '标识', 'data[name]', $info->name);
            $this->returnFieldFormat('text', '角色名称', 'data[display_name]', $info->display_name);
            $this->returnFieldFormat('textarea', '描述', 'data[description]', $info->description);

            $reponse = $this->returnFormFormat('编辑角色', $this->formField);
            $reponse['extendField'] = $presenter->permissionList($perms['admin'], $activePerms); //生成权限组视图

            return view('admin/role/add', compact('reponse'));
        }

    }

    public function del(Request $request)
    {

        $ids = $request->input('ids');
        if( !is_array($ids) ){
            $ids = explode(",",$ids);
        }

        $results = $this->role->delData($ids);
        return $results ? $this->responseData(0,"操作成功",$results) : $this->responseData(200,"操作失败");
    }

    public function show($id)
    {
        $role = $this->role->findById($id);

        return view('admin/role/show',compact('role'));
    }
}



