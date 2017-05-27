<?php
namespace App\Presenters\Admin;

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

class StrategyPresenter
{

    public function times()
    {
        return <<<Eof
        <div class="form-group">
        <label class="col-sm-2 control-label" for="">调节的温度</label>
        <div class="col-sm-10">
            <input class="form-control" style="width:20%;float:left" name="data[temp_start]" type="text" value="">
            <b style="float:left;padding:8px 5px 0 5px">至</b>
            <input class="form-control" style="width:20%;float:left" name="data[temp_end]" type="text" value="">
        </div>
</div>
<div class="form-group">
        <label class="col-sm-2 control-label" for="">策略使用的时间范围</label>
        <div class="col-sm-10">
            <input data-mask="99:99" placeholder="12:00" class="form-control" style="width:20%;float:left" name="data[start_time]" type="text" value="">
            <b style="float:left;padding:8px 5px 0 5px">至</b>
            <input data-mask="99:99" placeholder="22:00" class="form-control" style="width:20%;float:left" name="data[end_time]" type="text" value="">
        </div>
</div>
Eof;

    }



}