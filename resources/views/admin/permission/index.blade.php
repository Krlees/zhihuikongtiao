@inject('tablePresenter','App\Presenters\Admin\TablePresenter')
<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>权限管理</title>

<script>
    var colums = [
        {!! $tablePresenter->jsCheckbox() !!}
        {!! $tablePresenter->jsColums('ID','id','true') !!}
        {!! $tablePresenter->jsColums('显示名称','display_name') !!}
        {!! $tablePresenter->jsColums('路由名','name') !!}
        {!! $tablePresenter->jsColums('说明','description') !!}
        {!! $tablePresenter->jsEvents() !!}
    ];


</script>
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    @component('admin/components/table',$reponse)
    @endcomponent
</div>

<script>
    /* 初始化表格 */
    function initTable() {
        $table.bootstrapTable({
            height: getHeight(),
            url: "{{url('admin/permission/index')}}",
            toolbar: "#toolbar",
            showColumns: true,
            pagination: true,
            showRefresh: true,
            showToggle: true,
            showExport: true,
            detailView: true,
//            detailFormatter: "detailFormatter",
            cellStyle: true,
            striped: true,
            cache: false,
            search: 'true',
            sortOrder: "asc",
            uniqueId: uniqueId, // 设置主键
            pageList: [10, 25, 50],
            sidePagination: "server",
            responseHandler: "responseHandler",
            columns: colums,
            onExpandRow: function (index, row, $detail) {
                InitSubTable(index, row, $detail);
            },
            queryParams: function (params) {   //设置查询参数
                var paramForm = getParamSearch();
                return $.extend({}, params, paramForm); // 合并参数
            },
        });

        setTimeout(function () {
            $table.bootstrapTable('resetView');
        }, 200);

        /* checkbox选择事件，包括单选，全选 */
        $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {

            // 木有选择一项时
            $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);

            // save your data, here just save the current page
            selections = getIdSelections();
            // push or splice the selections if you want to save all data selections
        });

        /* 当点击详细图标展开详细页面的时候触发。 */
//        $table.on('expand-row.bs.table', function (e, index, row, $detail) {});

        /* 删除事件 */
        $remove.click(function () {
            var ids = getIdSelections();
            $.getJSON("{{$reponse['action']['removeUrl']}}", {ids: ids.join(',')}, function (result) {
                if (result.code == '0') {
                    $table.bootstrapTable('remove', {
                        field: uniqueId,
                        values: ids
                    });
                    $remove.prop('disabled', true);
                }
                else {
                    layer.msg("操作失败");
                }
            });

        });

        $(window).resize(function () {
            $table.bootstrapTable('resetView', {
                height: getHeight()
            });
        });


    }

    /* 子表 */
    function InitSubTable(index, row, $detail) {
        var parentid = row.id;
        var cur_table = $detail.html('<table></table>').find('table');
        $(cur_table).bootstrapTable({
            url: '{{url('admin/permission/get-sub-perm')}}' + '/' + parentid,
            method: 'get',
            queryParams: {},
            ajaxOptions: {},
            clickToSelect: true,
            detailView: true,//父子表
            uniqueId: "id",
            columns: [
                {!! $tablePresenter->jsCheckbox() !!}
                {!! $tablePresenter->jsColums('ID','id','true') !!}
                {!! $tablePresenter->jsColums('显示名称','display_name') !!}
                {!! $tablePresenter->jsColums('路由名','name') !!}
                {!! $tablePresenter->jsColums('icon图标','icon') !!}
                {!! $tablePresenter->jsColums('说明','description') !!}
                {!! $tablePresenter->jsEvents() !!}
            ],
            //无线循环取子表，直到子表里面没有记录
            onExpandRow: function (index, row, $Subdetail) {
                InitSubTable(index, row, $Subdetail);
            }
        });
    }

</script>
</body>
</html>
