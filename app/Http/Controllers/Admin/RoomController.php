<?php

// +----------------------------------------------------------------------
// | 房间管理
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;


use App\Services\Admin\RoomService;
use App\Services\Admin\UserService;
use App\Traits\Admin\FormTraits;
use Illuminate\Http\Request;

class RoomController extends BaseController
{
    use FormTraits;

    private $room;

    public function __construct(RoomService $room)
    {
        $this->room = $room;
    }

    public function index(Request $request, UserService $userService)
    {
        if ($request->ajax()) {

            // 过滤AJAX参数
            $data = $this->cleanAjaxPageParam($request->all());
            $results = $this->room->ajaxList($data);

            return $this->responseAjaxTable($results['total'], $results['rows']);

        } else {

            $action = $this->returnActionFormat(url('admin/room/add'), url('admin/room/edit'), url('admin/room/del'));
            $reponse = $this->returnSearchFormat(url('admin/room/index'), null, $action);

            return view('admin/room/index', compact('reponse', 'userSelect'));
        }
    }

    public function add(Request $request, UserService $userService)
    {
        if ($request->ajax()) {
            $data = $request->input('data');
            $result = $this->room->addData($data);

            return $result ? $this->responseData(0,'','',url('admin/room/index')) : $this->responseData(9000);

        } else {
            $userList = $userService->getUserSelects(0); // 所有一级酒店
            $this->returnFieldFormat('select', '酒店', 'data[user_id]',
                $this->returnSelectFormat($userList, 'name', 'id'), ['id' => 'top']
            );
            $this->returnFieldFormat('select', '', 'data[user_id]', [], ['id' => 'sub']);
            $this->returnFieldFormat('text', '房号', 'data[num]', '', ['dateType' => 'n']);
            $this->returnFieldFormat('text', '房间名称', 'data[name]', '');

            $reponse = $this->returnFormFormat('新建房间', $this->getFormField());

            return view('admin/room/add', compact('reponse'));
        }

    }

    public function edit($id, Request $request, UserService $userService)
    {
        if ($request->ajax()) {
            $data = $request->input('data');
            $result = $this->room->updateData($id, $data);

            return $result ? $this->responseData(0,'','',url('admin/room/index')) : $this->responseData(9000);
        } else {

            $info = $this->room->get($id);
            $user = $userService->findById($info->user_id);
            $topId = ($user->level == 1) ? $user->id : $user->pid;

            $userList = $userService->getUserSelects(0); // 所有一级酒店
            $this->returnFieldFormat('select', '酒店', 'data[user_id]',
                $this->returnSelectFormat($userList, 'name', 'id', $topId), ['id' => 'top']
            );

            if ($user->level == 2) {
                $this->returnFieldFormat('select', '', 'data[user_id]',
                    $this->returnSelectFormat([$user->toArray()], 'name', 'id', $user->id),
                    ['id' => 'sub']
                );
            }

            $this->returnFieldFormat('text', '房号', 'data[num]', '', ['dateType' => 'n']);
            $this->returnFieldFormat('text', '房间名称', 'data[name]', '');

            $reponse = $this->returnFormFormat('新建房间', $this->getFormField());

            return view('admin/room/edit', compact('reponse'));
        }

    }

    public function del(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            $ids = explode(",", $ids);
        }

        $results = $this->room->delData($ids);
        return $results ? $this->responseData(0, "操作成功", $results) : $this->responseData(200, "操作失败");
    }


}