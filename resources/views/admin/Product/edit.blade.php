<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$reponse['formTitle']}}</title>
</head>

<body class="gray-bg">
@component('admin/components/form',$reponse)
@endcomponent

<script>
    // 默认选择值
    $("input[name='data[status]'][value='{{$info->status}}']").attr("checked",true);

    var cateEle = $("select[name='data[cate_id]']");
    cateEle.chosen("destroy");
    cateEle.val("{{$info->cate_id}}");
    cateEle.chosen({width: "200px"});
</script>
</body>
</html>
