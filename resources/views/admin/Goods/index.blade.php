@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品中心</title>


</head>
<body class="gray-bg">
<script>
// 必须定义要显示的字段
var colums = [
    {!! $tablePresenter->jsCheckbox() !!}
    {!! $tablePresenter->jsColums('ID','id','true') !!}
    {!! $tablePresenter->jsColums('名称','name') !!}
    {!! $tablePresenter->jsColums('商品分类','goodClass') !!}
    {!! $tablePresenter->jsColums('商品价格','price') !!}
    {!! $tablePresenter->jsColums('商品库存','storage') !!}
    {!! $tablePresenter->jsEvents() !!}
];
</script>

@component('admin/components/table',$reponse)
@endcomponent
</body>
</html>
