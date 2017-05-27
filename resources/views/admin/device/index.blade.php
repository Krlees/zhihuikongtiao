@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>设备管理</title>
    <script>
        var colums = [
                {!! $tablePresenter->jsCheckbox() !!}
                {!! $tablePresenter->jsColums('id','id') !!}
                {!! $tablePresenter->jsColums('设备标识名','name') !!}
                {{--{!! $tablePresenter->jsColums('设备号','did') !!}--}}
                {{--{!! $tablePresenter->jsColums('mac地址','mac') !!}--}}
                {!! $tablePresenter->jsColums('所在房间','room') !!}
                {{--{!! $tablePresenter->jsColums('productkey','productkey') !!}--}}
                {{--{!! $tablePresenter->jsColums('passcode','passcode') !!}--}}
                {{--{!! $tablePresenter->jsColums('type','type') !!}--}}
                {{--{!! $tablePresenter->jsColums('加入时间','created_at','true') !!}--}}
            {
                'field': '',
                'title': '操作',
                'align': 'center',
                'events': 'operateEvents',
                'formatter': function (value, row, index) {
                    var strs = "";
                    strs += '<a class="view btn btn-xs btn-info tooltips" href="{{url('admin/device/setting')}}/' + row.id + '" title="配置设备上网" data-toggle="modal" data-target="#myModal" data-original-title="配置设备上网" data-placement="top"><i class="fa fa-eye"></i>配置设备上网</a>　';
                    strs += '<a class="adjust btn btn-xs btn-outline btn-danger tooltips" href="{{url('admin/electric/add')}}/' + row.id + '" title="添加受控设备"> <i class="fa fa-paper-plane-o"></i>添加受控设备</a>　';
                    strs += '<a class="adjust btn btn-xs btn-outline btn-success tooltips" href="{{url('admin/device/adjust')}}/' + row.id + '" title="调控"> <i class="fa fa-paper-plane-o"></i>调控</a>　';
                    strs += '<a class="edit btn btn-xs btn-outline btn-warning tooltips" href="javascript:void(0)" title="编辑"><i class="fa fa-edit"></i></a>　';
                    strs += '<a class="remove btn btn-xs btn-outline btn-danger tooltips destroy_item" href="javascript:void(0)" title="删除"> <i class="fa fa-trash"></i></a>　';

                    return strs;
                }
            },
        ];
    </script>
</head>
<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">
    @component('admin/components/table',$reponse)
    @endcomponent

    <script>
        var Append = '<a id="adjust-action" href="javascript:;" class="btn btn-outline btn-default" title="">调控</a>';
        $("#toolbar").append(Append);

        $(document).on('click', '#adjust-action', function () {
            var ids = getIdSelections();
            ids = ids.join(",");
            if( !ids ){
                layer.msg("请至少选择一项");
                return false;
            }

            window.location.href = "{{url('admin/device/adjust-all')}}" + "/" + ids;
        })


    </script>
</div>
@include('admin.common.modal')

</body>
</html>
