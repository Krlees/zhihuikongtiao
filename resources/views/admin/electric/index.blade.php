@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>设备管理</title>
    <script>
        var colums = [
            {!! $tablePresenter->jsCheckbox() !!}
            {!! $tablePresenter->jsColums('id','id') !!}
            {!! $tablePresenter->jsColums('电器标识名','name') !!}
            {!! $tablePresenter->jsColums('电器类型','ele_name') !!}
            {!! $tablePresenter->jsColums('电器品牌','ele_brand_name') !!}
            {!! $tablePresenter->jsColums('加入时间','created_at','true') !!}
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
