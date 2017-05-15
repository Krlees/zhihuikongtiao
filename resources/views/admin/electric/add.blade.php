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
            getSub("{{url('admin/electric/get-device')}}", id, 'sub');
        });

        $('#electric').change(function () {
            getSub("{{url('admin/electric/get-brand')}}", $(this).val(), 'brand');
        });
    });
</script>

</body>
</html>