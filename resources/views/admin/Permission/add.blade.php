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
    $(function () {
        $('#subPerm_chosen').hide();
        $('#topPerm').change(function () {
            var id = $(this).val();
            var selectHtmls = "";
            if (id > 0) {
                $.getJSON("{{url('admin/permission/get-sub-perm')}}" + "/" + id, {}, function (result) {
                    var initHtml = "<option value='" + id + "'>-请选择-</option>";
                    if (result.code == '0') {
                        $.each(result.data, function (i, v) {
                            selectHtmls += "<option value='" + v.id + "'>" + v.display_name + "</option>";
                        });
                    }

                    if (selectHtmls != "") {
                        $('#subPerm').chosen("destroy");
                        $("#subPerm").html(initHtml + selectHtmls).chosen({width: "200px"});
                        $("#subPerm_chosen").show();
                    }
                    else {
                        $('#subPerm').chosen("destroy");
                        $("#subPerm").html("").chosen({width: "200px"});
                        $("#subPerm_chosen").hide();
                    }
                });
            }
            else {
                $('#subPerm').chosen("destroy");
                $("#subPerm").html("").chosen({width: "200px"});
                $("#subPerm_chosen").hide();
            }


        });


    })


</script>
</body>
</html>
