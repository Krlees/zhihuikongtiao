<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>冷热实况</title>

    @include('admin.common.css')
    <link rel="stylesheet" type="text/css" href="{{ asset('hplus/css/base.css') }}"/>
    <style>
        .control-label{margin-top: 6px;}
    </style>
</head>

<body class="gray-bg" id="displsy"\>

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
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-2">
                            {!! Form::label('省级：', null, ['class' => 'col-sm-4 control-label']) !!}
                            {!! Form::select('data[province_id]', $provinceSelect, '', ['class' => 'chosen-select','id'=>'province']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::label('市级：', null, ['class' => 'col-sm-4 control-label']) !!}
                            {!! Form::select('data[city_id]', ['0'=>'-请选择-'], '', ['class' => 'chosen-select','id'=>'city']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::label('区/县：', null, ['class' => 'col-sm-4 control-label']) !!}
                            {!! Form::select('data[area_id]', ['0'=>'-请选择-'], '', ['class' => 'chosen-select','id'=>'area']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::text('data[username]', '', ['placeholder' => '请输入酒店名称','class'=>'col-sm-3 form-control']) !!}
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success" type="submit"><i class="fa fa-paper-plane-o"></i> 查询</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="main" class="flex-row ">
        <div id="view" class="flex-column">
            <!--<div id="aaa" style="width: 25%;height:200px;"></div>
            <div id="bbb" style="width: 25%;height:200px;"></div>
            <div id="ccc" style="width: 25%;height:200px;"></div>
            <div id="ddd" style="width: 25%;height:200px;"></div>-->
            <div id="course">
                <h1><b class="inline-block">2017年冷热预估</b></h1>

                <div class="chart">
                    <div class="point">
                        <p><b></b><span>制冷</span></p>
                        <p><b></b><span>制热</span></p>
                    </div>
                    <div id="aaa" style="width: 25%;height:200px;"></div>
                    <div id="bbb" style="width: 25%;height:200px;"></div>
                    <div id="ccc" style="width: 25%;height:200px;"></div>
                    <div id="ddd" style="width: 25%;height:200px;"></div>
                </div>
            </div>
        </div>
    </div>

</div>

@include('admin.common.js')
<script src="{{asset('js/components/district.js')}}"></script>
<script src="{{asset('hplus/js/plugins/echarts/echarts-all.js')}}" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

    // 渲染select框
    $("select").chosen({"width":"150px"});

    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('aaa'));
    var myChart1 = echarts.init(document.getElementById('bbb'));
    var myChart2 = echarts.init(document.getElementById('ccc'));
    var myChart3 = echarts.init(document.getElementById('ddd'));
    // 指定图表的配置项和数据
    option = {
        series : [
            {
                name: '访问来源',
                type: 'pie',
                radius: '80%',
                label: {
                    normal: {
                        position: 'inside'
                    }
                },
                data:[
                    {value:4, name:'25%'},
                    {value:12, name:'75%'},
                ]
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
    myChart1.setOption(option);
    myChart2.setOption(option);
    myChart3.setOption(option);
</script>
</body>
</html>
