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

<script src="{{asset('js/components/district.js')}}"></script>
<script type="text/javascript">
    {{--$(function () {--}}
        {{--if (!$("#sub").val()) {--}}
            {{--$('#sub_chosen').hide();--}}
        {{--}--}}

        {{--$('#top').change(function () {--}}
            {{--var id = $(this).val();--}}
            {{--getSub("{{url('admin/user/get-sub-user')}}", id, 'sub');--}}
        {{--});--}}
    {{--});--}}


</script>
</body>
</html>
