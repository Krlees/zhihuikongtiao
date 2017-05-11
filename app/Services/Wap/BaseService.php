<?php
namespace App\Services\Wap;

use App\Jobs\SendEmail;
use Route;

class BaseService
{

    public function sendSystemErrorMail($mail, $e)
    {
        $exceptionData = [
            'method' => Route::current()->getActionName(),
            'info' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
        dispatch(new SendEmail($mail, $exceptionData));
    }

    /**
     * 子父递归数据
     *
     * @param $menus
     * @param int $pid
     * @return array|string
     */
    public function childArr($data, $parentValue = 0, $primaryKey = 'id', $parnentKey = 'pid')
    {
        $arr = [];
        if (empty($data)) {
            return [];
        }

        foreach ($data as $key => $v) {
            if ($v[$parnentKey] == $parentValue) {
                $arr[$key] = $v;
                $arr[$key]['child'] = self::childArr($data, $v[$primaryKey]);
            }
        }
        return $arr;
    }

}