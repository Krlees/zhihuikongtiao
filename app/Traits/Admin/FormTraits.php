<?php
namespace App\Traits\Admin;

trait FormTraits
{
    protected $formField = [];

    /**
     * @return array
     */
    public function getFormField()
    {
        return $this->formField;
    }

    /**
     * 表单字段统一回调
     *
     * @param $formTitle  表单标题,如:添加产品
     * @param $formField  表单字段数据
     * @param $formUrl    表单提交地址
     */
    public function returnFormFormat($formTitle = '', $formField = [], $formUrl = null)
    {
        return compact('formTitle', 'formField', 'formUrl');
    }

    /**
     * 表格数据回调
     *
     * @param $searchUrl
     * @param array $searchField
     * @return array
     */
    public function returnSearchFormat($searchUrl = '', $searchField = [], $action = [])
    {
        $isForm = $searchField ? true : false;

        $action['add'] = array_get($action, 'addUrl', false);
        $action['remove'] = array_get($action, 'removeUrl', false);

        return compact('searchUrl', 'searchField', 'isForm', 'action');
    }

    /**
     * 返回组装好的action,配合<returnSearchFormat>方法使用
     *
     * @param string $addUrl
     * @param string $editUrl
     * @param string $removeUrl
     * @param bool $autoSearch
     * @return array
     */
    public function returnActionFormat($addUrl = null, $editUrl = null, $removeUrl = null, $autoSearch = true)
    {
        return [
            'addUrl' => $addUrl,
            'editUrl' => $editUrl,
            'removeUrl' => $removeUrl,
            'autoSearch' => $autoSearch
        ];
    }

    /**
     * 返回自动生成的action,配合<returnSearchFormat>方法使用
     *
     * @param $url    url前两位,例如: admin/menu
     * @param bool $autoSearch
     * @return array
     */
    public function returnAutoAction($url, $autoSearch = true)
    {
        return [
            'showUrl' => url($url . '/show'),
            'addUrl' => url($url . '/add'),
            'editUrl' => url($url . '/edit'),
            'removeUrl' => url($url . '/del'),
            'autoSearch' => $autoSearch
        ];
    }

    /**
     * 返回组装的字段数据【辅助表单】
     *
     * @param $type
     * @param string $title
     * @param $name
     * @param null $value
     * @param $options
     * @param null $tips
     * @return array
     */
    function returnFieldFormat($type, $title = '', $name, $value = '', $options = [], $tips = null)
    {
        $this->formField[] = compact('type', 'title', 'name', 'value', 'options', 'tips');
        return $this;
    }

    /**
     * 返回已处理好的【select框】商品分类
     *
     * @param $data
     * @param $name
     * @param $value
     * @param $checkid  为0则不帅选
     * @return array
     */
    public function returnSelectFormat($data = [], $name='name', $value='id', $checkid = 0)
    {
        if( !is_array($data)){
            return $data;
        }

        $return = [];
        foreach ($data as $k => $v) {

            $return[$k]['text'] = $v[$name];
            $return[$k]['value'] = $v[$value];
            if ($checkid == $v[$value]) {
                $return[$k]['checked'] = true;
            }
        }

        return $return;
    }
}