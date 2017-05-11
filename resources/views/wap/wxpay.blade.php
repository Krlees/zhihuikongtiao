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
        background: #fef9f1;
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

    .form-group {
        display: -webkit-flex; /* Safari */
        display: flex;
        margin-top: 18px;
    }

    .form-group .title {
        flex: 0 0 80px;
    }

    .form-group .title label {
        height: 32px;
        line-height: 32px;
        padding-left: 12px;
    }

    .form-group .row {
        flex: 1
    }

    .form-group .row a {
        display: inline-block;
        min-width: 20%;
    }

    .footer {
        position: fixed;
        bottom: 0;
        height: 90px;
        width: 100%
    }

    .footer {
        text-align: right;
    }

    .footer .footer-title {
    }

    .footer .footer-title .price {
        height: 38px;
        line-height: 26px;
        padding-top: 12px;
        padding-right: 12px;
    }

    .footer .footer-title .price i {
        margin-left: 8px;
        font-size: 24px;
        color: darkred;
    }

    .footer .footer-content {
        padding-right: 12px;
    }

    .checked {
        background-color: #1ab394;
        border-color: #1ab394;
        color: #FFF;
    }
</style>

<body class="gray-bg" style="margin: 0">

<div class="header">
    <div class="banner">
        <img src="http://p3.pstatp.com/large/1c5c00034e79ae135113" alt="">
    </div>
    <div class="title">
        缴费功能
    </div>
</div>
<div class="border-bottom"></div>

<div class="wrapper">

    <div class="wrapper-content">

        @foreach($products as $val)
            <div class="form-group">
                <div class="title">
                    <label>
                        {{$val->name}}
                    </label>
                </div>
                <div class="row">
                    @foreach($val->product as $k=>$v)
                        <a data-id="{{$v->id}}" data-price="{{$v->price}}"
                           class="col-md-4 btn-default btn {{$v->show_type}}"
                           href="javascript:;" class="btn btn-white">{{$v->name}}</a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="wrapper footer">
    <div class="footer-title">
        <div class="price">总价格：<i id="amount"></i></div>
    </div>
    <div class="footer-content">
        <a class="col-md-4 btn-primary btn" style="width: 140px" href="javascript:;"
           class="btn btn-white" id="create-order">微信支付</a>
    </div>
</div>

<!-- 全局js -->
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">


$(function () {

    var Radio = $(".radio");
    var Checkbox = $(".checkbox");

    // 初始化选中
    Radio.eq(0).addClass('checked');
    totalPrice();

    // 单选类
    Radio.click(function () {
        $(this).addClass('checked').siblings().removeClass('checked');

        totalPrice();
    });

    // 全选类
    Checkbox.click(function () {
        if ($(this).hasClass('checked')) {
            $(this).removeClass('checked');
        }
        else {
            $(this).addClass('checked');
        }

        totalPrice();
    });

    $("#create-order").click(function () {
        createOrder();
    });

    /**
     * 价格汇总
     */
    function totalPrice() {
        var amount = 0;
        $(".checked").each(function (i, v) {
            amount += v.getAttribute('data-price') - 0;
        });

        $("#amount").text("￥" + amount.toFixed(2));
    }

    /**
     * [提交订单]
     * @return {[type]} [description]
     */
    function createOrder() {
        var proIds = [];
        var amount = 0;
        $(".checked").each(function (i, v) {
            proIds.push(v.getAttribute('data-id'));
            amount += v.getAttribute('data-price') - 0;
        });

        $.post('{{url("Api/order-create")}}', {ids: proIds, amount: amount}, function (result) {
            if (result.code == '0') {
                // 调用微信支付
            }
        });
    }


})
</script>
</body>
</html>