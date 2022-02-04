<?php
/**
 * @filesource modules/repair/controllers/receive.php
 */

namespace Repair\Receive;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=repair-receive
 *
 */
class Controller extends \Gcms\Controller
{
    /**
     * เพิ่ม-แก้ไข ใบรับงาน
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // สมาชิก
        $login = Login::isMember();
        // อ่านข้อมูลรายการที่ต้องการ
        $index = \Repair\Receive\Model::get($request->request('id')->toInt());
        // ข้อความ title bar
        $this->title = Language::get($index->id == 0 ? 'Get a Job' : 'job description');
        // เลือกเมนู
        $this->menu = 'booking';
        // ใหม่, ตัวเอง, เจ้าหน้าที่
        if ($login && ($index->id == 0 || $login['id'] == $index->customer_id || Login::checkPermission($login, 'can_manage_car_booking'))) {
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-tools">{LNG_Booking jobs}</span></li>');
            $ul->appendChild('<li><a href="{BACKURL?module=repair-history}">{LNG_History}</a></li>');
            $ul->appendChild('<li><span>{LNG_'.($index->id == 0 ? 'Add' : 'Edit').'}</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-write">'.$this->title.'</h2>',
            ));
            // แสดงฟอร์ม
            $section->appendChild(\Repair\Receive\View::create()->render($index, $login));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
