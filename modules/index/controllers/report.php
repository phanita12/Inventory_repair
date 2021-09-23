<?php
/**
 * @filesource modules/index/controllers/report.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Report;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
//use Kotchasan\Http\Uri;
use Kotchasan\Language;
use Kotchasan\Collection;
use Kotchasan\Date;

/**
 * module=report
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * รายงาน
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        
        // ข้อความ title bar
        $this->title = Language::get('report');
        // เลือกเมนู
        $this->menu = 'report';
        // สมาชิก
        $login = Login::isMember();
        // สามารถดูรายงาน
        if (Login::checkPermission($login, array('report'))) {
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-tools">{LNG_Module}</span></li>');
           // $ul->appendChild('<li><span>{LNG_Repair}</span></li>');
            $ul->appendChild('<li><span>{LNG_report}</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-list">'.$this->title.'{LNG_Summary}</h2>',
            ));
            // แสดงฟอร์ม
            $section->appendChild(\index\Report\View::create()->render($request, $login));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }

    /**
     * แสดงเมนู report
     *
     * @param Request $request
     * @param array $login
     *
     * @return string
     */
    public function tabMenus(Request $request, $login)
    {
        // แสดงผล
        $section = Html::create('section', array(
            'class' => 'content_bg',
        ));
        // breadcrumbs
        $breadcrumbs = $section->add('div', array(
            'class' => 'breadcrumbs',
        ));
        $ul = $breadcrumbs->add('ul');
        $ul->appendChild('<li><span class="icon-menus">{LNG_report}</span></li>');
        $section->add('header', array(
            'innerHTML' => '<h2 class="icon-report">'.$this->title.'{LNG_Summary}</h2>',
        ));
        // menu
        $section->appendChild(\Index\Tabmenus\View::render($request, 'report', 'report'));
        // คืนค่า HTML
        return $section->render();
    }


   
   
}
