<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\MessageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseController;

class MessageController extends BaseController
{
    private $message;

    public function __construct(MessageService $message)
    {
        $this->message = $message;
    }

    public function index($isRead, Request $request)
    {
        if ($request->ajax()) {

            // 过滤参数
            $results = $this->message->ajaxList($isRead);

            return $this->responseAjaxTable($results['total'], $results['rows']);

        } else {
            $reponse = $this->returnSearchFormat(url('admin/message/index/' . $isRead),null,[
                'removeUrl' => url('admin/message/del')
            ]);

            return view('admin/message/index', compact('reponse'));
        }
    }

    public function del(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(",", $ids);
        }

        $affected = $this->message->delData($ids);
        $affected ? $this->responseData(0) : $this->responseData(9000);
    }

}
