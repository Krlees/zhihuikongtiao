<?php

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Services\Admin\ChartService;
use App\Services\Admin\UserService;
use App\Traits\Admin\GizwitTraits;
use App\Traits\Admin\QianhaiMdl;
use Illuminate\Http\Request;

class ChartController extends BaseController
{
    use GizwitTraits;
    use QianhaiMdl;

    protected $chart;

    public function __construct(ChartService $chart)
    {
        $this->chart = $chart;
    }

    /**
     * 能耗统计
     * @Author Krlee
     *
     */
    public function energy(Request $request, UserService $userService)
    {

        if ($request->ajax()) {

            $param = $this->cleanAjaxPageParam($request->all());
            $results = $this->chart->getAjaxChartList(array_get($param, 'data'));

            return $this->responseAjaxTable($results['total'], $results['rows']);

        } else {
            $provinceData = $this->chart->getDistrict(0);
            foreach ($provinceData as $k => $v) {
                $provinceSelect[$v['id']] = $v['name'];
            }
            ksort($provinceSelect);

            $user2 = $userService->getLevelUser(1);
            $user3 = $userService->getLevelUser(2);
            foreach ($user2 as $v) {
                $user2_select[$v['id']] = $v['name'];
            }
            foreach ($user3 as $v) {
                $user3_select[$v['id']] = $v['name'];
            }
            $user2_select[0] = '-请选择-';
            $user3_select[0] = '-请选择-';
            ksort($user2_select);
            ksort($user3_select);

            $chartData[0] = $this->chart->getChartForHour(2, 'use_energy');
            $chartData[1] = $this->chart->getChartForHour(2, 'no_use_energy');

            $reponse = $this->returnSearchFormat(url('admin/device/chart'));

            // 根据不用角色展示不同模板
            return view('admin/chart/energy', compact('provinceSelect', 'reponse', 'user2_select', 'user3_select', 'chartData'));

        }
    }

    public function report()
    {

    }

    public function history()
    {
        $cfg = \Config::get('gizwits.cfg');
        $gizwitId = \Auth::user()->id;
        $gizUsers = $this->createGizwitUser($cfg['appid'],$gizwitId);
//        if( empty($gizUsers)){
//            return [];
//        }

        $result = $this->getHistoryData($cfg['appid'],$gizUsers['token'],'fqvDqFzD3vakpz8P3VUXVY');
        dd($result);
    }
}