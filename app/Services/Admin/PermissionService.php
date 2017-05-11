<?php
namespace App\Services\Admin;
use App\Models\Permission;
use App\Repositories\PermissionRepositoryEloquent;
use App\Services\Admin\BaseService;

class PermissionService extends BaseService
{
	private $permission;

	public function __construct(PermissionRepositoryEloquent $permission)
	{
		$this->permission = $permission;
	}

    /**
     * AJAX 获取权限数据
     *
     * @param $param
     * @return array
     */
    public function ajaxPermList($param)
    {
        $where = [['pid',0]];
        if( isset($param['search'])){
            $where = [
                ['pid',0],
                ['name','like',"%{$param['search']}%",'and'],
                ['display_name','like',"%{$param['search']}%",'or']
            ];
        }

        $results =  $this->permission->ajaxPermList($param['offset'],$param['limit'],$param['sort'],$param['order'], $where);

//        // 整理权限子父关系
//        foreach ($results['rows'] as $k=>$v){
//            if( $v['child'] ){
//                $count = count($v['child']);
//                array_splice($results['rows'],$k+$count,0,$v['child']);
//            }
//        }

        return $results;
    }

    /**
     * 获取权限 <select>
     */
    public function getPermSelects($id=0)
    {
        return $this->permission->getPermSelects($id)->toArray();
    }

    /**
     * 根据菜单ID查找数据
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function findById($id)
    {
        $permModel = $this->permission->model();
        $data = $permModel::find($id);

        return $data ?: abort(404); // TODO替换正查找不到数据错误页面
    }

    /**
     * 创建数据
     */
    public function createData($data)
    {
        $permModel = $this->permission->model();
        $b = $permModel::create($data);
        return $b ?: false;
    }

    /**
     * 更新数据
     *
     * @param $data
     * @return bool
     */
    public function updateData($id, $data)
    {
        $permModel = $this->permission->model();
        $b = $permModel::where('id',$id)->update($data);

        return $b ?: false;
    }

    public function delData($ids)
    {
        if( empty($ids) ){
            return false;
        }



        $permModel = $this->permission->model();

        $permModel::whereIn('pid',$ids)->delete();
        $results = $permModel::whereIn('id',$ids)->delete();

        return $results ? true : false;

    }

    /**
     * 递归数据
     *
     * @param $menus
     * @param int $pid
     * @return array|string
     */
    private function sortArr($menus,$pid=0)
    {
        $arr = [];
        if (empty($menus)) {
            return '';
        }

        foreach ($menus as $key => $v) {
            if ($v['pid'] == $pid) {
                $arr[$key] = $v;
                $arr[$key]['child'] = self::sortArr($menus,$v['id']);
            }
        }
        return $arr;
    }




}