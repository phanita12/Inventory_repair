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
        // สามารถจัดการรายการซ่อมได้, ช่างซ่อม
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


    public static function renderCard2($card, $value ,$title,$headtitle,$bodytitle) 
    {		

      $content2 = '<br><section class=clear>
        <h4>'.$title.'</h4>
        <div id="table2" class="graphcs">
          <canvas></canvas>
          <table class="hidden">
            <thead>
              <tr>
                <th> '.$headtitle.'</th>
                <th>'.Date::monthName(1).'</th>
                <th>'.Date::monthName(2).'</th>
                <th>'.Date::monthName(3).'</th>
                <th>'.Date::monthName(4).'</th>
                <th>'.Date::monthName(5).'</th>
                <th>'.Date::monthName(6).'</th>
                <th>'.Date::monthName(7).'</th>
                <th>'.Date::monthName(8).'</th>
                <th>'.Date::monthName(9).'</th>
                <th>'.Date::monthName(10).'</th>
                <th>'.Date::monthName(11).'</th>
                <th>'.Date::monthName(12).'</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th> '.$bodytitle.'</th>
                <td>'.$value[0]['1'].'</td>
                <td>'.$value[0]['2'].'</td>
                <td>'.$value[0]['3'].'</td>
                <td>'.$value[0]['4'].'</td>
                <td>'.$value[0]['5'].'</td>
                <td>'.$value[0]['6'].'</td>
                <td>'.$value[0]['7'].'</td>
                <td>'.$value[0]['8'].'</td>
                <td>'.$value[0]['9'].'</td>
                <td>'.$value[0]['10'].'</td>
                <td>'.$value[0]['11'].'</td>
                <td>'.$value[0]['12'].'</td>
              </tr>'
              /* ต้องกลับมาเขียนเพิ่มถ้าข้ามปี
              <tr>
                <th> '.$bodytitle.'</th>
                <td>'.$value[1]['1'].'</td>
                <td>'.$value[2]['2'].'</td>
                <td>'.$value[3]['3'].'</td>
                <td>'.$value[4]['4'].'</td>
                <td>'.$value[5]['5'].'</td>
                <td>'.$value[6]['6'].'</td>
                <td>'.$value[7]['7'].'</td>
                <td>'.$value[8]['8'].'</td>
                <td>'.$value[9]['9'].'</td>
                <td>'.$value[10]['10'].'</td>
                <td>'.$value[11]['11'].'</td>
                <td>'.$value[12]['12'].'</td>
              </tr>*/
              .'
            </tbody>
          </table>
        </div>
        <script>
          new GGraphs("table2", {
            type: "bar",
            colors: [
              "#7E57C2",
              "#FF5722",
            ]
          });
        </script>
      </section>';
          $card->set(\Kotchasan\Password::uniqid(), $content2); 

          
    }
   
}
