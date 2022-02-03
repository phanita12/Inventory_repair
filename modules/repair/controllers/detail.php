<?php
/**
 * @filesource modules/repair/controllers/detail.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Detail;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;
use Gcms\Config;

/**
 * module=repair-detail
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * รายละเอียดการซ่อม
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // อ่านข้อมูลรายการที่ต้องการ
        $index = \Repair\Detail\Model::get($request->request('id')->toInt());
        // ข้อความ title bar
        $this->title = Language::get('job description');
        // เลือกเมนู
        $this->menu = 'booking';
        // สมาชิก
        $login = Login::isMember();
        // ผู้ส่งซ่อม หรือ สามารถรับเครื่องซ่อมได้
        if ($index && ($login['id'] == $index->customer_id || Login::checkPermission($login, array('can_manage_car_booking', 'can_repair')) || Login::checkPermission($login, array('approve_manage_repair', 'approve_repair')) )) {
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            // โหลด config
            $config = Config::load(ROOT_PATH.'settings/config.php');
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-tools">{LNG_List of} {LNG_approve_wait}</span></li>');
            $ul->appendChild('<li><a href="{BACKURL?module=repair-setup&id=0}">{LNG_Booking jobs}</a></li>');
            $ul->appendChild('<li><span>'.$index->topic.'</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-write">'.$this->title.'</h2>',
            ));
            // แสดงฟอร์ม
            $section->appendChild(\Repair\Detail\View::create()->render($index, $login));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
