@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户管理</title>

    <script>
        var colums = [
            {!! $tablePresenter->jsCheckbox() !!}
            {!! $tablePresenter->jsColums('登陆账号','email') !!}
            {!! $tablePresenter->jsColums('名称','name') !!}
            {!! $tablePresenter->jsColums('设备数量','device_count') !!}
            {!! $tablePresenter->jsColums('联系地址','area_info') !!}
            {!! $tablePresenter->jsColums('联系电话','phone') !!}
            {!! $tablePresenter->jsColums('加入时间','created_at','true') !!}
            {!! $tablePresenter->jsEvents() !!}
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