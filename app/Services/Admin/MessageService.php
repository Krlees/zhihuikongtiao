<?php

// +----------------------------------------------------------------------
// | desc
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Services\Admin;

use App\Models\Message;
use App\Services\Admin\BaseService;
use DB;

class MessageService extends BaseService
{
    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function ajaxList($isRead)
    {

        $rows = DB::select("SELECT *,(SELECT name FROM users where users.id=message.user_id) as username FROM message WHERE is_read= :is_read", ['is_read' => $isRead]);
        $rows = obj2arr($rows);

        $total = DB::table($this->message->getTable())->where('is_read', $isRead)->count();

        return compact('rows', 'total');
    }

    public function delData($ids)
    {
        try {
            DB::table($this->message->getTable())->whereIn('id', $ids)->delete();

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}