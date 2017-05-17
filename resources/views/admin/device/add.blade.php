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
@include('admin.common.modal')


<script type="text/javascript">
    $(function () {
        $('#sub_chosen').hide();
        $('#top').change(function () {
            var id = $(this).val();
            getSub("{{url('admin/user/get-sub-user')}}", id, 'sub');

            $("#room").chosen("destroy");
            $.getJSON("{{url('admin/user/get-user-room')}}" + '/' + id, {}, function (res) {
                var selectHtmls = "";
                $.each(res,function (i,v) {
                    selectHtmls += "<option value='" + v.id + "'>" + v.name + "</option>";
                });
                $("#room").html(selectHtmls).chosen({width: "200px"});
            });
        });
    });
</script>

</body>
</html>
