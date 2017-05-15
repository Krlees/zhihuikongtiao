@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
        <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>菜单管理</title>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">

    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>检索查询</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <form class="form-horizontal" role="form" id="searchFrom">

                        <div class="row">
                            <div class="col-md-3">
                                {{ Form::label('ds', null, ['class' => 'col-sm-4 control-label']) }}
                                <div class="col-sm-8" style="padding-left: 0">
                                    {{ Form::text('dd',null,['class' =>'form-control layer-date','onclick' => "laydate()"]) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2 col-sm-offset-1">
                                <button class="btn btn-w-m btn-info" type="button" id="searchRefresh">查询</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>


    <script>
        var colums = [
            {!! $tablePresenter->jsCheckbox() !!}
            {!! $tablePresenter->jsColums('ID','id','true') !!}
            {!! $tablePresenter->jsColums('名称','name') !!}
            {!! $tablePresenter->jsColums('价格','price') !!}
            {!! $tablePresenter->jsColums('状态','status') !!}
            {!! $tablePresenter->jsColums('添加时间','created_at') !!}
            {!! $tablePresenter->jsEvents() !!}
        ];
    </script>
    @component('admin/components/table',$reponse)
    @endcomponent
</div>

<script src="{{asset('hplus/js/plugins/layer/laydate/laydate.js')}}"></script>
</body>
</html>
