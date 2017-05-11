<?php
namespace App\Presenters\Admin;

use Collective\Html\FormBuilder;
use Form;

class FormPresenter
{
    /**
     * 生成表单html
     *
     * @param $type
     * @param $name
     * @param null $value
     * @param array $options
     */
    public function bulidFieldHtml($type, $name, $value = null, $options = [])
    {
        // 默认样式
        $opt = ['class' => 'form-control'];
        switch ($type) {

            case 'checkbox':
                $opt = ['class' => 'form-control checkbox-inline', 'style' => 'width: 20px'];
                $options = array_merge($options, $opt);
                if (!is_array($value) || empty($value)) {
                    return "error: 数据为空,请检查";
                }

                $htmls = '';
                foreach ($value as $k => $v) {
                    $htmls .= '<label class="checkbox-inline">' . Form::checkbox($name, $v['value'], isset($v['checked']) ? $v['checked'] : false, $options) . $v['text'] . '</label>';
                }

                return $htmls;
                break;

            case 'radio':
                $opt = ['class' => 'form-control radio-inline', 'style' => 'width: 20px'];
                $options = array_merge($options, $opt);
                if (!is_array($value) || empty($value)) {
                    return "error: 数据为空,请检查";
                }

                $htmls = '';
                foreach ($value as $k => $v) {
                    $htmls .= '<label class="radio-inline">' . Form::radio($name, $v['value'], isset($v['checked']) ? $v['checked'] : false, $options) . $v['text'] . '</label>';
                }

                return $htmls;
                break;

            case 'select':

                if (!is_array($value)) {
                    return "error: 数据错误,请检查";
                }

                // 插入最后, 利用krsort排序排到第一
                $value[] = [
                    'text' => '-请选择-',
                    'value' => 0
                ];
                krsort($value);

                $opt = ['class' => 'chosen-select'];
                $options = array_merge($options, $opt);

                $checked = false;
                foreach ($value as $k => $v) {
                    $list[$v['value']] = $v['text'];
                    if (isset($v['checked']) && $v['checked'] == true) {
                        $checked = $v['value'];
                    }
                }

                return Form::select($name, $list, $checked, $options);
                break;

            case 'textarea':
                $options = array_merge($options, $opt);
                return Form::textarea($name, $value, $options);
                break;

            case 'image':
                return Form::image($value, $name);
                break;

            case 'password':
                $options = array_merge($options, $opt);
                return Form::password($name, $options);
                break;

            case 'file':
                $str = <<<EOT
<div class="page-container">
    <p>您可以尝试文件拖拽来上传图片</p>
    <div id="uploader" class="wu-example">
        <div class="queueList">
            <div id="dndArea" class="placeholder">
                <div id="filePicker"></div>
                <p>或将照片拖到这里，单次最多可选300张</p>
            </div>
        </div>
        <div class="statusBar" style="display:none;">
            <div class="progress">
                <span class="text">0%</span>
                <span class="percentage"></span>
            </div>
            <div class="info"></div>
            <div class="btns">
                <div id="filePicker2"></div>
                <div class="uploadBtn">开始上传</div>
            </div>
        </div>
    </div>
</div>
EOT;

                $str .= '<script src="' . asset('admin/js/plugins/webuploader/webuploader.min.js') . '"></script>';
                $str .= '<script src="' . asset('admin/js/uploads.setting.js') . '"></script>';

                return $str;
                break;

            default:
                if (in_array($type, ['text', 'date', 'datetime', 'url', 'tel', 'number', 'hidden', 'email', 'datetimeLocal', 'color'])) {
                    $options = array_merge($options, $opt);

                    return Form::$type($name, $value, $options);
                }
                return;

        }

    }


}