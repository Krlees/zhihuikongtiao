<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use App\Http\Requests;
use Gregwar\Captcha\CaptchaBuilder;


class UploadsController extends Controller{

    public function index(Request $request)
    {
        $file = $request->file('file');

        // 文件是否上传成功
        if ($file->isValid()) {

            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg


            // 上传文件
            $filename = date('Ymd') . '-' . uniqid() . '.' . $ext;

            $bool = Storage::disk('local')->put($filename, file_get_contents($realPath));
            if( $bool ){
                return $this->reponseData(0,'uoloads is success',['filename'=>$filename]);
            }

        }

        return $this->reponseData(200,'file is fail upload');
    }

}