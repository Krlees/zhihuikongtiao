<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class SettingController extends BaseController
{

    /**
     * 使用说明
     * @Author Krlee
     *
     * @param $level
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function useDesc($level, Request $request)
    {
        if ($request->ajax()) {

            $data = $request->input('data');
            $result = set_setting('use_desc', 'level' . $level, $data['use_desc']);
            $result ? $this->responseData(0) : $this->responseData(9000);

        } else {

            $use_desc = get_setting('use_desc', 'level' . $level);
            $this->returnFieldFormat('textarea', '使用说明', 'data[use_desc]', $use_desc);

            $reponse = $this->returnFormFormat('使用说明设置', $this->getFormField());

            return view('admin/setting/use_desc', compact('reponse'));
        }
    }

    public function base(Request $request)
    {

        if ($request->ajax()) {

            $data = $request->input('data');
            $result = set_setting('base', 'base', json_encode($data));
            $result ? $this->responseData(0) : $this->responseData(9000);

        } else {

            $base = get_setting('base', 'base');
            $base = json_decode($base, true);
            $this->returnFieldFormat('text', '每小时浩电量', 'data[comsume]', $base['comsume']);
            $this->returnFieldFormat('text', '电费', 'data[fee]', $base['fee']);

            $reponse = $this->returnFormFormat('使用说明设置', $this->getFormField());

            return view('admin/setting/use_desc', compact('reponse'));
        }

    }


}
