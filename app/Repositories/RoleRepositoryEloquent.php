<?php
namespace App\Repositories;

use App\Models\Permission;
use App\Models\Role;
use Prettus\Repository\Eloquent\BaseRepository;
use DB;

class RoleRepositoryEloquent extends BaseRepository
{

    public function model()
    {
        // TODO: Implement model() method.
        return Role::class;
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
    public function ajaxRoleList($offset, $limit, $sort=false, $order, $where = [])
    {
        $sort = $sort ?: $this->model->getKeyName();

        $rows = $this->model->where($where)->orderBy($sort,$order)->offset($offset)->limit($limit)->get()->toArray();

        $total = $this->model->where($where)->count();

        return compact('rows', 'total');
    }






}

