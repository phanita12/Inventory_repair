<?php
/**
 * @filesource modules/index/controllers/reportgraph.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace index\reportgraph;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=reportg
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * รายงานรูปแบบกราฟ
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        var_dump('AAAA');
        // ข้อความ title bar
        $this->title = Language::trans('{LNG_Report} {LNG_Blood Pressure}');
        // เลือกเมนู
        $this->menu = 'report';
        // สมาชิก
        $login = Login::isMember();
        // สมาชิกที่เลือก
       /*$profile = \Bp\Profile\Model::get($request->request('id')->toInt(), $login);
        if ($profile) {
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><a href="'.WEB_URL.'index.php" class="icon-heart">{LNG_Blood Pressure}</a></li>');
            $ul->appendChild('<li><a href="index.php?module=bp&amp;id='.$profile->id.'">'.$profile->name.'</a></li>');
            $ul->appendChild('<li><span>{LNG_Report}</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-stats">'.$this->title.'</h2>',
            ));
            // แสดงกราฟ
            $section->appendChild(\Bp\Report\View::create()->render($request, $profile));
            // คืนค่า HTML
            return $section->render();
        }*/
        // 404
      //  return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
