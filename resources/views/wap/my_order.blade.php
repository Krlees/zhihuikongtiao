<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes">
    <title>缴费</title>
</head>
<body class="gray-bg">
<style>
    /* CSS Document */
    * {
        margin: 0;
        padding: 0;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        -webkit-text-size-adjust: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
        font-family: Arial, "微软雅黑";
    }

    img {
        border: none;
        max-width: 100%;
        vertical-align: middle;
    }

    body, p, form, input, button, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6 {
        margin: 0;
        padding: 0;
        list-style: none;
        overflow-x: hidden
    }

    h1, h2, h3, h4, h5, h6 {
        font-size: 100%;
    }

    input, textarea {
        -webkit-user-select: text;
        -ms-user-select: text;
        user-select: text;
        -webkit-appearance: none;
        font-size: 1em;
        line-height: 1.5em;
    }

    table {
        border-collapse: collapse;
    }

    input, select, textarea {
        outline: none;
        border: none;
        background: none;
    }

    a {
        outline: 0;
        cursor: pointer;
        *star: expression(this.onbanner=this.blur());
    }

    a:visited {
        text-decoration: none;
    }

    a {
        text-decoration: none;
        -webkit-touch-callout: none;
    }

    em, i {
        font-style: normal;
    }

    li, ol {
        list-style: none;
    }

    html {
        font-size: 10px;
    }

    .clear {
        clear: both;
        height: 0;
        font-size: 0;
        line-height: 0;
        visibility: hidden;
        overflow: hidden;
    }

    .fl {
        float: left;
    }

    .fr {
        float: right;
    }

    body {
        margin: 0 auto;
        max-width: 640px;
        min-width: 320px;
        color: #555;
        padding-bottom: 8%;
        /*background: #fef9f1;*/
        height: 100%;
    }

    /* 底部 */
    @media screen and (max-width: 320px) {
        body, input, select {
            font-size: 10px
        }
    }

    @media screen and (min-width: 320px) {
        body, input, select {
            font-size: 11.25px
        }
    }

    @media screen and (min-width: 400px) {
        body, input, select {
            font-size: 12.5px
        }
    }

    @media screen and (min-width: 480px) {
        body, input, select {
            font-size: 13.75px
        }
    }

    @media screen and (min-width: 560px) {
        body, input, select {
            font-size: 15px
        }
    }

    @media screen and (min-width: 600px) {
        body, input, select {
            font-size: 16.25px
        }
    }

    @media screen and (min-width: 640px) {
        body, input, select {
            font-size: 18px
        }
    }
</style>
<style>
    .header banner {
        height: 220px;
    }

    .header .title {
        height: 32px;
        line-height: 32px;
        font-size: 18px;
        padding: 10px 0px 10px 18px;
    }

    .border-bottom {
        position: relative;
        border-top: none !important;
    }

    .border-bottom::after {
        content: " ";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 1px;
        background-color: #e4e4e4;
        -webkit-transform-origin: left bottom;
        transform-origin: left bottom;
    }

    .btn {
        display: inline-block;
        padding: 4px 8px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .btn-white {
        color: inherit;
        background: #fff;
        border: 1px solid #e7eaec;
    }

    .btn-default {
        background-color: #c2c2c2;
        border-color: #c2c2c2;
        color: #FFF;
    }

    .btn-primary {
        background-color: #1ab394;
        border-color: #1ab394;
        color: #FFF;
    }

    .checked {
        background-color: #1ab394;
        border-color: #1ab394;
        color: #FFF;
    }
    .header{
        height: 32px;
        line-height: 32px;
        padding-left: 12px;
    }

    .border-bottom {
        position: relative;
        border-top: none !important;
    }

    .border-bottom::after {
        content: " ";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 1px;
        background-color: #e4e4e4;
        -webkit-transform-origin: left bottom;
        transform-origin: left bottom;
    }



</style>
<link href="{{asset('admin/css/bootstrap.min.css?v=3.3.6')}}" rel="stylesheet">
<link href="{{asset('admin/css/plugins/bootstrap-table/bootstrap-table.min.css')}}" rel="stylesheet">
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="{{asset('admin/js/bootstrap.min.js?v=3.3.6')}}"></script>
<script src="{{asset('admin/js/plugins/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('admin/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js')}}"></script>
<body>

<div class="header">
        我的缴费记录
</div>
<div class="border-bottom"></div>
<div class="wrapper">
    <table id="table" data-mobile-responsive="true">
    </table>
</div>


<!-- 全局js -->
<script type="text/javascript">
    $(function () {
        initTable();
    });

    /* 初始化表格 */
    function initTable() {
        $('#table').bootstrapTable({
            height: '',
            url: "{{url('wap/ajax-order')}}",
            sidePagination: "server",
            columns: [
                {
                    field: 'order_sn',
                    title: '订单号'
                }, {
                    field: 'productDesc',
                    title: '费用清单'
                }, {
                    field: 'amount',
                    title: '总价格'
                }, {
                    field: 'created_at',
                    title: '时间'
                }
            ]
        });

        setTimeout(function () {
            $('#table').bootstrapTable('resetView');
        }, 200);
    }


</script>
</body>
</html>