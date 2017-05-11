<?php
namespace App\Presenters\Admin;

class TablePresenter
{
    /**
     * 创建操作
     *
     * @param $createUrl    需要新建操作的url
     * @return string|void
     */
    public function bulidCreateAction($createUrl)
    {
        if (isset($createUrl{0})) {
            return <<<Eof
<a href="{$createUrl}" class="btn btn-outline btn-default" title="新建">
    <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
</a>
Eof;
        }

        return;

    }

    /**
     * 删除操作
     *
     * @param $removeUrl     Url链接
     * @return string|void
     */
    public function bulidRemoveAction($removeUrl)
    {
        if (isset($removeUrl{0})) {
            return <<<Eof
<button type="button" class="btn btn-outline btn-default" id="remove">
    <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
</button>
Eof;
        }
        return;
    }

    /**
     * 创建表格显示的字段信息
     *
     * @param $title
     * @param $field
     * @param bool $sortable
     * @return array
     */
    public function jsColums($title = '', $field, $sortable = 'false')
    {
        return <<<Eof
        {
            'field' : '{$field}',
            'title' : '{$title}',
            'align' : 'center',
            'sortable' : {$sortable}
        },
Eof;
    }

    /**
     * 创建表格多选操作
     * @return string
     */
    public function jsCheckbox()
    {
        return <<<Eof
        {
            'field' : 'state',
            'checkbox' : true,
            'align' : 'center',
            'valign' : 'middle'
        },
Eof;
    }

    /**
     * 创建表格里常用的编译及删除功能
     * @param string $eventFormat
     * @return string
     */
    public function jsEvents($eventFormat = ['edit','remove'])
    {
        $str = implode(",",$eventFormat);
        $str = str_replace(",","','",$str);

        return <<<Eof
        {
            'field' : '',
            'title' : '操作',
            'align' : 'center',
            'events' : 'operateEvents',
            'formatter' : function(value, row, index){
                return operateFormatter(row, ['{$str}'] );
            }
        },
Eof;
    }


}