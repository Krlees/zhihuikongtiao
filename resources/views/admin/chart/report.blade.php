@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>报表分析</title>
    <link rel="stylesheet" href="{{asset('hplus/css/plugins/datapicker/datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('hplus/css/plugins/colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <script>
        var colums = [
            {!! $tablePresenter->jsCheckbox() !!}
            {!! $tablePresenter->jsColums('ID','id','true') !!}
            {!! $tablePresenter->jsColums('酒店名称','username') !!}
            {!! $tablePresenter->jsColums('设备名称','device_name') !!}
            {!! $tablePresenter->jsColums('设备在线时长','all_time') !!}
            {!! $tablePresenter->jsColums('使用设备用电','comsume') !!}
            {!! $tablePresenter->jsColums('节省钱','fee') !!}
        ];

    </script>

</head>
<body class="gray-bg">
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
                        <div class="col-md-1">
                            {!! Form::label('时间：', null, ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-md-4">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="start" placeholder="开始时间" />
                                <span class="input-group-addon">到</span>
                                <input type="text" class="input-sm form-control" name="end" value="结束时间" />
                            </div>
                        </div>
                        <div class="col-md-1">
                            {!! Form::label('酒店名称：', null, ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::text('data[username]', '', ['placeholder' => '请输入酒店名称','class'=>'col-sm-3 form-control','id'=>'username']) !!}
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

    @component('admin/components/table',$reponse)
    @endcomponent

    <script src="{{asset('hplus/js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#datepicker input').datepicker();
        });
    </script>
</div>
</body>
</html>
