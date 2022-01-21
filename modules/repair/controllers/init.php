<?php
/**
 * @filesource modules/repair/controllers/init.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Init;

/**
 * Init Module
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{
    /**
     * รายการ permission ของโมดูล
     *
     * @param array $permissions
     *
     * @return array
     */
    public static function updatePermissions($permissions)
    {
       /* $permissions['can_manage_repair'] = '{LNG_Can manage repair}';
        $permissions['can_repair'] = '{LNG_Repairman}';
        $permissions['approve_manage_repair'] = '{LNG_Can approve manage repair}';
      //  $permissions['approve_repair'] = '{LNG_Can approve manage repair}';
        $permissions['report'] = '{LNG_report}';*/

        $permissions['can_manage_repair'] = '{LNG_Can manage repair}';
        $permissions['can_repair'] = '{LNG_Technical Service jobs}';
       // $permissions['can_approve_manage_repair'] = '{LNG_Can approve manage repair}';
       $permissions['approve_repair'] = '{LNG_Can approve manage repair}';
        $permissions['can_manage_customer'] = '{LNG_Customer list}';
        $permissions['report'] = '{LNG_report}';
        //ส่วน Booking Room
        $permissions['can_manage_room'] = '{LNG_Can manage room}';
        $permissions['can_approve_room'] = '{LNG_Can be approve} {LNG_Room}';
        return $permissions;
    }
}
