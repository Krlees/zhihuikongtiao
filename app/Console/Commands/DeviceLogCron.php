<?php

namespace App\Console\Commands;

use App\Services\Admin\QianhaiService;
use App\Traits\Admin\GizwitTraits;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use DB;

class DeviceLogCron extends Command
{
    use GizwitTraits;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(QianhaiService $qianhaiService)
    {
        //每隔一天获取历史数据
        $cfg = \Config::get('gizwits.cfg');
        $list = DB::table('device')->get();
        foreach ($list as $v) {

            $gizUsers = $this->createGizwitUser($cfg['appid'], $v->user_id);
            $res = $this->getHistoryData($cfg['appid'], $gizUsers['token'], $v->did);
            if (isset($res['error_code'])) {
                continue;
            }

            $res = json_decode($res, true);
            foreach ($res['objects'] as $k => $obj) {
                if ($obj['type'] == 'dev_online') {
                    $arr[$k]['device_id'] = $v->id;
                    $arr[$k]['all_time'] = $obj['payload']['keep_alive'];

                    $c1 = mt_rand(16, 30);
                    $c2 = mt_rand(18, 28);
                    $c3 = 16;
                    $arr[$k]['use_energy'] = round(31 * ($c1 / $c2) / ($c1 - $c3), 2);
                    $arr[$k]['date'] = date('Y-m-d H:i:s', substr($obj['timestamp'], 0, 10));
                }

            }

            try {
                DB::table('device_air_use_log')->insert($arr);
            }catch (\Exception $e){

            }
        }

        return true;
    }
}
