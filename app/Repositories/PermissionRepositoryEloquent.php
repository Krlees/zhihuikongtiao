<?php
namespace App\Repositories;

use App\Models\Permission;
use App\Models\Role;
use Prettus\Repository\Eloquent\BaseRepository;

class PermissionRepositoryEloquent extends BaseRepository
{

    public function model()
    {
        // TODO: Implement model() method.
        return Permission::class;
    }

    /**
     * ajax获取权限数据
     *
     * @param $offset
     * @param $limit
     * @param bool $sort
     * @param $order
     * @param array $where
     * @return array
     */
    public function ajaxPermList($offset, $limit, $sort=false, $order, $where = [])
    {
        $sort = $sort ?: $this->model->getKeyName();

        $rows = $this->model->where($where)->orderBy($sort,$order)->offset($offset)->limit($limit)->get()->toArray();

        $total = $this->model->where($where)->count();

        return compact('rows', 'total');
    }

    public function getPermSelects($id)
    {
        return $this->model->where('pid',$id)->get();
    }

    /**
     * 返回用户的权限
     *
     * @param $user
     * @return Array
     */
    public function getPerm($user)
    {
        $perms = $user->perms()->get(['name'])->toArray();

        return $perms;
    }

    /**
     * 获取所有的权限并按照功能分组
     * array_set函数介绍
     *      根据key按相同的字符串在不断分解成数组,非常强大的函数
     *
     * @return array
     */
    public function getGroupPermission()
    {
        $permissions = $this->model->all();
        $array = [];
        foreach ($permissions as $v) {
            array_set($array, $v->name, ['id' => $v->id,'name' => $v->display_name]);
        }

       return $array;
    }

}

