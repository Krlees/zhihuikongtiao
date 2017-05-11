<?php
namespace App\Presenters\Admin;

class RolePresenter
{

    /**
     * 修改角色界面，角色权限列表
     *
     * @param $permissions           所有权限
     * @param array $rolePermissions 该角色已有的权限
     * @return string
     */
    public function permissionList($permissions, $rolePermissions = [])
    {

        $html = '';
        foreach ($permissions as $key => $val) {
            $html .= "<tr><td>" . $key . "</td><td>";
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $html .= <<<Eof
						<div class="col-md-4">
	                     	<div class="i-checks">
	                        	<label> <input class="role-input" type="checkbox" name="permission[]" {$this->check($v['id'], $rolePermissions)} value="{$v['id']}"> <i></i> {$v['name']} </label>
	                      	</div>
                      	</div>
Eof;
                }
            }
            $html .= '</td></tr>';
        }


        $results = <<<Eof
        <div class="form-group">
    <label class="col-sm-2 control-label">权限</label>
    <div class="col-sm-10">
        <div class="ibox float-e-margins">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="col-md-1 text-center">模块</th>
                    <th class="col-md-10 text-center">权限</th>
                </tr>
                </thead>
                <tbody>
                    {$html}
                </tbody>
            </table>
        </div>
    </div>
</div>
Eof;

        return $results;
    }

    public function check($checkid, $checkArr)
    {
        if (empty($checkArr))
            return '';

        return (in_array($checkid, $checkArr)) ? 'checked="checked"' : '';
    }

    /**
     * 查看用户角色权限时展示的table
     * @author 晚黎
     * @date   2016-11-03T10:58:56+0800
     * @param  [type]                   $rolePermissions [description]
     * @return [type]                                    [description]
     */
    public function showRolePermissions($rolePermissions)
    {
        $html = '';
        if (!$rolePermissions->isEmpty()) {
            // 将角色权限分组
            $arr = [];
            foreach ($rolePermissions as $v) {
                array_set($arr, $v->name, ['id' => $v->id, 'name' => $v->display_name]);
            }

            if ($arr['admin']) {
                foreach ($arr['admin'] as $key => $val) {
                    $html .= "<tr><td>" . $key . "</td><td>";
                    if (is_array($val)) {
                        foreach ($val as $k => $v) {
                            $html .= <<<Eof
							<div class="col-md-4">
	                        	<label> {$v['name']} </label>
	                      	</div>
Eof;
                        }
                    }
                    $html .= '</td></tr>';
                }
            }
        }

        return $html;
    }

    /**
     * 获取权限
     * @param $permissions
     */
//    public function getPermissionList($permissions)
//    {
//        $html = "";
//        foreach ($permissions as $key => $permission) {
//            $html .= "<tr><td>".$key."</td><td>";
//            if (is_array($permission)) {
//                foreach ($permission as $k => $v) {
//                    $html .= <<<Eof
//						<div class="col-md-4">
//	                     	<div class="i-checks">
//	                        	<label> <input type="checkbox" name="permission[]" {$this->checkPermisison($v['id'],$rolePermissions)} value="{$v['id']}"> <i></i> {$v['name']} </label>
//	                      	</div>
//                      	</div>
//Eof;
//                }
//            }
//            $html .= '</td></tr>';
//        }
//

//    }

}