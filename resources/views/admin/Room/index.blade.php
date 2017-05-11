@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>房间管理</title>
    <script>
        var colums = [
            {!! $tablePresenter->jsCheckbox() !!}
            {!! $tablePresenter->jsColums('房间号','num','true') !!}
            {!! $tablePresenter->jsColums('房间名称','name') !!}
            {!! $tablePresenter->jsColums('所属酒店','hotel') !!}
            {!! $tablePresenter->jsColums('创建时间','created_at','true') !!}
            {!! $tablePresenter->jsEvents() !!}
        ];
    </script>
</head>
<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">

    @component('admin/components/table',$reponse)
    @endcomponent
</div>

<script>
    $('#user_id').chosen({width: "200px"})
</script>
</body>
</html>
