<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserChart extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'users_chart';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

}
