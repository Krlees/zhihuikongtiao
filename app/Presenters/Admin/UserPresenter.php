<?php
namespace App\Presenters\Admin;

class UserPresenter
{
	/**
	 * 创建修改用户界面，角色权限列表
	 * @author 晚黎
	 * @date   2016-11-03T09:36:36+0800
	 * @param  [type]                   $permissions     [所有权限]
	 * @param  [type]                   $rolePermissions [该角色已有的权限]
	 * @return [type]                                    [html]
	 */
	public function permissionList($permissions,$rolePermissions=[])
	{
		$html = '';
		if ($permissions) {
			foreach ($permissions as $key => $permission) {
				$html .= "<tr><td>".$key."</td><td>";
				if (is_array($permission)) {
					foreach ($permission as $k => $v) {
						$html .= <<<Eof
						<div class="col-md-4">
	                     	<div class="i-checks">
	                        	<label> <input type="checkbox" name="permission[]" {$this->checkPermisison($v['id'],$rolePermissions)} value="{$v['id']}"> <i></i> {$v['name']} </label>
	                      	</div>
                      	</div>
Eof;
					}
				}
				$html .= '</td></tr>';
			}
		}
		return $html;
	}

	/**
	 * 添加用户出现错误时，获取已经选中的选项
	 * @author 晚黎
	 * @date   2016-11-03T09:42:15+0800
	 * @param  [type]                   $permissionId   [权限ID]
	 * @param  [type]                   $rolePermissions[修改角色时所有权限ID数组]
	 * @return [type]                                   [string]
	 */
	private function checkPermisison($permissionId,$rolePermissions = [])
	{
		$permissions = old('permission');
		if ($permissions) {
			return in_array($permissionId,$permissions) ? 'checked="checked"':'';
		}
		if ($rolePermissions) {
			if ($permissions) {
				if (in_array($permissionId,$rolePermissions) && in_array($permissionId,$permissions)) {
					return 'checked="checked"';
				}
			}else{
				return in_array($permissionId,$rolePermissions) ? 'checked="checked"':'';
			}
			return '';
		}
		return '';
	}
	/**
	 * 角色列表
	 * @author 晚黎
	 * @date   2016-11-03T14:05:56+0800
	 * @param  [type]                   $roles [所有角色对象]
	 * @return [type]                          [HTML]
	 */
	public function roleList($roles,$userRoles=[])
	{

		$html = '<div class="form-group"><label class="col-sm-2 control-label" for="email">角色</label><div class="col-sm-10">';
		if (!$roles->isEmpty()) {
			foreach ($roles as $role) {
				$html .= '<div class="i-checks"><div class="col-md-3"><label> <input type="checkbox" name="role[]" '.$this->check($role->id,$userRoles).' value="'.$role->id.'"> '.$role->display_name.' [<a data-target="#myModal" data-toggle="modal" href="'.url('admin/role/show',[$role->id]).'">查看角色权限</a>]</label></div></div>';
			}
		}
        $html .= '</div></div>';
		return $html;
	}

    public function check($checkid,$checkArr)
    {
        if(empty($checkArr))
            return '';

        return (in_array($checkid,$checkArr)) ? 'checked="checked"' : '';
    }

	/**
	 * 查看用户信息时展示的table
	 * @author 晚黎
	 * @date   2016-11-03T16:49:04+0800
	 * @param  [type]                   $rolePermissions [description]
	 * @return [type]                                    [description]
	 */
	public function showUserPermissions($userPermissions)
	{
		$html = '';
		if (!$userPermissions->isEmpty()) {
			// 将角色权限分组
			$permissionArray = [];
			foreach ($userPermissions as $v) {
				array_set($permissionArray, $v->slug, ['id' => $v->id,'name' => $v->name]);
			}
			if ($permissionArray) {
				foreach ($permissionArray as $key => $permission) {
					$html .= "<tr><td>".$key."</td><td>";
					if (is_array($permission)) {
						foreach ($permission as $k => $v) {
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
	 * 查看用户信息时展示的角色
	 * @author 晚黎
	 * @date   2016-11-03T16:55:32+0800
	 * @param  [type]                   $userRoles [description]
	 * @return [type]                              [description]
	 */
	public function showUserRoles($userRoles)
	{
		$html = '';
		if (!$userRoles->isEmpty()) {
			foreach ($userRoles as $role) {
				$html .= '<label class="checkbox-inline">'.$role->name.' [<a data-target="#myModal" data-toggle="modal" href="'.url('admin/role',[$role->id]).'">'.trans('admin/user.role_info').'</a>]</label>';
			}
		}
		return $html;
	}
}