@inject('formPresenter','App\Presenters\Admin\FormPresenter')
@include('admin/common/css')
@include('admin/common/js')
<link rel="stylesheet" type="text/css" href="{{asset('hplus/css/Validform_v5.3.2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('hplus/css/plugins/webuploader/webuploader.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('hplus/css/demo/webuploader-demo.css')}}">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>{{$formTitle}}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    {!! Form::open(['url' => $formUrl,'class'=>'form-horizontal m-t validform']) !!}

                    @foreach ($formField as $i=>$v )
                        <div class="form-group">
                            {!! Form::label($v['title'], null, ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! $formPresenter->bulidFieldHtml($v['type'],$v['name'],$v['value'],$v['options']) !!}
                                @if( isset($v['tips']) )
                                    <span class="m-b-none"><i
                                                class="fa fa-info-circle"></i> {{$v['tips']}}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {!! isset($extendField)?$extendField:'' !!}
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
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


<!-- 全局js -->
<script src="{{asset('hplus/js/content.js?v=1.0.0')}}"></script>
<script src="{{asset('hplus/js/Validform_v5.3.2_min.js')}}"></script>
<script src="{{asset('hplus/js/plugins/jasny/jasny-bootstrap.min.js')}}"></script>
<script>
    $('select.chosen-select').chosen({width: "150px"});

    var $valid = $(".validform").Validform({
        ajaxPost: true,
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

</script>