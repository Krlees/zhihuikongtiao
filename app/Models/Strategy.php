<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'strategy';

    protected $strategy_log = 'strategy_log'; // 空调每次调整操作记录表

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * @return string
     */
    public function getStrategyLog()
    {
        return $this->strategy_log;
    }

}
