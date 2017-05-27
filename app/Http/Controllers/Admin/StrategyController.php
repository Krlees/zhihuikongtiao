<?php

// +----------------------------------------------------------------------
// | 策略
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Presenters\Admin\StrategyPresenter;
use App\Services\Admin\QianhaiService;
use App\Services\Admin\StrategyService;
use Illuminate\Http\Request;

class StrategyController extends BaseController
{
    protected $strategy;

    public function __construct(StrategyService $strategy)
    {
        $this->strategy = $strategy;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            // 过滤参数
            $data = $this->cleanAjaxPageParam($request->all());
            $results = $this->strategy->ajaxList($data);

            return $this->responseAjaxTable($results['total'], $results['rows']);

        } else {
            $action = $this->returnActionFormat(url('admin/strategy/add'), null, url('admin/strategy/del'));
            $reponse = $this->returnSearchFormat(url('admin/strategy/index'), '', $action);

            return view('admin/strategy/index', compact('reponse'));
        }

    }

    public function add(Request $request, StrategyPresenter $strategyPresenter)
    {
        if ($request->ajax()) {

            $param = $request->input('data');
            $this->strategy->checkTemp($param['temp']) or $this->responseData(80001, "温度值必须在16-30度区间");

            $param['start_time'] = date('H:i:00', strtotime($param['start_time']));
            $param['end_time'] = date('H:i:00', strtotime($param['end_time']));

            $result = $this->strategy->addData($param);
            $result ?
                $this->responseData(0, '', $result, url('admin/strategy/index')) :
                $this->responseData(9000);

        } else {

            $this->returnFieldFormat('text', '策略名称', 'data[name]', '', ['dataType' => 's1-30']);
            $this->returnFieldFormat('radio', '是否开启除湿', 'data[is_humidity]', [
                [
                    'text' => '是',
                    'value' => 1,
                    'checked' => true
                ], [
                    'text' => '否',
                    'value' => 0,
                    'checked' => false
                ]
            ]);
            $this->returnFieldFormat('text', '设定温度值', 'data[temp]', '', ['dataType' => 'n']);
            $extendField = $strategyPresenter->times();

            $reponse = $this->returnFormFormat('添加策略', $this->formField);
            $reponse['extendField'] = $extendField;
            return view('admin/strategy/add', compact('reponse'));
        }
    }

    public function del(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(",", $ids);
        }

        $affected = $this->strategy->delData($ids);
        $affected ? $this->responseData(0) : $this->responseData(9000);
    }

    public function night(Request $request)
    {
        if ($request->isMethod('POST')) {

            $data = $request->input('data');
            $result = set_setting('strategy', 'night', json_encode($data));
            $request ?
                $this->responseData(0, '', $result) :
                $this->responseData(9000);

        } else {
            $data = get_setting('strategy', 'night');
            $data = json_decode($data, true);

            $this->returnFieldFormat('text', '时间点', 'data[times]', array_get($data, 'times'), ['data-mask' => '99:99', 'placeholder' => '12:00']);
            $this->returnFieldFormat('text', '调节的温度', 'data[temp]', array_get($data, 'temp'));
            $this->returnFieldFormat('radio', '是否开启除湿', 'data[is_humidity]', [
                [
                    'text' => '是',
                    'value' => 1,
                    'checked' => (array_get($data, 'is_humidity') == 1) ? true : false
                ], [
                    'text' => '否',
                    'value' => 0,
                    'checked' => (array_get($data, 'is_humidity') == 0) ? true : false
                ]
            ]);

            $reponse = $this->returnFormFormat('夜间模式', $this->formField);
            return view('admin/strategy/night', compact('reponse'));
        }
    }

    /**
     * 使用策略的记录
     * @Author Krlee
     *
     */
    public function useChart()
    {
        $list = $this->strategy->useChart();
        $chartData = array_column($list, 'scale');
        $countData = [];
        foreach ($list as $k => $v) {
            $countData[] = $k;
        }

        return view('admin/strategy/use_chart', compact('chartData', 'countData'));
    }

    /**
     * 记录策略介入
     * @Author Krlee
     *
     */
    public function setStrategyLog(Request $request, QianhaiService $qianhaiService)
    {
        $bestTemp = 26; //人体最适温度
        $outTemp = $request->input('out_temp'); //室外温度
        $inTemp = $request->input('in_temp'); // 室内温度

        $deviceId = $request->input('deviceId');
        if (strpos($deviceId, ",") !== false) {
            $deviceIds = explode(",", $deviceId);
        } else {
            $deviceIds[] = $deviceId;
        }

        // 匹配策略
        $result = $this->strategy->get($inTemp);
        if ($result) {
            $cmd = $qianhaiService->getAirCmd();
            $cmd[14] = $result['temp'];
            if ($result['is_humidity']) {
                $cmd[21] = 1;

            }
        }

        // 记录
        $this->strategy->setStrategyLog($deviceIds, $bestTemp, $outTemp, $inTemp);

        return $result ? $this->responseData(0, '', ['RAW_SMARTHOME' => $cmd]) : $this->responseData(80001);
    }


}