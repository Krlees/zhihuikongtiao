@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>智能调控</title>
    @include('admin/common/css')
    @include('admin/common/js')
</head>
<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">
    {{-- 图表 --}}
    <div id="main" class="flex-row">
        <div id="view" class="flex-column">
            <div id="course">
                <h1><b class="inline-block">智能调控</b></h1>
                <div class="chart">
                    <div class="point">
                    </div>
                    <div id="aaa" style="width:100%;height:600px;"></div>
                </div>
                {{--<div id="info">--}}
                    {{--<h2>节能估算方式：<b>P1=P(C1-C2)/(C1-C3)</b></h2>--}}
                    {{--<p><b>P1</b><span>空调实际功率，空调的功耗主要来自压缩机，当室内温度到设定温度时，压缩机会暂时停机</span></p>--}}
                    {{--<p><b>P</b><span>空调满载功率，基本就是压缩机功率（大空调的压缩机占整机的98%以上），在你这里是31KW</span></p>--}}
                    {{--<p><b>C1</b><span>环境温度，是指完全不用空调时的室内温度，如果将空调设定在此温度，压缩功率为0</span></p>--}}
                    {{--<p><b>C2</b><span>设定温度，空调的实际设定温度，当小于C3时取C2=C3</span></p>--}}
                    {{--<p><b>C3</b><span>满载温度，空调不停机时房间能达到的最低温度，跟空调功率，散热器效率，房间热阻有关</span></p>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
</div>



<script src="{{asset('hplus/js/plugins/echarts/echarts-all.js')}}" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

    $('#user2').change(function () {
        var id = $(this).val();
        getSub("{{url('admin/user/get-sub-user')}}", id, 'user3', true, false);
    });

    createChart();
    function createChart(series) {

        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('aaa'));
        // 指定图表的配置项和数据
        option = {
            title: {},
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['比例']
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
                data: [{!! implode(",",$countData) !!}]
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name: '使用系统能耗',
                    type: 'line',
                    smooth: true,
                    data: [{!! implode(",",$chartData) !!}]
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    }


</script>
</body>
</html>
