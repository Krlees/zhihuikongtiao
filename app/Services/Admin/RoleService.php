<?php
namespace App\Services\Admin;

use App\Models\Role;
use App\Repositories\PermissionRepositoryEloquent;
use App\Repositories\RoleRepositoryEloquent;
use App\Services\Admin\BaseService;
use Zizaco\Entrust\EntrustRole;

class RoleService extends BaseService
{
    private $role;
    private $perm;

    public function __construct(RoleRepositoryEloquent $role, PermissionRepositoryEloquent $perm)
    {
        $this->role = $role;
        $this->perm = $perm;
    }

    /**
     * AJAX 获取权限数据
     *
     * @param $param
     * @return array
     */
    public function ajaxRoleList($param)
    {
        $where = [];
        if (isset($param['search'])) {
            $where = [
                ['name', 'like', "%{$param['search']}%", 'and'],
                ['display_name', 'like', "%{$param['search']}%", 'or']
            ];
        }

        $results = $this->role->ajaxRoleList($param['offset'], $param['limit'], $param['sort'], $param['order'], $where);

        return $results;
    }

    /**
     * 返回权限数据
     *
     * @return array|string
     */
    public function getGroupPermission($roleId = 0)
    {
        $results = $this->perm->getGroupPermission();

        return $results;
    }

    /**
     * 查询角色的权限
     * @param $id
     * @return mixed
     */
    public function findByPerms($role_id)
    {
        return $this->role->find($role_id)->perms()->get()->toArray();
    }


    /**
     * 根据菜单ID查找数据
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function findById($id)
    {
        $data = $this->role->with('perms')->find($id);

        return $data ?: abort(404); // TODO替换正查找不到数据错误页面
    }

    /**
     * 创建数据
     */
    public function createData($param)
    {
        $data = $param['data'];
        $permArr = isset($param['permission']) ? $param['permission'] : [];

        // 创建角色并更新角色权限表
        $roles = $this->role->create($data)->perms()->sync($permArr);

        return $roles ?: false;
    }

    /**
     * 更新数据
     *
     * @param $data
     * @return bool
     */
    public function updateData($id, $param)
    {
        $data = $param['data'];
        $permArr = isset($param['permission']) ? $param['permission'] : [];

        // 更新角色并更新角色权限表
        $roles = $this->role->update($data,$id)->perms()->sync($permArr);

        return $roles ?: false;
    }

    /**
     * 删除角色
     *
     * @param $ids
     */
    public function delData($ids)
    {
        $roleModel = $this->role->model();
        $results = $roleModel::whereIn('id',$ids)->delete();

        return $results;
    }

    /**
     * 所有权限转化为子父权限,并附上checked标识 <递归处理>
     *
     * @param $arr
     * @param int $pid
     * @param array    已有的权限
     */
    private function roleChildArr($arr, $pid = 0, $activeRoles = null)
    {
        $results = [];
        if (empty($arr)) {
            return [];
        }

        foreach ($arr as $key => $v) {
            if ($activeRoles && in_array($v['id'], $activeRoles)) {
                $v['checked'] = true;
            }
            else {
                $v['checked'] = false;
            }

            if ($v['pid'] == $pid) {
                $results[$key] = $v;
                $results[$key]['child'] = self::roleChildArr($arr, $v['id'], $activeRoles);
            }
        }

        return $results;
    }


}