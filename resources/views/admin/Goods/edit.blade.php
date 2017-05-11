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

<script type="text/javascript">

    var flag = "{{$reponse['info']['flag']}}";
    $("input[name='data[status]'][value='{{$reponse['info']['status']}}']").attr("checked",true);
    $("input[name='data[flag][]']").each(function () {
        if( flag ){
            if( flag.indexOf($(this).val()) >= 0 ){
                $(this).attr("checked",true);
            }
        }
    });
    $(function () {
        $('#top').change(function () {
            var id = $(this).val();
            var selectHtmls = "";
            if (id > 0) {
                $.getJSON("{{url('admin/goods/get-sub-class')}}" + "/" + id, {}, function (result) {
                    var initHtml = "<option value='" + id + "'>-请选择-</option>";
                    if (result.code == '0') {
                        $.each(result.data, function (i, v) {
                            selectHtmls += "<option value='" + v.id + "'>" + v.name + "</option>";
                        });
                    }

                    if (selectHtmls != "") {
                        $('#sub').chosen("destroy");
                        $("#sub").html(initHtml + selectHtmls).chosen({width: "200px"});
                        $("#sub_chosen").show();
                    }
                    else {
                        $('#sub').chosen("destroy");
                        $("#sub").html("").chosen({width: "200px"});
                        $("#sub_chosen").hide();
                    }
                });
            }
            else {
                $('#sub').chosen("destroy");
                $("#sub").html("").chosen({width: "200px"});
                $("#sub_chosen").hide();
            }


        });


    })


</script>
</body>
</html>
