@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>消息管理</title>
    <script>
        var colums = [
            {!! $tablePresenter->jsCheckbox() !!}
            {!! $tablePresenter->jsColums('意见','msg') !!}
            {!! $tablePresenter->jsColums('酒店名称','username') !!}
            {!! $tablePresenter->jsColums('创建时间','created_at') !!}
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
