<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 首页
Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    return redirect('admin/index');
});
// 后台路由
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'auth.admin']], function () {


    Route::get('index', 'IndexController@index');
    Route::get('dashboard', 'IndexController@dashboard');

    // 权限管理
    Route::group(['prefix' => 'permission'], function () {
        Route::any('index', 'PermissionController@index');
        Route::any('add', 'PermissionController@add');
        Route::any('edit/{id}', 'PermissionController@edit');
        Route::any('del', 'PermissionController@del');
        Route::any('get-sub-perm/{id}', 'PermissionController@getSubPerm');
    });

    // 角色管理
    Route::group(['prefix' => 'role'], function () {
        Route::any('index', 'RoleController@index');
        Route::any('show/{id}', 'RoleController@show');
        Route::any('add', 'RoleController@add');
        Route::any('edit/{id}', 'RoleController@edit');
        Route::any('del', 'RoleController@del');
        Route::any('{id}', 'RoleController@getInfo');
    });

    // 管理员
    Route::group(['prefix' => 'user'], function () {
        Route::any('index/{level}', 'UsersController@index');
        Route::any('add/{level}', 'UsersController@add');
        Route::any('edit/{id}', 'UsersController@edit');
        Route::any('del', 'UsersController@del');
        Route::any('get-sub-user/{pid}', 'UsersController@getSubSelect');
        Route::any('get-user-room/{id}', 'UsersController@getUserRoom');
    });

    // 菜单管理
    Route::group(['prefix' => 'menu'], function () {
        Route::any('index', 'MenuController@index');
        Route::any('add', 'MenuController@add');
        Route::any('edit/{id}', 'MenuController@edit');
        Route::any('del', 'MenuController@del');
        Route::any('get-sub-menu/{id}', 'MenuController@getSubMenu');
    });

    // 产品
    Route::group(['prefix' => 'product'], function () {
        Route::any('index', 'ProductController@index');
        Route::any('add', 'ProductController@add');
        Route::any('edit/{id}', 'ProductController@edit');
        Route::any('del', 'ProductController@del');
        Route::any('get-sub-class/{id}', 'ProductController@getSubClass');
    });

    // 订单
    Route::group(['prefix' => 'order'], function () {
        Route::any('index', 'OrderController@index');
        Route::any('detail/{id}', 'OrderController@detail');
    });

    // 房间
    Route::group(['prefix' => 'room'], function () {
        Route::any('index', 'RoomController@index');
        Route::any('add', 'RoomController@add');
        Route::any('edit/{id}', 'RoomController@edit');
        Route::any('del', 'RoomController@del');
    });

    // 调控
    Route::group(['prefix' => 'device'], function () {
        Route::any('index', 'DeviceController@index');
        Route::any('add', 'DeviceController@add');
        Route::any('edit/{id}', 'DeviceController@edit');
        Route::any('del', 'DeviceController@del');
        Route::any('adjust/{id}', 'DeviceController@adjust'); // 设备调控
        Route::any('get-Gizwit-Config', 'DeviceController@getGizwitConfig');
        Route::any('chart', 'DeviceController@chart'); // 能耗统计
        Route::any('live', 'DeviceController@live');   // 冷热实况
        Route::any('get-gizwit-cmd/{id}', 'DeviceController@getGizwitCmd');   // 冷热实况
        Route::any('setting/{id}', 'DeviceController@setting');   // 冷热实况
        Route::any('save-cmd', 'DeviceController@saveFirstSyncCmd');   // 保存第一次获取的设备状态
        Route::any('save-state', 'DeviceController@saveState');   // 存储每次设备返回的状态
        Route::any('get-device-count/{deviceId?}', 'DeviceController@getDataCount');   // 存储每次设备返回的状态
        Route::any('save-device-count/{deviceId?}', 'DeviceController@setDataCount');   // 存储每次设备返回的状态
        Route::any('send-electric-cmd', 'DeviceController@sendElectricCmd');   // 存储每次设备返回的状态
        Route::any('get-weather', 'DeviceController@getWeather');   // 存储每次设备返回的状态
        Route::any('get-scene', 'DeviceController@getScene');   // 获取情景模式
        Route::any('get-user-token', 'DeviceController@getUserToken');   // 获取用户
    });

    // 电器
    Route::group(['prefix' => 'electric'], function () {
        Route::any('index', 'ElectricController@index');
        Route::any('add/{deviceId}', 'ElectricController@add');
        Route::any('edit/{id}', 'ElectricController@edit');
        Route::any('del', 'ElectricController@del');
        Route::any('get-device/{room_id}', 'ElectricController@getDevice');
        Route::any('get-brand/{ele_id}', 'ElectricController@getBrand');
    });

    // 策略
    Route::group(['prefix' => 'strategy'], function () {
        Route::any('index', 'StrategyController@index');
        Route::any('add', 'StrategyController@add');
        Route::any('del', 'StrategyController@del');
        Route::any('night', 'StrategyController@night');
        Route::any('use-chart', 'StrategyController@useChart');
        Route::any('set-strategy-log', 'StrategyController@setStrategyLog');
    });

    // 统计分析
    Route::group(['prefix' => 'chart'], function () {
        Route::any('energy',  'ChartController@energy');
        Route::any('report',  'ChartController@report');
        Route::any('history', 'ChartController@history');
    });

    // 设置
    Route::group(['prefix' => 'setting'], function () {
        Route::any('use-desc/{level}',  'SettingController@useDesc');
        Route::any('base',  'SettingController@base');
    });

    // 消息
    Route::group(['prefix' => 'message'], function () {
        Route::any('index/{isRead}',  'MessageController@index');
        Route::any('del',  'MessageController@del');
    });

});

// Auth退出
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


Auth::routes();
