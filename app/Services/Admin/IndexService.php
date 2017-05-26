<?php
namespace App\Services\Admin;

use App\Repositories\MenuRepositoryEloquent;
use App\Repositories\PermissionRepositoryEloquent;

class IndexService extends BaseService
{
    private $menu;
    private $perm;

    public function __construct(MenuRepositoryEloquent $menu, PermissionRepositoryEloquent $perm)
    {
        $this->menu = $menu;
        $this->perm = $perm;
    }

    /**
     * 返回权限允许的菜单
     *
     * @param $user
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getPermMenu($user)
    {

        $menuData = $this->menu->getAllMenu();
        foreach ($menuData as $key => $val) {
            if (!$user->may($val->permission_name)) {
                unset($menuData[$key]);
            } else {
                foreach ($val->sub as $k => $v) {
                    if (!$user->may($v->permission_name)) {
                        unset($menuData[$key]->sub[$k]);
                    }
                }

            }
        }

        return $menuData;

    }

}