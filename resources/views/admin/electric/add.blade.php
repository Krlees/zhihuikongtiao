<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加受控设备</title>
</head>
<body class="gray-bg">
@inject('formPresenter','App\Presenters\Admin\FormPresenter')
@include('admin/common/css')
@include('admin/common/js')
<link rel="stylesheet" type="text/css" href="{{asset('hplus/css/Validform_v5.3.2.css')}}">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>添加设备</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    {!! Form::open(['url' => url('admin/electric/add'),'class'=>'form-horizontal m-t validform']) !!}

                    <div class="form-group">
                        {!! Form::label('选择空调品牌', null, ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('data[ele_brand_id]',$brand,null,['class' => 'chosen-select','id'=>'change']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('数据量', null, ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('data[count]','',['class'=>'col-sm-4 form-control','id'=>'device-count']) !!}
                            <b id="device-count"></b>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('数据匹配', null, ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            <button class="btn btn-info" type="button" id="device-sub"><i
                                        class="fa fa-paper-plane-o"></i> 点击匹配
                            </button>
                            <br>
                            <span class="m-b-none"><i
                                        class="fa fa-info-circle"></i> 当有反应时则先保存数据</span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('保存数据', null, ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            <button class="btn btn-success" type="button" id="save-count"><i
                                        class="fa fa-paper-plane-o"></i> 保存数据
                            </button>
                            <br>
                            <span class="m-b-none"><i
                                        class="fa fa-info-circle"></i> 先保存数据，方可添加。</span>
                        </div>
                    </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <input type="hidden" name="data[ele_count]" id="ele-count" value="0">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-paper-plane-o"></i> 提交
                            </button>
                            <a href="javascript:" onclick="javascript:history.go(-1)" class="btn btn-white" id="back"><i
                                        class="fa fa-reply"></i> 返回</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>

</div>
<script>
    $('select.chosen-select').chosen({width: "200px"});
</script>
<script src="{{asset('hplus/js/plugins/layer/layer.min.js')}}"></script>
<script src="{{asset('hplus/js/plugins/bootstrap-switch/bootstrap-switch.min.js')}}"></script>
<script src="{{asset('hplus/js/gizwits_ws_0.3.0.js')}}"></script>
<script src="{{asset('hplus/js/Validform_v5.3.2_min.js')}}"></script>git
<script type="text/javascript">
    var $valid = $(".validform").Validform({
//        tiptype: function (msg, o, cssctl) {
//
//        },
        ajaxPost: true,
        datatype: {
            "zh": /^[\u4E00-\u9FA5\uf900-\ufa2d]$/,
            "username": function (gets, obj, curform, regxp) {
                //参数gets是获取到的表单元素值，obj为当前表单元素，curform为当前验证的表单，regxp为内置的一些正则表达式的引用;
                var reg1 = /^[\w\.]{4,16}$/,
                    reg2 = /^[\u4E00-\u9FA5\uf900-\ufa2d]{2,8}$/;

                if (reg1.test(gets)) {
                    return true;
                }
                if (reg2.test(gets)) {
                    return true;
                }
                return false;

                //注意return可以返回true 或 false 或 字符串文字，true表示验证通过，返回字符串表示验证失败，字符串作为错误提示显示，返回false则用errmsg或默认的错误提示;
            }

        },
        beforeCheck: function (curform) {
            //在表单提交执行验证之前执行的函数，curform参数是当前表单对象。
            //这里明确return false的话将不会继续执行验证操作;
        },
        beforeSubmit: function (curform) {

            //在验证成功后，表单提交前执行的函数，curform参数是当前表单对象。
            //这里明确return false的话表单将不会提交;
        },
        callback: function (data) {
            if (data.code == '0') {
                if (data.href != "") {
                    window.location.href = data.href;
                }
                else {
                    window.location.reload(true);
                }
            }
            else {
                layer.msg('操作失败');
            }
            $('#Validform_msg').hide();

        }

    });
    $valid.tipmsg.w["zh"] = "请输入中文字符！";


    var current = 0;
    var curCount = 0;
    var dataCounts = [];


    $("#change").change(function () {
        var brand_id = $(this).val();
        get_device_count(brand_id);
    });


    function get_device_count(brand_id) {
        $.getJSON('{{url('admin/device/get-device-count')}}' + '/' +{{$deviceId}}, {brand_id: brand_id}, function (res) {
            curCount = res.count;
            dataCounts = res.list;

            $("#device-count").val(current + '/' + curCount);
        });
    }

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

    $(function () {

        gizwitsws = newObj();
        gizwitsws.init();

        // 点击匹配
        $("#device-sub").click(function () {

            if (current == curCount) {
                return false;
            }

            $("#device-count").val(current + '/' + curCount);

            $.getJSON('{{url('admin/device/send-electric-cmd')}}', {ele_count: dataCounts[current]}, function (cmd) {
                current++;
                $("#device-count").val(current + '/' + curCount);


                writeCommand(cmd);
            });

        });

        // 保存数据
        $("#save-count").click(function () {
            var key=current-1;
            $.getJSON('{{url('admin/device/save-device-count')}}' + '/' +{{$deviceId}}, {ele_count: dataCounts[key]}, function (cmd) {

                $("#ele-count").val(dataCounts[key]);

                writeCommand(cmd)
            });
        });
    });


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

        // 判断是否同步
        if (cmd[3] == 96) {
            layer.msg("同步成功");
            is_sync = true;

        }
        else {
            //if (is_sync == false) {
            // 发送同步命令
                    {{--var cmd = '{"RAW_SMARTHOME":[{{$sync_cmd}}]}';--}}
           // var cmd = '{"RAW_SMARTHOME":[255,255,200,96,11,0,0,0,0,0,0,0,0,0,255,255,7,38,20,67,128,0,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 ,10,30,24,85,26,1,2,0,10,30,24,65,28,0,7,0,1,1,1,0,1,1,1,20,30,19,26,30,0,1,0,0,0,0,1,254,4,56,1,224,4,26,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]}';

            //writeCommand(JSON.parse(cmd));

            //}
        }

        var str = "收到设备" + value.did + "的Attrs: ";
        for (var key in value.attrs) {
            str = str + key + ":" + value.attrs[key] + "; ";
        }

        //layer.msg(str)

    }

    function onError(value) {
        layer.msg(value.toString());
    }

</script>

</body>
</html>
