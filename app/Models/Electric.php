<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Electric extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'electric';

    protected $primaryKey = 'id';

    public $timestamps = false;

}
