<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;

    use EntrustUserTrait {
        restore as private restoreEntrust;
        EntrustUserTrait::can as may;
    }

//    use SoftDeletes {
//        restore as private restoreSoftDelete;
//    }

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'province_id', 'city_id', 'area_id', 'area_info', 'phone', 'pid', 'level'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 获取关联到酒店用户的统计数据
     */
    public function userChart()
    {
        return $this->hasOne('App\Models\UserChart');
    }

//    protected $dates = ['deleted_at'];

//    public function restore()
//    {
//        $this->restoreEntrust();
//        $this->restoreSoftDelete();
//    }

    public function roles()
    {
        return $this->belongsToMany(Config ::get('entrust.role'), Config::get('entrust.role_user_table'), Config::get('entrust.user_foreign_key'), Config::get('entrust.role_foreign_key'));
    }
//
//    public function hasRole($name)
//    {
//
//    }
//
//    public function can($permission,$arguments = [])
//    {
//
//    }
//
//    public function ability($roles,$permissions,$options)
//    {
//
//    }
}