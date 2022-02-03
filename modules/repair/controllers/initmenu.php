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
        /*$submenus = array(
            array(
                'text' => '{LNG_Get a Job}',
                'url' => 'index.php?module=repair-receive',
            ),
            array(
                'text' => '{LNG_History}',
                'url' => 'index.php?module=repair-history',
            ),
        );*/
            $submenus = array();
           // สามารถจัดการรายการซ่อมได้, ช่างซ่อม
           if (Login::checkPermission($login, array( 'can_repair'))) {
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
        } 
        // อนุมัติรายการซ่อม 
        if (Login::checkPermission($login, array( 'approve_repair'))) {
            $submenus[] = array(
                'text' => '{LNG_Booking list} ({LNG_approve_wait})',
                'url' => 'index.php?module=repair-approve',
            );
        }
        // สามารถจัดการรายการซ่อมได้, ช่างซ่อม
<<<<<<< HEAD
        if (Login::checkPermission($login, array('can_manage_car_booking', 'can_repair'))) {
=======
        if (Login::checkPermission($login, array('can_manage_repair'))) { //, 'can_repair'
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
            $submenus[] = array(
                'text' => '{LNG_Booking list} ({LNG_all items})', 
                'url' => 'index.php?module=repair-setup',
            );
        } 

        // เมนูแจ้งซ่อม
<<<<<<< HEAD
        $menu->add('repair', '{LNG_Booking jobs}', null, $submenus);
        $menu->addTopLvlMenu('repair', '{LNG_Booking jobs}', null, $submenus, 'member');
=======
        $menu->add('repair', '{LNG_Repair jobs}', null, $submenus);
        $menu->addTopLvlMenu('repair', '{LNG_Repair jobs}', null, $submenus, 'member');
        

>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
        // สามารถตั้งค่าระบบได้
        if (Login::checkPermission($login, 'can_config')) {
            $menu->add('settings', '{LNG_Booking}', null, array(
                array(
                    'text' => '{LNG_Module settings}',
                    'url' => 'index.php?module=repair-settings',
                ),
                array(
                    'text' => '{LNG_Task status}',
                    'url' => 'index.php?module=repair-repairstatus',
                ),
            ), 'repair');
        }
         // จุดประสงค์ 
         if (Login::checkPermission($login,'can_manage_inventory')) {
            $menu->add('types', '{nage}  {LNG_List of} {LNG_Inventory}',  'index.php?module=inventory-setup',null   );
            $menu->addTopLvlMenu('types', ' {LNG_List of} {LNG_Inventory}',  'index.php?module=inventory-setup',null   );
        }

        // รายงาน 
        if (Login::checkPermission($login, array('report_car_booking'))) {
            $submenus2 = array(
                    array(
                        'text' => '{LNG_Summary}',
                        'url' => 'index.php?module=report',
                        ),
                     array(
                        'text' => '{LNG_Graph-report}',
                        'url' => 'index.php?module=reportg',
                         ),
            );
            // เมนูรายงาน
            $menu->add('reportG', '{LNG_report}', null, $submenus2);
            $menu->addTopLvlMenu('reportG', '{LNG_report}', null, $submenus2, 'report');
        }
    }
}
