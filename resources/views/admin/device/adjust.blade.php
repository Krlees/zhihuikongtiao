<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>调控设备</title>


    @include('admin.common.css')
    <link rel="stylesheet" type="text/css" href="{{ asset('hplus/css/base.css') }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('hplus/js/plugins/bootstrap-switch/bootstrap-switch.min.css') }}"/>

    <style>
        label {
            width: 100%
        }

        .btn-success.active.focus, .btn-success.active:focus, .btn-success.active:hover, .btn-success:active.focus, .btn-success:active:focus, .btn-success:active:hover, .open > .dropdown-toggle.btn-success.focus, .open > .dropdown-toggle.btn-success:focus, .open > .dropdown-toggle.btn-success:hover {
            color: #fff;
            background-color: #39AEF5;
            border-color: #39AEF5;
        }

        .control-label {
            margin-top: 6px;
        }

        .device-info p {
            display: flex;
            justify-content: space-between;
        }

        .live-middle {
            min-height: 520px;
            background: #f3f3f4;
        }

        .live-middle .col-md-3, .live-middle .col-md-9 {
        }

        .col-md-3 .top, .col-md-3 .top .ibox-title, .col-md-3 .top .ibox-content {
            background: #fc8e08;
            border-radius: 6px;
            color: #fff;
        }

        .col-md-3 .top .ibox-title {
            border: none;
            border-bottom: 1px solid #ef8606;
        }

        .col-md-3 .top .ibox-title img {
            vertical-align: bottom;
            margin-right: 8px;
        }

        .col-md-3 .top .ibox-content {
            border: none;
        }

        .col-md-3 .top .ibox-content h1 {
            text-align: center;
            font-weight: bold;
            font-size: 10rem;
        }

        .col-md-3 .top .ibox-content b {
            vertical-align: top;
            font-size: 3.2rem;
        }

        .col-md-3 .top .ibox-content .text {
            font-size: 14px;
            width: 100%;
        }

        .col-md-3 .top .ibox-content .text .text-weather {
            padding: 0 6%;
        }

        .col-md-3 .bottom {
            margin-top: 20px;
        }

        .col-md-3 .bottom, .col-md-3 .bottom .ibox-title, .col-md-3 .bottom .ibox-content {
            background: #0fcf60;
            border-radius: 6px;
            color: #fff;
        }

        .col-md-3 .bottom .ibox-title {
            border: none;
            border-bottom: 1px solid #13c55e;
        }

        .col-md-3 .bottom .ibox-title img {
            vertical-bottomalign: bottom;
            margin-right: 8px;
        }

        .col-md-3 .bottom .ibox-content {
            border: none;
        }

        .col-md-3 .bottom .ibox-content h1 {
            width: 50%;
            float: left;
            text-align: center;
            font-weight: bold;
        }

        .col-md-3 .bottom .ibox-content .text {
            font-size: 14px;
            width: 100%;
        }

        .col-md-3 .bottom .ibox-content .text span {
            padding-right: 8px;
        }

        .col-md-9 .ibox-title img {
            vertical-align: bottom;
            margin-right: 8px;
        }

        .adjust .ibox-title {
            border: none;
            background: #1B94E5;
            color: #fff;
        }

        .adjust .ibox-content {
            /*height: 250px;*/
        }

        .adjust-times .col-md-12 {
            display: flex;
            flex-flow: row wrap;
            align-items: center;
        }

        .times {
            border-radius: 200px;
            display: flex;
            flex-flow: row nowrap;
            justify-content: center;
            align-items: center;
            height: 60px;
            width: 60px;
            border: 1px solid #dadada;
            margin-right: 12px;
            margin-bottom: 12px;
        }

        .btn-success {
            background: #39AEF5;
            border-color: #39AEF5;
            color: #fff;
        }

        .adjust-content .row {
            margin-bottom: 20px;
        }

        .adjust-content .form-control {
            width: 40%;
            text-align: center;
            /*position: relative;*/
            /*left: 50%;*/
        }

        .adjust-content .control-label {
            border-left: 2px solid #1B94E5;
            padding-left: 10px;
        }

        .live-middle .ibox-content .content {
            height: 390px;
            padding: 4% 0 0 12%;
        }

        .live-middle .ibox-content .content .content-desc {
            line-height: 3.5rem;
            font-size: 2.2rem;
            float: left;
            padding-left: 12%;
            min-width: 170px;
        }

        .live-middle .ibox-content .content .content-desc li {
            display: flex;
            justify-content: space-between;
        }

        .live-middle .ibox-content .content .content-desc span {
            flex: 100% 0 0;
            -webkit-box-flex: 100% 0 0;
        }

        .best-weather {
            width: 300px;
            height: 300px;
            float: left;
            display: box;
            display: -webkit-box;
            display: -moz-box;
            -webkit-box-pack: center;
            -moz-box-pack: center;
            -webkit-box-align: center;
            -moz-box-align: center;
            background-image: url("{{asset('hplus/css/images/control.png')}}")
        }

        #best-temp {
            color: #EA4D3C;
            padding-left: 33px;
            font-size: 9rem;
        }

        #best-temp i {
            font-size: 3rem;
            vertical-align: text-top;
        }
    </style>
</head>

<body class="gray-bg" id="control">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <a class="btn btn-info" href="javascript:;">
                        设备智能调控
                    </a>
                    <a class="btn btn-default" href="{{url('admin/device/index')}}">
                        返回
                    </a>
                </div>
                <div class="ibox-content">
                    <div class="row device-info">
                        <p>设备标识名: {{$info->name}}</p>
                        <p>设备号: {{$info->did}}</p>
                        <p>MAC地址: {{$info->mac}}</p>
                        <p>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row live-middle">
        <div class="col-md-3">
            <div class="top">
                <div class="ibox-title">
                    <h5 style="font-size: 16px;">
                        <img style="width: 18px;" src="{{asset('hplus/css/images/icon-yun.png')}}" alt="">
                        当前天气实况
                    </h5>
                </div>
                <div class="ibox-content">
                    <h1><i class="room_temp"></i><b>℃</b></h1>
                    <div class="text">
                        <span class="text-time">2017年4月14日</span>
                        <span class="text-weather">晴</span>
                        <span class="text-humidity">湿度：<i class="room_humidity">30</i></span>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="ibox-title">
                    <h5 style="font-size: 16px;">
                        <img style="width: 18px;" src="{{asset('hplus/css/images/icon-tongji.png')}}" alt="">
                        当前室内数据统计
                    </h5>
                </div>
                <div class="ibox-content">
                    <div>
                        <h1>
                            <i class="now_temp">26</i>℃
                            <p style="font-size: 12px">温度</p>
                        </h1>
                        <h1>
                            <i class="now_humidity">26</i>%
                            <p style="font-size: 12px">湿度</p>
                        </h1>
                    </div>
                    <div class="text">
                        <span>房间: 16m</span>
                        <span>空闭性: 60%</span>
                    </div>
                    <div class="text">
                        <span>空调能耗: 1300</span>
                        <span>实际能耗: 1300</span>
                        <span>老化：30%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="ibox-title">
                <h5 style="font-size: 16px;">
                    <img style="width: 18px;" src="{{asset('hplus/css/images/icon-wendu.png')}}" alt="">
                    最佳温度
                </h5>
            </div>
            <div class="ibox-content">
                <div class="content">
                    {{--<img style="float: left;width: 300px;height: 300px;" "--}}
                    {{--alt="">--}}
                    <div class="best-weather">
                        <p id="best-temp">25<i>℃</i></p>
                        <p style="position: relative;top: 5rem;left: -7rem;font-size: 2rem;">70%</p>
                    </div>

                    <ul class="content-desc">
                        <li><span>计算取值：</span></li>
                        <li>
                            <span>气温</span>
                            <b>40%</b>
                        </li>
                        <li>
                            <span>湿度</span>
                            <b>20%</b>
                        </li>
                        <li>
                            <span>晴</span>
                            <b>10%</b>
                        </li>
                        <li>
                            <span>阴</span>
                            <b>10%</b>
                        </li>
                        <li>
                            <span>雨</span>
                            <b>10%</b>
                        </li>
                        <li>
                            <span>雪</span>
                            <b>10%</b>
                        </li>
                    </ul>
                </div>
                {{--<div class="bottom">--}}
                {{--备注:60%取值24℃，70%取值25℃，80%取值26℃--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
    <div class="row adjust">
        <div class="col-sm-12">
            <div class="ibox-title">
                <h5 style="font-size: 16px;">
                    <img style="width: 18px;" src="{{asset('hplus/css/images/icon-yun.png')}}" alt="">
                    调整
                </h5>
            </div>
            <div class="ibox-content adjust-content">
                <div class="row">
                    {{--<div class="col-md-2">--}}
                        {{--<label class="control-label">情景模式</label>--}}
                        {{--<div class="btn-group">--}}
                            {{--<button data-key="1" class="scene_mode btn-adjust btn btn-white" type="button">经济--}}
                            {{--</button>--}}
                            {{--<button data-key="2" class="scene_mode btn-adjust btn btn-white" type="button">舒适--}}
                            {{--</button>--}}
                            {{--<button data-key="3" class="scene_mode btn-adjust btn btn-success" type="button">环保--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="col-md-2">
                        <label class="control-label">风速调节</label>
                        <div class="btn-group">
                            <button data-key="1" class="wind_rate btn-adjust btn btn-white" type="button">自动
                            </button>
                            <button data-key="2" class="wind_rate btn-adjust btn btn-white" type="button">小
                            </button>
                            <button data-key="3" class="wind_rate btn-adjust btn btn-success" type="button">中
                            </button>
                            <button data-key="4" class="wind_rate btn-adjust btn btn-white" type="button">大
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">模式</label>
                        <div class="btn-group">
                            <button data-key="2" class="mode btn-adjust btn btn-white btn-success" type="button">冷
                            </button>
                            <button data-key="3" class="mode btn-adjust btn btn-white" type="button">去湿
                            </button>
                            <button data-key="5" class="mode btn-adjust btn btn-white" type="button">热
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">自动风向</label>
                        <div class="btn-group">
                            <button data-key="1" class="auto_wind_direction btn-adjust btn btn-white" type="button">开
                            </button>
                            <button data-key="0" class="auto_wind_direction btn-adjust btn btn-success" type="button">关
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">手动风向</label>
                        <div class="btn-group">
                            <button data-key="1" class="wind_direction btn-adjust btn btn-white" type="button">上
                            </button>
                            <button data-key="2" class="wind_direction btn-adjust btn btn-white" type="button">中
                            </button>
                            <button data-key="3" class="wind_direction btn-adjust btn btn-success" type="button">下
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">温度调整</label>
                        <div class="btn-group">
                            <input type="text" value="25" class="temp adjust-do col-sm-1 form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label">空调开关</label>
                        <div class="switch" data-on="success" data-off="warning">
                            <input class="power switch" id="power" type="checkbox" checked/>
                        </div>
                    </div>
                </div>
                <div class="row adjust-times">
                    <div class="col-md-12">
                        <label class="control-label" style="margin-bottom: 12px">时间选择</label>

                        @for($i=1;$i<=12;$i++)
                            <a data-key="{{$i}}" class="btn btn-white times" type="button">{{$i}}:00</a>
                        @endfor
                    </div>
                    <div class="col-md-12">

                        @for($i=13;$i<=24;$i++)
                            <a data-key="{{$i}}" class="btn btn-white times" type="button">{{$i}}:00</a>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

{{--<table border="0" cellpadding="0" , cellspacing="0">--}}
{{--<tr>--}}
{{--<td align="left" valign="top" style="padding: 0 0 0 10px;">--}}
{{--<span id="log"></span>--}}
{{--</td>--}}
{{--</tr>--}}
{{--</table>--}}
<script src="{{asset('hplus/js/jquery.min.js')}}"></script>
<script src="{{asset('hplus/js/plugins/layer/layer.min.js')}}"></script>
<script src="{{asset('hplus/js/plugins/bootstrap-switch/bootstrap-switch.min.js')}}"></script>
<script src="{{asset('hplus/js/gizwits_ws_0.3.0.js')}}"></script>
<script>
    var sock = false;
    var gizwitsws;
    var deviceId = "{{$info->id}}";
    var did = "{{$info->did}}";
    var apiHost = "{{$gizwitsCfg['apihost']}}";
    var commType = "attrs_v4";
    var wechatOpenId = "{{$gizwit_id}}";
    var gizwitsAppId = "{{$gizwitsCfg['appid']}}";
    var is_sync = false;
    var power;  // 开关
    var roomState = true;
    var bestTemp = 0;


    $(function () {



        // 调用当前天气
        $.getJSON("{{url('admin/device/get-weather')}}", {}, function (res) {
            var temps = res.temp;
            $(".room_temp").text(res.temp);
            $(".room_humidity").text(res.humidity);
            $(".text-weather").text(res.weather);

            bestTemp = Math.ceil((temps - 0) / 0.7);
            if( bestTemp < 16 ){
                bestTemp = 16;
            } else if( bestTemp < 30 ){
                bestTemp = 30;
            }

            $("#best-temp").html(bestTemp + '<i>℃</i>');

        });

        gizwitsws = newObj();
        gizwitsws.init();

        // 情景模式
        $(".scene_mode").on('click', function () {
            $.getJSON("{{url('admin/device/get-scene')}}", {}, function (res) {

            });
        })

        // JS控件
        $("#power").bootstrapSwitch();

        // 切换控件状态并发送ajax请求控制命令
        // 风速调节 和 功能模式
        $(".btn-adjust").on('click', function () {
            $(this).removeClass('btn-white').addClass('btn-success').siblings('button').removeClass('btn-success').addClass('btn-white');

            ajaxCmd();
        });

        // 延迟调整 （增加）
        $(".timer-add").on('click', function () {
            var Timer = $("#timer-value");
            var value = Timer.val() - 0 + 1;
            var index = Timer.attr('data-index');

            Timer.val(value);

            ajaxCmd(value, index);
        });

        // 延迟调整（减少）
        $(".timer-reduce").on('click', function () {
            var Timer = $("#timer-value");
            var value = Timer.val() - 1;
            var index = Timer.attr('data-index');
            if (value < 0) {
                return false;
            }

            Timer.val(value);
            ajaxCmd();
        });

        // 空调开关操作
        $('#power').on({
            'switchChange.bootstrapSwitch': function (event, state) {
                ajaxCmd()
            }
        });

        // 温度调整 和 湿度调整
        $(".adjust-do").on('change', function () {
            var value = $(this).val();

            if (value < 16) {
                $(this).val(16);
            }
            else if (value > 30) {
                $(this).val(30);
            }

            ajaxCmd();
        });

        // 时间选择
        $(".times").on('click', function () {
            if ($(this).hasClass('btn-white')) {
                $(this).removeClass('btn-white').addClass('btn-success');
            }
            else {
                $(this).removeClass('btn-success').addClass('btn-white');
            }

            ajaxCmd();
        });

    });

    function ajaxCmd() {
//        if (sock) {
//            return false;
//        }

//        var cmd = '{"RAW_SMARTHOME":[255,255,11,16,1,0,0,0,0,0,0,0,1,0,25,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]}';
//        writeCommand(JSON.parse(cmd));
//        layer.msg(cmd);


        var temp = $(".temp").val(); // 温度
        var wind_direction = $(".wind_direction.btn-success").attr('data-key'); // 风向
        var auto_wind_direction = $(".auto_wind_direction.btn-success").attr('data-key'); // 自动风向
        var mode = $(".mode.btn-success").attr('data-key'); // 模式
        var wind_rate = $(".wind_rate.btn-success").attr('data-key'); // 风速调节
        var times = [];
        var power = $('#power').bootstrapSwitch('state');
        $(".times.btn-success").each(function (i, v) {
            times.push($(v).attr('data-key'));
        });

        console.log($(".wind_rate.btn-success"));
        sock = true;
        $.post("{{url('admin/device/get-gizwit-cmd')}}" + "/" + deviceId, {
            '_token': "{{csrf_token()}}",
            'temp': temp,
            'wind_rate': wind_rate,
            'wind_direction': wind_direction,
            'auto_wind_direction': auto_wind_direction,
            'power': power,
            'mode': mode,
            'times': times,
        }, function (cmd) {
            sock = false;

            if (cmd) {
                writeCommand(cmd);
            } else {
                layer.msg('命令传输失败');
            }

        }, 'json');
    }

    function newObj() {
        gizwitsws = new GizwitsWS(apiHost, wechatOpenId, gizwitsAppId, commType);
        gizwitsws.onInit = onInit;
        gizwitsws.onConnected = onConnected;
        gizwitsws.onOnlineStatusChanged = onOnlineStatusChanged;
        gizwitsws.onReceivedRaw = onReceivedRaw;
        gizwitsws.onReceivedAttrs = onReceivedAttrs;
        gizwitsws.onError = onError;
        layer.msg("初始化对象成功!");

        return gizwitsws;
    }

    function writeCommand(cmd) {
        try {
            gizwitsws.write(did, cmd);
            layer.msg("发送指令成功");
        } catch (e) {
            layer.msg("数据格式错误：" + e);
        }
    }

    //=========================================================
    // callback functions
    //=========================================================

    /**
     * 初始化完成后链接设备
     */
    function onInit(devices) {
        if (devices.length == 0) {
            layer.msg("没有绑定的设备");
        }
        else {
            for (var i = 0; i < devices.length; i++) {
                if (devices[i].did == did) {
                    //connect();//连接设备
                    this.connect(did);
                    return;
                }
            }
        }
    }

    /**
     * 连接成功后读取状态
     * @param did
     */
    function onConnected(did) {
        layer.msg("与设备:" + did + "连接成功!");
        this.read(did, null);
    }

    function onOnlineStatusChanged(value) {
        if (value.is_online == '1') {
            //layer.msg("设备上线");
        } else {
            //layer.msg("设备已下线");
        }
    }

    function onReceivedRaw(value) {
        var str = "收到设备" + value.did + "的Raw: [";
        for (var i = 0; i < value.raw.length; i++) {
            str = str + value.raw[i] + ",";
        }
        str = str.substr(0, str.length - 1) + "]";
        layer.msg(str);
    }

    /**
     * 设备的回调信息
     * @param value
     */
    function onReceivedAttrs(value) {
        var cmd = value.attrs.RAW_SMARTHOME;

        if (cmd[3] == 80) {
            $(".now_temp").text(cmd[24]);
            $(".now_humidity").text(cmd[26]);

            // 当前室内温度等于设置的温度
            if (cmd[24] == $(".temp").val()) {
                $(".temp").val(bestTemp);

                ajaxCmd();
            }
        }

        // 判断是否同步
        if (cmd[3] == 96) {
            layer.msg("同步成功");
            is_sync = true;


            $(".temp").val(cmd[22]);

            cmd = cmd.join(",");

            // 记录第一次同步成功返回的状态
            {{--$.post('{{url("admin/device/save-cmd")}}', {cmd: cmd, '_token': "{{csrf_token()}}"}, function (result) {--}}

            {{--});--}}

            // 检测匹配节能策略
            checkStrategy();

        }
        else {
            //if (is_sync == false) {
            // 发送同步命令
                    {{--var cmd = '{"RAW_SMARTHOME":[{{$sync_cmd}}]}';--}}
            var cmd = '{"RAW_SMARTHOME":[255,255,200,96,11,0,0,0,0,0,0,0,0,0,255,255,7,38,20,67,128,0,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 ,10,30,24,85,26,1,2,0,10,30,24,65,28,0,7,0,1,1,1,0,1,1,1,20,30,19,26,30,0,1,0,0,0,0,1,254,4,56,1,224,4,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]}';

            writeCommand(JSON.parse(cmd));

            //}
        }

        var str = "收到设备" + value.did + "的Attrs: ";
        for (var key in value.attrs) {
            str = str + key + ":" + value.attrs[key] + "; ";
        }

    }

    function onError(value) {
        layer.msg(value.toString());
    }

    /**
     *  检测节能策略是否介入
     */
    function checkStrategy() {
        var outTemp = $("#room_temp").text() - 0; // 室外温度
        var inTemp = $("#now_temp").text() - 0;  // 室内温度

        $.ajax({
            url: '{{url('admin/strategy/set-strategy-log')}}',
            type: 'POST',
            dataType: 'json',
            data: {
                _token: "{{csrf_token()}}",
                out_temp: outTemp,
                in_temp: inTemp,
                deviceId:deviceId
            },
        })
            .done(function (res) {
                if(res.code == '0'){
                    ajaxCmd(res.cmd);
                }
            })
            .fail(function () {
                console.log("error");
            })
            .always(function () {
                console.log("complete");
            });
    }


</script>

</body>
</html>