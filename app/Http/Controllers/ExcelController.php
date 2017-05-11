<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Excel;

class ExcelController extends Controller
{
    //Excel文件导出功能 By Laravel学院
    public function export()
    {
        $cellData = [
            ['学号', '姓名', '成绩'],
            ['10001', 'AAAAA', '99'],
            ['10002', 'BBBBB', '92'],
            ['10003', 'CCCCC', '95'],
            ['10004', 'DDDDD', '89'],
            ['10005', 'EEEEE', '96'],
        ];
        Excel::create('学生成绩', function ($excel) use ($cellData) {
            $excel->sheet('score', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    //Excel文件导入功能 By Laravel学院
    public function import()
    {

        $filePath =  storage_path() . '/kk.xls';
        Excel::load($filePath, function ($reader) {
            $data = $reader->all();
            dd($data);
        });

    }
}