<?php
/**
 * @filesource modules/repair/models/settings.php
 *
 */

namespace Repair\Settings;

use Gcms\Config;
use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=repair-settings
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{
    /**
     * รับค่าจาก settings.php
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // session, token, can_config, ไม่ใช่สมาชิกตัวอย่าง
        if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
            if (Login::notDemoMode($login) && Login::checkPermission($login, 'can_config')) {
                // โหลด config
                $config = Config::load(ROOT_PATH.'settings/config.php');
                $config->repair_first_status = $request->post('repair_first_status')->toInt();
                $config->repair_job_no = $request->post('repair_job_no')->topic();
                // save config
                if (Config::save($config, ROOT_PATH.'settings/config.php')) {
                    // คืนค่า
                    $ret['alert'] = Language::get('Saved successfully');
                    $ret['location'] = 'reload';
                    // เคลียร์
                    $request->removeToken();
                } else {
                    // ไม่สามารถบันทึก config ได้
                    $ret['alert'] = sprintf(Language::get('File %s cannot be created or is read-only.'), 'settings/config.php');
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่าเป็น JSON
        echo json_encode($ret);
    }
}
