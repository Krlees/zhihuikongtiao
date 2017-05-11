<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'menu';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

}
