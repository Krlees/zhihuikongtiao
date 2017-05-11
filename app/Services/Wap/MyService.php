<?php
namespace App\Services\Wap;

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

use App\Repositories\OrderProductRepositoryEloquent;
use App\Repositories\OrderRepositoryEloquent;

class MyService
{
    private $order;
    private $order_product;

    public function __construct(OrderRepositoryEloquent $order, OrderProductRepositoryEloquent $order_product)
    {
        $this->order = $order;
        $this->order_product = $order_product;
    }

    /**
     * 获取所有缴费产品
     * @Author Krlee
     *
     */
    public function ajaxOrderList($memberId)
    {
        $where['member_id'] = $memberId;
        $result = $this->order->ajaxPageList(0, 40, false, false, $where = []);
        foreach ($result['rows'] as $k=>$v){
            $arr = $this->order_product->findWhere(['order_id'=>$v['id']],['pro_name'])->toArray();
            if( $arr ) {
                $productDesc = implode(",", array_column($arr, 'pro_name'));
            }
            else {
                $productDesc = '';
            }

            $result['rows'][$k]['productDesc'] = $productDesc;
        }

        return $result;
    }


}


