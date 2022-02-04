<?php

/**
 * @filesource modules/repair/controllers/home.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Home;


//use Gcms\Login;
use Kotchasan\Http\Request;
use Index\Home\Controller as Home;


/**
 * Controller สำหรับการแสดงผลหน้า Home.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{


    /**
     * ฟังก์ชั่นสร้าง card แบบกล่องจำนวน
     *
     * @param Request         $request
     * @param \Kotchasan\Html $card
     * @param array           $login
     */
    public static function addCard(Request $request, $card, $login)
    {
        $arr = array('0');
<<<<<<< HEAD
        $datas = \Repair\Home\Model::getNew($login ,  $arr);
      /*  $datas2 = \Repair\Home\Model::getNew3($login);
=======
       
        $datas = \Repair\Home\Model::getNew($login ); //,  $arr
      
        $datas2 = \Repair\Home\Model::getNew3($login);
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
        $datas_close = \Repair\Home\Model::getStatusclose($login);
        $datas_cancel = \Repair\Home\Model::getStatuscancel($login);
        $datas_waitParts = \Repair\Home\Model::getStatuswaitParts($login);*/
        $datas_Alltoday = \Repair\Home\Model::getAlltoday($login);
        $datas_Sendapprove = \Repair\Home\Model::getSendapprove($login);
        $datas_Sendapprove2 = \Repair\Home\Model::getSendapprove2($login);
       $datas_Approve = \Repair\Home\Model::getApprove($login);
       $datas_Ready = \Repair\Home\Model::getReady($login);
        $datas_NoneApprove = \Repair\Home\Model::getNoneApprove($login);
<<<<<<< HEAD

       if ($datas->isStaff) {
             
            Home::renderCard($card, 'icon-calendar', '{LNG_Date}', \Kotchasan\Date::format(time(), 'd M'), '{LNG_today}', 'index.php');  
           // Home::renderCard($card, 'icon-calendar', '{LNG_Booking list}', number_format($datas_Alltoday->count), '{LNG_Per Month}', 'index.php?module=repair-setup');  
            Home::renderCard($card, 'icon-verfied', '{LNG_Booking list}', number_format($datas_Ready->count), '{LNG_Ready to use}', 'index.php?module=repair-setup&amp;status=2');

            Home::renderCard($card, 'icon-new', '{LNG_Booking list}', number_format($datas_Approve->count), '{LNG_pending}', 'index.php?module=repair-setup&amp;status=9'); //. (isset(self::$cfg->repair_first_status) ? '&amp;status=' . self::$cfg->repair_first_status : ''));  $datas2->count
            Home::renderCard($card, 'icon-clock', '{LNG_Booking list}', number_format($datas_Sendapprove->count), '{LNG_approve_wait}', 'index.php?module=repair-setup&amp;status=8');
           
          //  Home::renderCard($card, 'icon-close', '{LNG_Booking list}', number_format($datas_NoneApprove->count), '{LNG_Disapproved}', 'index.php?module=repair-setup&amp;status=10');
           
           /* Home::renderCard($card, 'icon-valid', '{LNG_Booking list}', number_format($datas_close->count), '{LNG_Closejob}', 'index.php?module=repair-setup&amp;status=7');
            Home::renderCard($card, 'icon-invalid', '{LNG_Booking list}', number_format($datas_cancel->count), '{LNG_Canceljob}', 'index.php?module=repair-setup&amp;status=6');  
            Home::renderCard($card, 'icon-compare', '{LNG_Booking list}', number_format($datas_waitParts->count), '{LNG_WaitParts}', 'index.php?module=repair-setup&amp;status=3');*/
        } else {
            Home::renderCard($card, 'icon-calendar', '{LNG_Date}', \Kotchasan\Date::format(time(), 'd M'), '{LNG_today}', 'index.php');  
            Home::renderCard($card, 'icon-verfied', '{LNG_Booking list}', number_format($datas_Ready->count), '{LNG_Ready to use}', 'index.php?module=repair-setup&amp;status=2');
            Home::renderCard($card, 'icon-users', '{LNG_Booking list}', number_format($datas->count), '{LNG_Job today}', 'index.php?module=repair-history');
            Home::renderCard($card, 'icon-clock', '{LNG_Booking list}', number_format($datas_Sendapprove2->count), '{LNG_approve_wait}', 'index.php?module=repair-approve&amp;status=8');
            Home::renderCard($card, 'icon-tags', '{LNG_Booking list}', number_format($datas_Alltoday->count), '{LNG_Per Month}', 'index.php?module=repair-history');
=======
        if ($datas->isStaff) {
            \Index\Home\Controller::renderCard($card, 'icon-calendar', '{LNG_Repair list}', number_format($datas_Alltoday->count), '{LNG_Per Month}', 'index.php?module=repair-setup'); // '{LNG_Job today}
            \Index\Home\Controller::renderCard($card, 'icon-new', '{LNG_Repair list}', number_format($datas2->count), '{LNG_pending}', 'index.php?module=repair-setup' . (isset(self::$cfg->repair_first_status) ? '&amp;status=' . self::$cfg->repair_first_status : ''));
            \Index\Home\Controller::renderCard($card, 'icon-clock', '{LNG_Repair list}', number_format($datas_Sendapprove->count), '{LNG_approve_wait}', 'index.php?module=repair-setup&amp;status=8');
            \Index\Home\Controller::renderCard($card, 'icon-verfied', '{LNG_Repair list}', number_format($datas_Approve->count), '{LNG_Approved}', 'index.php?module=repair-setup&amp;status=9');
            \Index\Home\Controller::renderCard($card, 'icon-close', '{LNG_Repair list}', number_format($datas_NoneApprove->count), '{LNG_Disapproved}', 'index.php?module=repair-setup&amp;status=10');
            \Index\Home\Controller::renderCard($card, 'icon-valid', '{LNG_Repair list}', number_format($datas_close->count), '{LNG_Closejob}', 'index.php?module=repair-setup&amp;status=7');
            \Index\Home\Controller::renderCard($card, 'icon-invalid', '{LNG_Repair list}', number_format($datas_cancel->count), '{LNG_Canceljob}', 'index.php?module=repair-setup&amp;status=6');  
            \Index\Home\Controller::renderCard($card, 'icon-compare', '{LNG_Repair list}', number_format($datas_waitParts->count), '{LNG_WaitParts}', 'index.php?module=repair-setup&amp;status=3');
        } else if (Login::checkPermission($login, array( 'can_repair','approve_repair'))) {
            \Index\Home\Controller::renderCard($card, 'icon-calendar', '{LNG_Date}', \Kotchasan\Date::format(time(), 'd M'), '{LNG_today}', 'index.php');
            \Index\Home\Controller::renderCard($card, 'icon-tags', '{LNG_Repair list}', number_format($datas_Alltoday->count), '{LNG_Per Month}', 'index.php?module=repair-history');
            \Index\Home\Controller::renderCard($card, 'icon-users', '{LNG_Repair list}', number_format($datas->count), '{LNG_Job today}', 'index.php?module=repair-history');
            \Index\Home\Controller::renderCard($card, 'icon-clock', '{LNG_Repair list}', number_format($datas_Sendapprove2->count), '{LNG_approve_wait}', 'index.php?module=repair-approve&amp;status=8');
        } else if (Login::checkPermission($login, array( 'can_repair'))) {
            \Index\Home\Controller::renderCard($card, 'icon-calendar', '{LNG_Date}', \Kotchasan\Date::format(time(), 'd M'), '{LNG_today}', 'index.php');
            \Index\Home\Controller::renderCard($card, 'icon-tags', '{LNG_Repair list}', number_format($datas_Alltoday->count), '{LNG_Per Month}', 'index.php?module=repair-history');
            \Index\Home\Controller::renderCard($card, 'icon-users', '{LNG_Repair list}', number_format($datas->count), '{LNG_Job today}', 'index.php?module=repair-history');
        }else{
            \Index\Home\Controller::renderCard($card, 'icon-calendar', '{LNG_Date}', \Kotchasan\Date::format(time(), 'd M'), '{LNG_today}', 'index.php');
        }
    }

    //ส่วนแสดงกราฟ
    public static function addBlock(Request $request, $card, $login)
    {       

       $datas = \Repair\Home\Model::getNew($login);
        $data_monthly = \Repair\Home\Model::get_monthly($login);
        if ($datas->isStaff) {
            \Index\Home\Controller::renderCard2($card,  $data_monthly, '{LNG_Graph monthly report}', '{LNG_Month}', '{LNG_Repair} {LNG_all items}'); 
              
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
        }
    }


  /**
     * ฟังก์ชั่นสร้าง block
     *
     * @param Request $request
     * @param Collection $block
     * @param array $login
     */
    public static function addBlock(Request $request, $block, $login)
    {
        $content = \Repair\Calendar\View::create()->render($request);
        $block->set('Car calendar', $content);
    }
    

}
