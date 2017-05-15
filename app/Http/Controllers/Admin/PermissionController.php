<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Services\Admin\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{
    private $perm;

    public function __construct(PermissionService $perm)
    {
        $this->perm = $perm;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            // 过滤参数
            $data = $this->cleanAjaxPageParam($request->all());
            $results = $this->perm->ajaxPermList($data);

            return $this->responseAjaxTable($results['total'], $results['rows']);
        } else {
            $action = $this->returnActionFormat(url('admin/permission/add'), url('admin/permission/edit'), url('admin/permission/del'));
            $reponse = $this->returnSearchFormat(url('admin/permission/index'), null, $action);

            return view('admin/permission/index', compact('reponse'));
        }
    }

    public function add(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->input('data');
            $results = $this->perm->createData($data);

            return $results ? $this->responseData(0,"操作成功",$results,url('admin/permission/index')) : $this->responseData(200,"操作失败");

        } else {
            $permSelects = $this->perm->getPermSelects();

            $this->returnFieldFormat('select', '权限', 'data[pid]', $this->returnSelectFormat($permSelects, 'display_name', 'id'), ['id' => 'topPerm']);
            $this->returnFieldFormat('select', '', 'data[pid]', [], ['id' => 'subPerm']);
            $this->returnFieldFormat('text', '显示名称', 'data[display_name]');
            $this->returnFieldFormat('text', '路由名', 'data[name]');
            $this->returnFieldFormat('textarea', '描述', 'data[description]');
            $reponse = $this->returnFormFormat('添加权限', $this->getFormField());

            return view('admin/permission/add', compact('reponse'));

        }
    }

    public function edit(Request $request, $id)
    {

        if ($request->ajax()) {
            $data = $request->input('data');
            $results = $this->perm->updateData($id,$data);

            return $results ? $this->responseData(0,"操作成功",$results,url('admin/permission/index')) : $this->responseData(200,"操作失败");

        } else {

            $permSelects = $this->perm->getPermSelects();

            $info = $this->perm->findById($id);

            $this->returnFieldFormat('select', '权限', 'data[pid]', $this->returnSelectFormat($permSelects, 'display_name', 'id',$info->pid), ['id' => 'topPerm']);
            $this->returnFieldFormat('select', '', 'data[pid]', [], ['id' => 'subPerm']);
            $this->returnFieldFormat('text', '显示名称', 'data[display_name]',$info->display_name);
            $this->returnFieldFormat('text', '路由名', 'data[name]',$info->name);
            $this->returnFieldFormat('textarea', '描述', 'data[description]',$info->description);

            $reponse = $this->returnFormFormat('编辑权限', $this->formField);
            return view('admin/permission/add', compact('reponse'));

        }

    }

    public function del(Request $request)
    {
        $ids = $request->input('ids');
        if( !is_array($ids) ){
            $ids = explode(",",$ids);
        }

        $results = $this->perm->delData($ids);
        return $results ? $this->responseData(0,"操作成功",$results) : $this->responseData(200,"操作失败");
    }

    /**
     * 获取子权限
     *
     * @param $id
     */
    public function getSubPerm($id)
    {
        $data = $this->perm->getPermSelects($id);

        return $data ? $this->responseData(0,"操作成功",$data) : $this->responseData(200,"操作失败");
    }

}
