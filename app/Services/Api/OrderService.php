<?php
namespace App\Services\Api;

use App\Repositories\OrderRepositoryEloquent;
use App\Repositories\ProductRepositoryEloquent;
use Illuminate\Support\Facades\DB;

class OrderService extends BaseService
{
    private $order;
    private $product;

    public function __construct(OrderRepositoryEloquent $order, ProductRepositoryEloquent $product)
    {
        $this->order = $order;
        $this->product = $product;
    }

    /**
     * 创建订单
     * @Author Krlee
     *
     */
    public function create($param)
    {
        $member_id = $param['member_id'];
        $proIds = $param['ids'];
        $amount = $param['amount'];
        $order_sn = $this->order_sn();

        // 添加订单表
        try {
            $orderId = $this->order->create(compact('amount', 'order_sn', 'member_id'));
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }

        $proData = [];
        foreach ($proIds as $k => $pro_id) {
            $proInfo = $this->product->find($pro_id);
            $proData[$k]['pro_id'] = $proInfo->id;
            $proData[$k]['pro_name'] = $proInfo->name;
            $proData[$k]['price'] = $proInfo->price;
            $proData[$k]['order_id'] = $orderId->id;
        }

        // 添加订单产品表
        try {
            DB::table('order_product')->insert($proData);
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }

        // 添加订单日志
        try {
            DB::table('order_log')->insert([
                'order_id' => $orderId->id,
                'member_id' => $member_id,
                'msg' => '用户已下单',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }

    /***
     * 生成订单编号
     * @return <string>
     */
    private function order_sn()
    {
        return date('Ymd') . sprintf('%06d', (float)microtime() * 1000000) . mt_rand(100, 999);
    }
}