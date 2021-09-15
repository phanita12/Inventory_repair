<?php
/**
 * @filesource modules/repair/controllers/initmenu.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Initmenu;

use Gcms\Login;
use Kotchasan\Http\Request;

/**
 * Init Menu
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{
    /**
     * ฟังก์ชั่นเริ่มต้นการทำงานของโมดูลที่ติดตั้ง
     * และจัดการเมนูของโมดูล.
     *
     * @param Request                $request
     * @param \Index\Menu\Controller $menu
     * @param array                  $login
     */
    public static function execute(Request $request, $menu, $login)
    {
        $submenus = array(
            array(
                'text' => '{LNG_Get a repair}',
                'url' => 'index.php?module=repair-receive',
            ),
            array(
                'text' => '{LNG_History}',
                'url' => 'index.php?module=repair-history',
            ),
        );
        // อนุมัติรายการซ่อม 
        if (Login::checkPermission($login, array('approve_manage_repair', 'approve_repair'))) {
            $submenus[] = array(
                'text' => '{LNG_Repair list} ({LNG_approve_wait})',
                'url' => 'index.php?module=repair-approve',
            );
        }
        // สามารถจัดการรายการซ่อมได้, ช่างซ่อม
        if (Login::checkPermission($login, array('can_manage_repair', 'can_repair'))) {
            $submenus[] = array(
                'text' => '{LNG_Repair list} ({LNG_all items})', //LNG_Repairman
                'url' => 'index.php?module=repair-setup',
            );
        } 
        // เมนูแจ้งซ่อม
        $menu->add('repair', '{LNG_Repair jobs}', null, $submenus);
        $menu->addTopLvlMenu('repair', '{LNG_Repair jobs}', null, $submenus, 'member');
        // สามารถตั้งค่าระบบได้
        if (Login::checkPermission($login, 'can_config')) {
            $menu->add('settings', '{LNG_Repair}', null, array(
                array(
                    'text' => '{LNG_Module settings}',
                    'url' => 'index.php?module=repair-settings',
                ),
                array(
                    'text' => '{LNG_Repair status}',
                    'url' => 'index.php?module=repair-repairstatus',
                ),
            ), 'repair');
        }

        // รายงาน 
        if (Login::checkPermission($login, array('can_manage_repair', 'can_repair','report'))) {
            $submenus[] = array(
                'text' => '{LNG_Graph monthly report})',
                'url' => 'index.php?module=report',
            );
            // เมนูรายงาน
            $menu->add('report', '{LNG_report}{LNG_Summary}', null, $submenus);
        }
        
        //เมนูรายงานกราฟแสดงจำนวนการแจ้งซ่อม แจกแจงตามรายเดือน
       // $menu->addTopLvlMenu('repair', '{LNG_report}{LNG_Graph monthly report}', null, $submenus, 'report');
    }
}
