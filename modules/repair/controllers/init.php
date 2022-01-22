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

        /*$permissions['can_manage_repair'] = '{LNG_Can manage repair}';
        $permissions['can_repair'] = '{LNG_Technical Service jobs}';
        $permissions['approve_repair'] = '{LNG_Can approve manage repair}';
        $permissions['can_manage_customer'] = '{LNG_Customer list}';
        $permissions['report'] = '{LNG_report}';
        //ส่วน Booking Room
        $permissions['can_manage_room'] = '{LNG_Can manage room}';
        $permissions['can_approve_room'] = '{LNG_Can be approve} {LNG_Room}';*/

         //**********Repair************** */
        //เมนูจัดการงาน
        $permissions['can_manage_repair'] = '{LNG_Can manage repair}';  
         //เมนูอนุมัติงาน
         $permissions['approve_repair'] = '{LNG_Can approve manage repair} '; 
         
          //**********Sum********************** */
         //เมนูแจ้งงาน
         $permissions['can_repair'] = '{LNG_New Job}';
         //เมนูรายงาน
         $permissions['report'] = '{LNG_report}';

         //**********Technical************** */
         //เมนูจัดการลูกค้า
         $permissions['can_manage_customer'] = '{LNG_Customer list} ({LNG_Technical Service system})'; 
        //เมนูจัดการงาน
        $permissions['can_manage_technical'] = '{LNG_Can manage the} {LNG_Technical Service jobs} ({LNG_Technical Service system})';  //$permissions['can_manage_repair']
       // $permissions['can_approve_manage_repair'] = '{LNG_Can approve manage repair}';
        //การส่งอีเมล
        $permissions['send_email'] = '{LNG_Emailing} ({LNG_Technical Service system})';
        $permissions['send_email_cc'] = '{LNG_Emailing} {LNG_email_cc} ({LNG_Technical Service system})';

         //**********Room************** */
        //ส่วน Booking Room
        $permissions['can_manage_room'] = '{LNG_Can manage room} ({LNG_Room})';
        $permissions['can_approve_room'] = '{LNG_Can be approve} ({LNG_Room})';

        return $permissions;
    }
}
