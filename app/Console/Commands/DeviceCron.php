<?php

namespace App\Console\Commands;

use App\Services\Admin\QianhaiService;
use App\Traits\Admin\GizwitTraits;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class DeviceCron extends Command
{
    use GizwitTraits;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:sendgiz';

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
        //每隔一小时发送远程控制命令到机智云
        $gizwitsCfg = Config::get('gizwits.cfg');
        $nowHour = date('H');
        $cron = Cache::store('file')->get('device_cron_' . date('Y-m-d'));

        if( !$cron ) {
            return true;
        }

        foreach ($cron as $k => $v) {
            if ($v['user_id']) {

                // 1. 获取token
                $result = $this->createGizwitUser($gizwitsCfg['appid'], $v['user_id']);
                if (isset($result['error_code'])) {
                    return false;
                }

                if ($nowHour == $k) {
                    // 开启命令
                    $v['cmd']['4'] = 1;
                } else {
                    // 关闭命令
                    $v['cmd']['4'] = 0;
                }

                $cmd = json_decode($v['cmd']);
                $cmd = $qianhaiService->getAirCmd($cmd);


                $response = $this->sendControlGiz($gizwitsCfg['appid'], $result['token'], $v['did'], $cmd);
                dd($response);

            }
        }

        return true;
    }
}
