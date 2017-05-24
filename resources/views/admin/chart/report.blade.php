@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>报表分析</title>
    <script>
        var colums = [
            {!! $tablePresenter->jsCheckbox() !!}
            {!! $tablePresenter->jsColums('ID','id','true') !!}
            {!! $tablePresenter->jsColums('酒店名称','name') !!}
            {!! $tablePresenter->jsColums('设备名称','url') !!}
            {!! $tablePresenter->jsColums('设备在线时长','url') !!}
            {!! $tablePresenter->jsColums('未使用设备用电','url') !!}
            {!! $tablePresenter->jsColums('使用设备用电','url') !!}
            {!! $tablePresenter->jsColums('节省用电度','url') !!}
            {!! $tablePresenter->jsColums('节省钱','url') !!}
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
