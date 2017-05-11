<?php
namespace App\Services\Admin;

use App\Repositories\MenuRepositoryEloquent;
use App\Services\Admin\BaseService;
use Exception, DB;

class MenuService extends BaseService
{
    private $menu;

    public function __construct(MenuRepositoryEloquent $menu)
    {
        $this->menu = $menu;
    }

    public function getTopMenu()
    {
        return $this->menu->getTopMenu();
    }

    public function getAllMenu()
    {
        return $this->menu->getAllMenu();
    }

    public function ajaxMenuList($param)
    {
        $where = [['pid', 0]];
        if (isset($param['search'])) {
            $where = [
                ['pid', 0],
                ['name', 'like', "%{$param['search']}%", 'and'],
            ];
        }

        return $this->menu->ajaxMenuList($param['offset'], $param['limit'], $param['sort'], $param['order'], $where);
    }

    /**
     * 根据菜单ID查找数据
     * @author 晚黎
     * @date   2016-11-04T16:25:59+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function findMenuById($id)
    {
        $menu = $this->menu->find($id);
        if ($menu){
            return $menu;
        }
        // TODO替换正查找不到数据错误页面
        abort(404);
    }

    /**
     * 获取菜单 <select>
     */
    public function getMenuSelects($id=0)
    {
        return $this->menu->getMenuSelects($id)->toArray();
    }

    /**
     * 创建数据
     */
    public function createData($data)
    {
        $b = $this->menu->create($data);

        return $b ?: false;
    }

    public function updateData($data, $id){

        $b = $this->menu->update($data,$id);

        return $b ?: false;
    }

    public function delData($ids)
    {
        return $this->menu->delData($ids);
    }


}