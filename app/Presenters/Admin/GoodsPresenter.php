<?php
namespace App\Presenters\Admin;

class GoodsPresenter
{

    /**
     * 返回商品分类<select>框
     * @param array $data 所有分类
     * @param int $checkids 已有的分类id
     * @return string
     */
    public function ClassSelect($categorys, $checkids = [])
    {

        $result = '';

        if( empty($checkids)){
            $checkids = [1];
        }
        foreach ($checkids as $checks) {

            $str = <<<Eof
<div class="form-group">
        <label class="col-sm-2 control-label" for="">商品分类</label>
        <div class="col-sm-10">
<select id="top" class="chosen-select"><option value="0">请选择</option>
Eof;
            $topSel = $subSel = '';
            foreach ($categorys as $key => $val) {
                $selected = $this->checkSelect($val['id'], $checks['pid']);
                $topSel .= '<option value="' . $val['id'] . '"' . $selected . '>' . $val['name'] . '</option>';

                if ($val['sub']) {
                    $subSel .= '<select id="sub" class="chosen-select" name="ids[]"><option value="0">请选择</option>';
                    foreach ($val['sub'] as $k => $v) {
                        $selected = $this->checkSelect($v['id'], $checks['id']);
                        $subSel .= '<option value="' . $v['id'] . '" ' . $selected . '>' . $v['name'] . '</option>';
                    }
                }

            }

            $topSel .= '</select>';
            $subSel .= '</select>';

            $result .= $str . $topSel . $subSel . '</div></div>';
        }

        return $result;
    }

    public function checkSelect($id, $checkids)
    {
        if ($id == $checkids) {
            return 'selected="selected"';
        }

        return '';
    }

}