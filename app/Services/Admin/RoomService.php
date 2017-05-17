<?php

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Services\Admin;


use App\Models\Room;
use DB;

class RoomService extends BaseService
{
    protected $room;
    private $tbName;

    public function __construct(Room $room)
    {
        $this->room = $room;
        $this->tbName = $this->room->getTable();
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

        $roomDb = DB::table($this->tbName);
        $sort = $param['sort'] ?: $this->room->getKeyName();

        $rows = $roomDb
            ->select([$this->tbName . '.*', DB::raw('(select name from users where users.id=' . $this->room->getTable() . '.user_id) as hotel')])
            ->offset($param['offset'])->limit($param['limit'])
            ->orderBy($sort, $param['order'])
            ->get()
            ->toArray();
        $rows = cleanArrayObj($rows);
        $total = $roomDb->where($where)->count();

        return compact('rows', 'total');
    }

    public function get($id)
    {
        return DB::table($this->tbName)->find($id);
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
            $kk = DB::transaction(function () use ($ids) {
                DB::table('devicee')->whereIn('room_id', $ids)->delete();
                DB::table($this->tbName)->whereIn('id', $ids)->delete();
            });

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function getUserRoom($id)
    {
        $result = DB::table($this->tbName)->where('user_id',$id)->get()->toArray();
        $result = cleanArrayObj($result);

        return $result;
    }


}