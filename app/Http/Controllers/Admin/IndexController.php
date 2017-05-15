<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Admin\BaseController;
use App\Services\Admin\IndexService;
use Illuminate\Http\Request;
use Auth;

class IndexController extends BaseController
{
    public function index(IndexService $index)
    {

        $user = Auth::user();
        $menus = $index->getPermMenu($user);

        return view('admin/index', compact('menus'));
    }

    public function dashboard()
    {

        return view('admin/dashboard');
    }


}
