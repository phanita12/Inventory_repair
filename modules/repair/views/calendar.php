<?php
/**
 * @filesource modules/repair/views/calendar.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Calendar;


use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Date;

/**
 * module=repair-calendar
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * ตัวอย่างฟอร์ม
     *
     * @return string
     */
    public function render(Request $request)
    {

        /* คำสั่งสร้างฟอร์ม */
            $form = Html::create('div', array(
                'class' => 'setup_frm', 
                //'style' => 'margin-top:-60.5%',
            ));
            $form->add('div', array(
                'id' => 'calendarx',
                'class' => 'padding-left-right-bottom-top',
            ));

            //get data
            $datas = \Repair\Home\Model::getbooking();
            foreach($datas as $k => $item){  
                //check type request
                    foreach (self::$cfg->type_request as $key => $value) {
                        if($item['types_objective'] == $key){
                            $types_objective = $value;
                            if( $types_objective == "อื่นๆ (โปรดระบุลงในหมายเหตุ)"){
                                $types_objective = 'อื่นๆ';
                            }
                        }    
                    }
                //check color
                    // if($item['id'] %2 ==0){$color = '#060';}else{$color = '#6633FF';} 
                    foreach (self::$cfg->color_status as $i => $temp) {
                            if($item['status'] == $i){
                                $scolor = $temp; 
                                
                            }    
                        }  
                    $events[$k] = 
                        array(
                           // 'title' =>  $item['product_no'].' / '.$types_objective,
                           'title' =>  $item['product_no'].' ('.Date::format( $item['begin_date'], 'H:i').'-'.Date::format( $item['end_date'], 'H:i'.')'.' / '.$types_objective),
                            'start' =>  $item['begin_date'],
                            'end' => $item['end_date'],
                            'color' =>  $scolor ,
                        );
            }
 
        /* Javascript สำหรับ Calendar */
            $form->script('new Calendar("calendarx", {month: 12, year: 2021,  showButton: false}).setEvents('.json_encode($events).');'); //onclick: doEventClick,
          
        /* คืนค่า HTML */
            return $form->render();
    }
}

