<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Route;

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

        $arr = explode("/", Route::getCurrentRoute()->uri());
        $str = array_get($arr, 0) . '.' . array_get($arr, 1) . '.' . array_get($arr, 2);
        if ($str == 'admin.index.') {
            $str = 'admin.index.index';
        } elseif ($str == 'admin.dashboard.') {
            $str = 'admin.index.dashboard';
        }

//        if (!Auth::user()->may($str)) {
//            if ($request->ajax() && ($request->getMethod() != 'GET')) {
//                return response()->json([
//                    'code' => 403,
//                    'msg' => '您没有权限执行此操作'
//                ]);
//            } else {
//                return response('权限不足.', 403);
//            }
//
//        }

        return $next($request);
    }
}
