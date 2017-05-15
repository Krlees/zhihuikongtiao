@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>菜单管理</title>
    <script>
        var colums = [
                {!! $tablePresenter->jsCheckbox() !!}
                {!! $tablePresenter->jsColums('ID','id','true') !!}
                {!! $tablePresenter->jsColums('订单号','order_sn') !!}
                {!! $tablePresenter->jsColums('价格','amount') !!}
                {!! $tablePresenter->jsColums('支付单号','pay_sn') !!}
                {!! $tablePresenter->jsColums('支付方式','pay_type') !!}
                {!! $tablePresenter->jsColums('状态','status') !!}
                {!! $tablePresenter->jsColums('下单时间','created_at') !!}
                {
                    'field' : '',
                    'title' : '操作',
                    'align' : 'center',
                    'events' : 'operateEvents',
                    'formatter' : function(value, row, index){
                        return operateFormatter(row, ['view'] );
                    }
                },
        ];
        
        function formatIcon(data) {
            return '$';
        }



    </script>
</head>
<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInRight">
    @component('admin/components/table',$reponse)
    @endcomponent
</div>

</body>
</html>
