<?php

// +----------------------------------------------------------------------
// | 前海类库
// +----------------------------------------------------------------------
// | @Authoer Krlee
// +----------------------------------------------------------------------

namespace App\Services\Admin;


use Illuminate\Support\Facades\Config;

class QianhaiService
{
    //这是同步命令
    public $sync_cmd = array(255, 255, 12, 96, 11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

    public $mPower = 0x00;//开关
    public $mTemperature = 0x19;//湿度
    public $mWindRate = 0x01; // 01 02 03 04 // 风速
    public $mWindDirection = 0x02; // 01 02 03 // 风向
    public $mAutomaticWindDirection = 0x01; // 00 //自动风向
    public $mMode = 0x01; // 0x01 ~ 0x05 //模式
    public $mKey = 0x01; // 键名

    public $mState = 0x00; // 状态
    public $checksum = 0;

    public function __construct()
    {

    }

    //这里需要处理服务器码库收到的字符串编码为数组
    //private buf[]
    public function setAirirencode($buf)
    {
        $this->sync_data();
        $this->SetKey(0);
        $this->checksum = 0;
        $buf[4] = $this->mTemperature;
        $this->checksum += $buf[4];
        $buf[5] = $this->mWindRate;
        $this->checksum += $buf[5];
        $buf[6] = $this->mWindDirection;
        $this->checksum += $buf[6];
        $buf[7] = $this->mAutomaticWindDirection;
        $this->checksum += $buf[7];
        $buf[8] = $this->mPower;
        $this->checksum += $buf[8];
        $buf[9] = $this->mKey;
        $this->checksum += $buf[9];
        $buf[10] = $this->mMode;
        $this->checksum += $buf[10];
        $len = count($buf);
        $buf[$len - 1] = $buf[$len - 1] + $this->checksum;
        //$buf[$len - 1] = intval(substr($buf[$len - 1], 0, 1));
        $buf[$len - 1] = $buf[$len - 1] % 255;
        return $buf;
    }

    public function ReloadAirKeyValue($parentbuf)
    {
        // TODO Auto-generated method stub
        $childparabuf = array(25, 1, 2, 1, 1, 2, 1);
        $totalbuf = array();
        for ($i = 0; $i < count($parentbuf); $i++) {
            $totalbuf[$i] = $parentbuf[$i];
        }
        $this->checksum = 0;
        for ($i = 0; $i < count($childparabuf); $i++) {
            $totalbuf[4 + $i] = $childparabuf[$i];
            $this->checksum += $childparabuf[$i];
        }
        $len = count($parentbuf);
        $totalbuf[$len - 1] += $this->checksum;
        //$totalbuf[$len - 1] = intval(substr($totalbuf[$len - 1], 0, 1));
        $totalbuf[$len - 1] = $totalbuf[$len - 1] % 255;

        return $totalbuf;
    }

    public function SetKey($_key)
    {
//	    $this->mKey = $_key & 0x000000FF;
//	    if ($_key == KEY_AIR_POWER)
//	    {
        if ($this->mState == 0x00) {
            if ($this->mPower == 0x00) {
                $this->mPower = 0x01;
            } else {
                $this->mPower = 0x00;
            }
        } else {
            $this->mPower = 0x01;
        }
    }

    /**
     * 处理数据
     * @Author Krlee
     *
     * @param bool $data
     */
    public function sync_data($data = false)
    {
        if (empty($data))
            $data = session('RAW_SMARTHOME'); //读取第一次的状态数据

        if (empty($data)) {
            $default = array(25, 1, 2, 1, 1, 2, 1);
            $data = array();
            for ($i = 22; $i <= 28; $i++) {
                $data[$i] = $default[$i - 22];
            }
        }

        $this->mTemperature = $data[22];
        $this->mWindRate = $data[23];
        $this->mWindDirection = $data[24];
        $this->mAutomaticWindDirection = $data[25];
        $this->mPower = $data[26];
        $this->mKey = $data[27];
        $this->mMode = $data[28];

    }

    /**
     * 生成指令
     * @Author Krlee
     *
     * @param string $ele_id 设备类型
     * @param string $control 控制数字（int）
     * @param int $seqnum 消息序号
     * @param array $data 码库数据
     * @param int $keyindex
     * @return array
     */
    public function build_cmd($ele_id = '49152', $control = '', $seqnum = 0, $data = array(), $keyindex = 14)
    {
        $cmd = array(255, 255, 11, $control, 0, 0, 0, 0, 0, 0, 0, 0, $seqnum, 0);
        // 256位
        for ($i = 0; $i < 256; $i++) {
            if (count($cmd) >= 256) break;
            $cmd[] = 0;
        }

        if (!empty($data)) {
            if ($ele_id == '49152') {
                $data = $this->setAirirencode($data);
            }
            $cmd[2] = 11 + count($data);
            for ($i = 0; $i < count($data); $i++) {
                if ($keyindex + $i > 256)
                    break;
                $cmd[$keyindex + $i] = $data[$i];
            }
        }
        return $cmd;
    }


    /**
     * 获取前海自定义的key
     * @Author Krlee
     *
     * @param $ele_id
     * @param $index
     * @return array
     */
    public function get_custom_key($ele_id, $index)
    {
        $keys = array_get(Config::get('gizwits.electrical'), $ele_id);
        if (!$keys) {
            return;
        }

        // 空调
        if ($ele_id == '49152') {


            if ($index > 0) $this->mPower = 1;

            // 初始值
            $result = array(
                $this->mTemperature,
                $this->mWindRate,
                $this->mWindDirection,
                $this->mAutomaticWindDirection,
                $this->mPower,
                $index + 1,
                $this->mMode
            );

            $cValue = 0;
            switch ($index) {
                case 0:
                    $cValue = $this->mPower;
                    break;
                case 1:
                    $cValue = $this->mMode;
                    break;
                case 2:
                    $cValue = $this->mWindRate;
                    break;
                case 3:
                    $cValue = $this->mWindDirection;
                    break;
                case 4:
                    $cValue = $this->mAutomaticWindDirection;
                    break;
                case 5:
                    $cValue = $this->mTemperature;
                    break;
                case 6:
                    $cValue = $this->mTemperature;
                    break;
            }

            $action = $keys[$index]['action'];
            if ($action) {
                eval("\$nValue=\$cValue" . $action . $keys[$index]['custom'][0] . ";");
                $result[$keys[$index]['index']] = $cValue;
            } else {
                $len = count($keys[$index]['custom']);
                if ($cValue >= $keys[$index]['custom'][$len - 1]) {
                    $result[$keys[$index]['index']] = $keys[$index]['custom'][0];
                } else {
                    $k = -1;
                    foreach ($keys[$index]['custom'] as $key => $val) {
                        if ($cValue == $val) {
                            $k = $key;
                            break;
                        }
                    }
                    $result[$keys[$index]['index']] = $keys[$index]['custom'][$k + 1];
                }
            }

            if (empty($result[1])) $result[1] = 1;
            if (empty($result[2])) $result[2] = 1;
            if (empty($result[3])) $result[3] = 1;
            if ($index == 5 || $index == 6) {
                if ($result[0] < 19)
                    $result[0] = 19;
                if ($result[0] > 30)
                    $result[0] = 30;
            }

            return $result;
        }

        return array($keys[$index]['custom']);
    }

    /**
     * 获取设备类型
     * @Author Krlee
     *
     * @param $typenum
     * @return mixed
     */
    public function get_device_type($typenum)
    {
        $device_type = Config::get('gizwits.device_type');
        foreach ($device_type as $v) {
            if ($v[2] == $typenum)
                return $v;
        }
    }

    /**
     * 检测发送指令的长度
     * @Author Krlee
     *
     * @param $cmd     命令
     * @param $length  指令长度
     * @return mixed
     */
    public function check_sum($cmd, $length)
    {
        $chechsum = 0;
        for ($i = 0; $i < $length + 1; $i++) {
            $chechsum += $cmd[2 + $i];
        }

        $cmd[$length + 3] = $cmd[$length + 3] % 255;

        return $cmd;
    }

    /**
     * 获取前海自定义的key
     * @Author Krlee
     *
     * @param $ele_id
     * @param $index
     * @return array
     */
    public function getAirCmd($airCmd = [])
    {
        $keys = array_get(Config::get('gizwits.electrical'), '49152');
        if (!$keys) {
            return;
        }

        if (empty($airCmd)) {
            $airCmd = [25, 1, 1, 1, 0, 1, 1, 0];
        }

        $control = 16; // 控制字
        $seqnum = 1;   // 消息序号

        $cmd = [255, 255, 11, $control, 1, 0, 0, 0, 0, 0, 0, 0, $seqnum, 0]; // 初始化14位
        $cmd = array_merge($cmd, $airCmd);

        return $this->cleanCmd($cmd);
    }

    /**
     * 过滤命令，补足长度256
     * @Author Krlee
     *
     */
    public function cleanCmd($cmd)
    {
        $count = count($cmd);
        if ($count > 256) {
            return $cmd;
        }

        $len = 256 - $count;
        for ($i = 0; $i < $len; $i++) {
            $cmd[$count + $i] = 0;
        }

        return $cmd;
    }
}