@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>权限管理</title>

    <script>
        var colums = [
            {!! $tablePresenter->jsCheckbox() !!}
            {!! $tablePresenter->jsColums('ID','id','true') !!}
            {!! $tablePresenter->jsColums('角色标识','name') !!}
            {!! $tablePresenter->jsColums('角色名称','display_name') !!}
            {!! $tablePresenter->jsEvents(['view','edit','remove']) !!}
        ];
    </script>
</head>

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    @component('admin/components/table',$reponse)
    @endcomponent

</div>

@include('admin.common.modal')




</body>
</html>