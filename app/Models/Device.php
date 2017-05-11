<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'device';
    protected $air_adjust_log_tb = 'device_air_adjust_log'; // 空调每次调整操作记录表
    protected $air_use_log_tb = 'device_air_use_log'; // 空调每次调整操作记录表

    protected $primaryKey = 'id';

    protected $fillable = [
        //'name'
    ];

    public $timestamps = false;

    /**
     * 空调调整记录表
     * @return string
     */
    public function getAirAdjustLogTb()
    {
        return $this->air_adjust_log_tb;
    }

    /**
     * 空调使用日志表（每天计算）
     * @return string
     */
    public function getAirUseLogTb()
    {
        return $this->air_use_log_tb;
    }

}
