<?php
namespace App\Services\Wap;

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

use App\Repositories\CategoryRepositoryEloquent;
use App\Repositories\ProductRepositoryEloquent;

class PaymentService
{
    private $product;
    private $category;

    public function __construct(ProductRepositoryEloquent $product, CategoryRepositoryEloquent $category)
    {
        $this->product = $product;
        $this->category = $category;
    }

    /**
     * 获取所有缴费产品
     * @Author Krlee
     *
     */
    public function getAllProducts()
    {
        $data = $this->category->all();
        foreach ($data as $v) {
            $v->product = $this->product->findWhere(['cate_id' => $v->id]);
        }

        return $data;
    }


}


