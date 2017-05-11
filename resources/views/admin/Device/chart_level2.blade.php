@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>能耗统计</title>
    @include('admin.common.css')
    <link rel="stylesheet" type="text/css" href="{{ asset('hplus/css/base.css') }}"/>


</head>
<body id="statistics" class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>检索</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" id="searchFrom">
                    <div class="row">
                        <div class="col-md-2">
                            {!! Form::label('直营：', null, ['class' => 'col-sm-4 control-label']) !!}
                            {!! Form::select('data[user]', $user2, '', ['class' => 'chosen-select','id'=>'province']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::label('独立：', null, ['class' => 'col-sm-4 control-label']) !!}
                            {!! Form::select('data[user_id]', $user3, '', ['class' => 'chosen-select','id'=>'city']) !!}
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success" type="button" id="ok"><i
                                        class="fa fa-paper-plane-o"></i> 查询
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 表数据--}}
    <script>
        var colums = [
            {!! $tablePresenter->jsColums('时间','times') !!}
            {!! $tablePresenter->jsColums('总设备','all_device_count') !!}
            {!! $tablePresenter->jsColums('调节次数','adjust_count') !!}
            {!! $tablePresenter->jsColums('工作时长','all_time') !!}
            {!! $tablePresenter->jsColums('未用系统能耗','use_energy') !!}
            {!! $tablePresenter->jsColums('使用系统能耗','no_use_energy') !!}
            {!! $tablePresenter->jsColums('节能数量','use_energy_count') !!}
            {!! $tablePresenter->jsColums('节能比例','use_energy_scale') !!}
        ];
    </script>
    @component('admin/components/table',$reponse)
    @endcomponent



    {{-- 图表 --}}
    <div id="main" class="flex-row">
        <div id="view" class="flex-column">
            <div id="course">
                <h1><b class="inline-block">系统能耗对比</b></h1>
                <div class="chart">
                    <div class="point">
                        <p><b></b><span>使用系统能耗</span></p>
                        <p><b></b><span>未用系统能耗</span></p>
                    </div>
                    <div id="aaa" style="width:100%;height:600px;"></div>
                </div>
                <div id="info">
                    <h2>节能估算方式：<b>P1=P(C1-C2)/(C1-C3)</b></h2>
                    <p><b>P1</b><span>空调实际功率，空调的功耗主要来自压缩机，当室内温度到设定温度时，压缩机会暂时停机</span></p>
                    <p><b>P</b><span>空调满载功率，基本就是压缩机功率（大空调的压缩机占整机的98%以上），在你这里是31KW</span></p>
                    <p><b>C1</b><span>环境温度，是指完全不用空调时的室内温度，如果将空调设定在此温度，压缩功率为0</span></p>
                    <p><b>C2</b><span>设定温度，空调的实际设定温度，当小于C3时取C2=C3</span></p>
                    <p><b>C3</b><span>满载温度，空调不停机时房间能达到的最低温度，跟空调功率，散热器效率，房间热阻有关</span></p>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('hplus/js/plugins/echarts/echarts-all.js')}}" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('aaa'));
    // 指定图表的配置项和数据
    option = {
        title: {

        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['意向', '预购', '成交']
        },
        toolbox: {
            show: true,
            feature: {
                magicType: {show: true, type: ['stack', 'tiled']},
                saveAsImage: {show: true}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: ['00:00-02:00', '02:00-04:00', '04:00-06:00', '06:00-08:00', '08:00-10:00', '10:00-12:00', '12:00-14:00', '14:00-16:00', '16:00-18:00', '18:00-20:00', '20:00-22:00', '22:00-24:00']
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            name: '未用系统能耗',
            type: 'line',
            smooth: true,
            data: [4500, 4200, 4400, 4000, 3600, 5000, 6200, 6000, 4000, 4500, 5000, 5500]
        },
            {
                name: '使用系统能耗',
                type: 'line',
                smooth: true,
                data: [3500, 3200, 3400, 3000, 2600, 4000, 5200, 5000, 6000, 5500, 5000, 4500]
            }]
    }
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);

</script>
</body>
</html>
