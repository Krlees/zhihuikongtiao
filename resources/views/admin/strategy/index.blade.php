@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>策略管理</title>
    <script>
        var colums = [
            {!! $tablePresenter->jsCheckbox() !!}
            {!! $tablePresenter->jsColums('策略名称','name') !!}
            {!! $tablePresenter->jsColums('时间范围','times') !!}
            {!! $tablePresenter->jsColums('设定的温度值','temp') !!}
            {!! $tablePresenter->jsColums('调节上下温度值','temp') !!}
            {!! $tablePresenter->jsColums('是否需要除湿','is_humidity','true') !!}
            {!! $tablePresenter->jsEvents(['remove']) !!}
        ];
    </script>
</head>
<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">
    @component('admin/components/table',$reponse)
    @endcomponent
</div>

</body>
</html>
