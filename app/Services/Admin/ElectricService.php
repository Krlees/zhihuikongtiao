<?php

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Services\Admin;


use App\Models\Electric;
use App\Traits\Admin\QianhaiMdl;
use DB;

class ElectricService extends BaseService
{

    use QianhaiMdl;

    protected $elec;
    private $tbName;

    public function __construct(Electric $elec)
    {
        $this->elec = $elec;
        $this->tbName = $this->elec->getTable();
    }

    public function ajaxList($param)
    {
        $where = [];
        if (isset($param['search'])) {
            $where = [
                ['name', 'like', "%{$param['search']}%", 'OR'],
                ['num', 'like', "%{$param['search']}%"],
            ];
        }

        $elecDb = DB::table($this->tbName);
        $sort = $param['sort'] ?: $this->elec->getKeyName();
        $rows = DB::table($this->elec->getTable())->where($where)
            ->offset($param['offset'])->limit($param['limit'])
            ->orderBy($sort, $param['order'])
            ->get()
            ->toArray();
        $rows = cleanArrayObj($rows);
        $total = DB::table($this->elec->getTable())->where($where)->count();

        return compact('rows', 'total');
    }

    public function get($id)
    {
        return DB::table($this->tbName)->find($id);
    }

    public function getBrand($ele_id)
    {
        $result = $this->getDeviceBrand($ele_id);

        return array_get($result, 'root');
    }

    /**
     * 获取酒店下的所有房间
     * @Author Krlee
     *
     * @param int $user_id
     */
    public function getAll($user_id)
    {
        $result = DB::table($this->tbName)->where('user_id', $user_id)->get()->toArray();

        return cleanArrayObj($result);
    }

    public function addData($data)
    {
        $eleType = $this->getEleType();
        foreach ($eleType['root'] as $v) {
            if ($v['id'] == $data['ele_id']) {
                $data['ele_name'] = $v['name'];
            }
        }

        $eleBrand = $this->getDeviceBrand($data['ele_id']);
        foreach ($eleBrand['root'] as $k => $v) {
            if ($k == $data['ele_brand_id']) {
                $data['ele_brand_id'] = $k;
                $data['ele_brand_name'] = $v;
            }
        }

        $eleDataCount = $this->getDeviceCount($data['ele_id'], $data['ele_brand_id']);
        $data['ele_count'] = json_encode($eleDataCount['root']);

        $this->getDeviceCount($data['ele_id'], $data['ele_brand_id']);
        $data['created_at'] = date('Y-m-d H:i:s');

        return DB::table($this->tbName)->insert($data);
    }

    public function updateData($id, $data)
    {
        return DB::table($this->tbName)->where('id', $id)->update($data);
    }

    public function delData($ids)
    {
        try {
            DB::table($this->tbName)->whereIn('id', $ids)->delete();

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }


}