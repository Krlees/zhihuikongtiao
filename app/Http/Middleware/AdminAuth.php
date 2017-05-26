<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Route;
use DB;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::user()->name == 'admin') {
            return $next($request);
        }

        $str = "";
        $urlPath = $request->path();
        $menu = DB::table('menu')->where('url', $urlPath)->first(['permission_name']);
        if (!empty($menu)) {
            $str = $menu->permission_name;
        }
        else {
            $arr = explode("/",$urlPath);
            $str = $arr[0].".".$arr[1].".".$arr[2];
        }

        if ($urlPath == 'admin/index') {
            $str = 'admin.index.index';
        } elseif ($urlPath == 'admin/dashboard') {
            $str = 'admin.index.dashboard';
        }

//        dd($str);


        if (!Auth::user()->may($str) && !$request->ajax()) {
//            if ($request->ajax() && ($request->getMethod() != 'GET')) {
//                return response()->json([
//                    'code' => 403,
//                    'msg' => '您没有权限执行此操作'
//                ]);
//            } else {
//                return response('权限不足.', 403);
//            }

            return response('权限不足.', 403);

        }

        return $next($request);
    }
}
