<?php
/**
 * @filesource modules/index/views/reportg.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Reportg;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Date;


/**
 * module=reportg
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * รายงานกราฟแสดงการแจ้งซ่อม
     *
     * @param Request $request
     * @param array   $login
     * @param object  $profile
     *
     * @return string
     */
    public function render(Request $request)
    {
        $login = Login::isMember();
        $isAdmin = Login::checkPermission($login, 'report_car_booking');
        $params = array(
            'from' => $request->request('from', date('Y-m-d', strtotime('-7 days')))->date(),
            'to' => $request->request('to', date('Y-m-d'))->date(),
        );
        // สถานะสมาชิก
            $member_status = array(-1 => '{LNG_all items}');
                if ($isAdmin) {
                    $params['member_id'] = $request->request('dash_Member', -1)->toInt();
                } 
                foreach (self::$cfg->member_status as $key => $value) {
                    $member_status[$key] = '{LNG_'.$value.'}';
                }
        $params['login_id'] = $login['id'];//$login;


        //create form search
          $form = Html::create('form', array(
            'id' => 'report_search_frm',
            'class' => 'table_nav clear',
            'action' => 'index.php?module=reportg', 
            'ajax' => false,
            'token' => false,
          ));
          $div = $form->add('div');
          $fieldset = $div->add('fieldset');
                    // from
                    $fieldset->add('date', array(
                        'id' => 'from',
                        'label' => '{LNG_Search}{LNG_from}{LNG_Begin date}', 
                        'value' => $params['from'],
                    ));
                    $fieldset = $div->add('fieldset');
                    // to
                    $fieldset->add('date', array(
                        'id' => 'to',
                        'label' => '{LNG_To}',
                        'value' => $params['to'],
                    ));
                    $fieldset = $div->add('fieldset');
                  /*  // repair_first_status
                    $fieldset->add('select', array(
                        'id' => 'dash_Member',
                        'label' => '{LNG_Member}',
                        'options' => $member_status,
                        'value' => $params['member_id'],
                    ));*/
                    $fieldset = $div->add('fieldset');
                     // submit
                        $fieldset->add('submit', array(
                            'class' => 'button go',
                            'value' => 'GO',
                        ));
                     // id
                        $fieldset->add('hidden', array(
                            'id' => 'id',
                            'value' => $login['id'],
                        ));
                        // submit
                        $fieldset->add('hidden', array(
                            'id' => 'module',
                            'value' => 'reportg',
                        ));

        /*  ----------------------------- กราฟที่ 1-------------------------------- */
                    // แสดงผล                   
                $content = '<section id=report class="setup_frm">'; 
                $content .= $form->render();
                $content .= '<article class="ggraphs clear">';
                $index =  \Repair\Home\Model::get_type($params); 
                $title = '{LNG_Graph report} {LNG_Type}';
                $list= '{LNG_List of} {LNG_Booking}' ;
                $headtitle = '{LNG_Booking}';
                $bodytitle = '{LNG_List of} {LNG_Booking}';
                    $str = ''; $str_2 = '';
                        for($i=0;$i<count($index);$i++){
                                if( ($index[0]['count']) != '0' || is_null($index[0]['count']) )  {  
                                    $str =  $str.'<th>'. $index[$i]['topic'].'</th>';
                                    $str_2 =  $str_2.'<td>'. $index[$i]['count'].'</td>';
                                }else{  $str_2 = '<td> 0 </td>';    }
                        } 
                $content .= '<br><section class=clear>
                    <h4>'.$title.' '.$list.'</h4>
                    <div id="table" class="graphcs">
                        <canvas></canvas>
                        <table class="hidden">
                        <thead>
                            <tr>
                            <th>'.$headtitle.'</th>'. $str
                        .'  </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th> '.$bodytitle.'</th>'. $str_2
                        .'</tr> 
                        </tbody>
                        </table>
                    </div>
                    <script>
                        new GGraphs("table", {
                        type: "pie",
                            centerX: 50 + Math.round($G("table").getHeight() / 2),
                            labelOffset: 70,
                            centerOffset: 60,
                            strokeColor: null,
                            colors: [
                                "#660000",
                                        "#d940ff",
                                        "#E65100",
                                        "#FF992A",
                                        "#06d628",
                                        "#009ACD",
                                        "#1B5E20",
                                        "#263238",
                                        "#120eeb",
                                        "#FF1493",
                                        "#FF9999",
                                        "#8B4513",
                                        "#9999FF",
                                        "#CC66FF",
                                        "#FF3300",
                                        "#FFA500",
                                        "#336666",
                                        "#FFDAB9",
                                        "#CD5C5C",
                                        "#FFD700",
                                        "#9400D3",
                                        "#CC6600",
                                        "#FF9933",
                                        "#FF0066",
                                        "#CC3300",
                                        "#66CCCC",
                                        "#FFB90F",
                                        "#00CC00",
                                        "#BEBEBE",
                                        "#00FF7F",
                            ]
                        });
                    </script>
                    </section>'; 

        /*  ----------------------------- กราฟที่ 2-------------------------------- */
                // แสดงผล
                $content2 = '<section id=report class="setup_frm">';
                $content2 .= '<article class="ggraphs clear">';
                $index = \Repair\Home\Model::get_category($params);
                $title = '{LNG_Graph report} {LNG_Category}';
                $list= '{LNG_List of} {LNG_Booking}' ;
                $headtitle = '{LNG_Booking}';
                $bodytitle = '{LNG_List of}{LNG_Booking}';
                    $content2 = '<br><section class=clear>
                    <h4>'.$title.' '.$list.'</h4>
                    <div id="table2" class="graphcs">
                        <canvas></canvas>
                        <table class="hidden">
                        <thead>
                            <tr>
                            <th>'.$headtitle.'</th>
                            <th>รถบริษัท</th>            
                            <th>รถรับจ้าง</th>
                            <th>รถส่วนกลาง</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th> '.$bodytitle.'</th>
                            <td>'.$index[0]['1'].'</td>
                            <td>'.$index[0]['2'].'</td>
                            <td>'.$index[0]['3'].'</td>
                            <td>'.$index[0]['4'].'</td>
                        </tbody>
                        </table>
                    </div>
                    <script>
                        new GGraphs("table2", {
                        type: "pie",
                            centerX: 50 + Math.round($G("table2").getHeight() / 2),
                            labelOffset: 70,
                            centerOffset: 60,
                            strokeColor: null,
                            colors: [
                                "#660000",
                                "#d940ff",
                                "#E65100",
                                "#FF992A",
                                "#06d628",
                                "#009ACD",
                                "#1B5E20",
                                "#263238",
                                "#120eeb",
                                "#FF1493",
                                "#FF9999",
                                "#8B4513",
                                "#9999FF",
                                "#CC66FF",
                                "#FF3300",
                                "#FFA500",
                                "#336666",
                                "#FFDAB9",
                                "#CD5C5C",
                                "#FFD700",
                                "#9400D3",
                                "#CC6600",
                                "#FF9933",
                                "#FF0066",
                                "#CC3300",
                                "#66CCCC",
                                "#FFB90F",
                                "#00CC00",
                                "#BEBEBE",
                                "#00FF7F",
                            ]
                        });
                    </script>
                    </section>';
            
        /*  ----------------------------- กราฟที่ 3-------------------------------- */
                    // แสดงผล
                    $content3 = '<section id=report class="setup_frm">';
                    $content3 .= '<article class="ggraphs clear">';
                    $index3 = array();
                    $index3 =  \Repair\Home\Model::get_group($params);    
                        // สถานะสมาชิก
                        $gmember2 = '';     $str_3 = '';
                        foreach (self::$cfg->member_status as $key => $value) {
                            $gmember2 .=  '<th>{LNG_'.$value.'}</th>';
                        }
                        for($i=0;$i<count($index3[0]);$i++){ 
                            if( ($index3[0]['0'.$i]) >= 0 ||  ($index3[0]['0'.$i]) != null)  {  
                                $str_3 =  $str_3.'<td>'. $index3[0]['0'.$i].'</td>';
                            }else{  $str_3 = $str_3.'<td> 0 </td>'; }
                        } 

                    $title = '{LNG_Graph report} {LNG_Member} '; 
                    $list= '{LNG_List of} {LNG_Member}' ;
                    $headtitle = '{LNG_Repair}';
                    $bodytitle = '{LNG_List of}{LNG_Repair}';
                    $content3 = '<br><section class=clear>
                            <h4>'.$title.'</h4>
                            <div id="table3" class="graphcs">
                            <canvas></canvas>
                            <table class="hidden">
                                <thead>
                                <tr>
                                    <th>'.$headtitle.'</th>'. $gmember2.'       
                                </tr>
                                </thead>
                                <tbody>
                                <tr> <th> '.$bodytitle.'</th> '.$str_3.'</tr>
                                </tbody>
                            </table>
                            </div>
                            <script>
                            new GGraphs("table3", {
                                type: "pie",
                                    centerX: 50 + Math.round($G("table3").getHeight() / 2),
                                    labelOffset: 70,
                                    centerOffset: 60,
                                    strokeColor: null,
                                    colors: [
                                        "#660000",
                                        "#d940ff",
                                        "#E65100",
                                        "#FF992A",
                                        "#06d628",
                                        "#009ACD",
                                        "#1B5E20",
                                        "#263238",
                                        "#120eeb",
                                        "#FF1493",
                                        "#FF9999",
                                        "#8B4513",
                                        "#9999FF",
                                        "#CC66FF",
                                        "#FF3300",
                                        "#FFA500",
                                        "#336666",
                                        "#FFDAB9",
                                        "#CD5C5C",
                                        "#FFD700",
                                        "#9400D3",
                                        "#CC6600",
                                        "#FF9933",
                                        "#FF0066",
                                        "#CC3300",
                                        "#66CCCC",
                                        "#FFB90F",
                                        "#00CC00",
                                        "#BEBEBE",
                                        "#00FF7F",
                                    ]
                            });
                            </script>
                        </section>';

        /*  ----------------------------- กราฟที่ 4-------------------------------- */
                // แสดงผล
                $content4 = '<section id=report class="setup_frm">';
                $content4 .= '<article class="ggraphs clear">';
                $index =  \Repair\Home\Model::get_status($params);             
                $title = '{LNG_Graph report} {LNG_Repair process}';
                $list= '{LNG_List of}{LNG_all items}' ;
                $headtitle = '';
                $bodytitle = '{LNG_List of}{LNG_Repair}';    
                $content4 = '<br><section class=clear>
                                <h4>'.$title.' </h4>
                                <div id="table4" class="graphcs">
                                <canvas></canvas>
                                <table class="hidden">
                                    <thead>
                                    <tr>
                                        <th>'.$headtitle.'</th>'. $gmember2.'       
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr> <th> '.$bodytitle.'</th> '.$str_3.'</tr>
                                    </tbody>
                                </table>
                                </div>
                                <script>
                                new GGraphs("table3", {
                                    type: "pie",
                                        centerX: 50 + Math.round($G("table3").getHeight() / 2),
                                        labelOffset: 70,
                                        centerOffset: 60,
                                        strokeColor: null,
                                        colors: [
                                            "#660000",
                                            "#d940ff",
                                            "#E65100",
                                            "#FF992A",
                                            "#06d628",
                                            "#009ACD",
                                            "#1B5E20",
                                            "#263238",
                                            "#120eeb",
                                            "#FF1493",
                                            "#FF9999",
                                            "#8B4513",
                                            "#9999FF",
                                            "#CC66FF",
                                            "#FF3300",
                                            "#FFA500",
                                            "#336666",
                                            "#FFDAB9",
                                            "#CD5C5C",
                                            "#FFD700",
                                            "#9400D3",
                                            "#CC6600",
                                            "#FF9933",
                                            "#FF0066",
                                            "#CC3300",
                                            "#66CCCC",
                                            "#FFB90F",
                                            "#00CC00",
                                            "#BEBEBE",
                                            "#00FF7F",
                                        ]
                                });
                                </script>
                            </section>';

        /*  ----------------------------- กราฟที่ 4-------------------------------- */
                // แสดงผล
                $content4 = '<section id=report class="setup_frm">';
                $content4 .= '<article class="ggraphs clear">';
                $index =  \Repair\Home\Model::get_status($params);             
                $title = '{LNG_Graph report} {LNG_Booking process}';
                $list= '{LNG_List of}{LNG_all items}' ;
                $headtitle = '';
                $bodytitle = '{LNG_List of} {LNG_Booking}';    
                $content4 = '<br><section class=clear>
                                <h4>'.$title.' </h4>
                                <div id="table4" class="graphcs">
                                <canvas></canvas>
                                <table class="hidden">
                                    <thead>
                                    <tr>
                                        <th>'.$headtitle.'</th>'.
                                         /*<th>แจ้งซ่อม</th>          
                                       <th>กำลังดำเนินการ</th>
                                        '<th>รออะไหล่</th>
                                        <th>ซ่อมสำเร็จ</th>'.
                                       <th>ซ่อมไม่สำเร็จ</th>*/
                                         '<th>ยกเลิกการงาน</th>
                                        <th>ปิดงาน</th>
                                        <th>รอหัวหน้าอนุมัติ</th>
                                        <th>ไม่อนุมัติและแจ้ง HR</th>
                                        <th>อนุมัติและแจ้ง HR</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th> '.$bodytitle.'</th>'.
                                        /*<td>'.$index[0]['1'].'</td>
                                        <td>'.$value[0]['2'].'</td>
                                        <td>'.$index[0]['3'].'</td>
                                        <td>'.$value[0]['4'].'</td>
                                        <td>'.$index[0]['5'].'</td>*/
                                        '<td>'.$index[0]['6'].'</td>
                                        <td>'.$index[0]['7'].'</td>
                                        <td>'.$index[0]['8'].'</td>
                                        <td>'.$index[0]['10'].'</td>
                                        <td>'.$index[0]['9'].'</td>
                                    </tr>
                                    </tbody>
                                </table>
                                </div>
                                <script>
                                new GGraphs("table4", {
                                    type: "pie",
                                        centerX: 50 + Math.round($G("table4").getHeight() / 2),
                                        labelOffset: 70,
                                        centerOffset: 60,
                                        strokeColor: null,
                                        colors: [
                                            "#660000",
                                            "#d940ff",
                                            "#120eeb", 
                                            "#FF992A",
                                            "#06d628",
                                            "#009ACD",
                                            "#1B5E20",
                                            "#263238",
                                            "#E65100",
                                            "#06d628",
                                            "#FF9999",
                                            "#8B4513",
                                            "#9999FF",
                                            "#CC66FF",
                                            "#FF3300",
                                            "#FFA500",
                                            "#336666",
                                            "#FFDAB9",
                                            "#CD5C5C",
                                            "#FFD700",
                                            "#9400D3",
                                            "#CC6600",
                                            "#FF9933",
                                            "#FF0066",
                                            "#CC3300",
                                            "#66CCCC",
                                            "#FFB90F",
                                            "#00CC00",
                                            "#BEBEBE",
                                            "#00FF7F",
                                        ]
                                });
                                </script>
                            </section>';

        /*  ----------------------------- กราฟที่ 5-------------------------------- */
                    // แสดงผล                   
                $content5 = '<section id=report >';
                $content5 .= '<article class="ggraphs clear">';
                $title = '{LNG_Graph report} {LNG_Type}';
                $list= '{LNG_List of} {LNG_Booking} ({LNG_hour})' ;
                $headtitle = '{LNG_Booking}';
                $bodytitle = '{LNG_List of}{LNG_Booking} ';
                    $str = ''; $str_2 = '';$time_h =''; $i =0;
                    foreach(\index\Reportg\Model::get_time_of_type($params) as $value2){
                    if($value2->type_id != ''){
                                $time = DATE::DATEDiff($value2->create_date,$value2->end_date); 
                                if($time['d'] > 0 ){ 
                                        $time_h = ($time['d']*24)+($time['h']*60)+$time['i'];  
                                }else if($time['h'] > 0 ) {
                                        $time_h = ($time['h']*60+$time['i']);            
                                }else {
                                    $time_h = 0;            
                            }
                                $q[] = array($value2->create_date,$value2->end_date,$time_h,$value2->topic);
                                $str_2 =  $str_2.'<td>'. $q[$i][2].'</td>';
                                $str =  $str.'<th>'.  $q[$i][3].'</th>';                                      
                        $i++;
                        }            
                    } 
                $content5 .= '<br><section class=clear>
                    <h4>'.$title.' '.$list.'</h4>
                    <div id="table5" class="graphcs">
                        <canvas></canvas>
                        <table class="hidden">
                        <thead>
                            <tr>
                            <th>'.$headtitle.'</th>'. $str
                        .'  </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th> '.$bodytitle.'</th>'.$str_2
                        .'</tr> 
                        </tbody>
                        </table>
                    </div>
                    <script>
                        new GGraphs("table5", {
                        type: "pie",
                            centerX: 50 + Math.round($G("table5").getHeight() / 2),
                            labelOffset: 70,
                            centerOffset: 60,
                            strokeColor: null,
                            colors: [
                                "#660000",
                                        "#d940ff",
                                        "#E65100",
                                        "#FF992A",
                                        "#06d628",
                                        "#009ACD",
                                        "#1B5E20",
                                        "#263238",
                                        "#120eeb",
                                        "#FF1493",
                                        "#FF9999",
                                        "#8B4513",
                                        "#9999FF",
                                        "#CC66FF",
                                        "#FF3300",
                                        "#FFA500",
                                        "#336666",
                                        "#FFDAB9",
                                        "#CD5C5C",
                                        "#FFD700",
                                        "#9400D3",
                                        "#CC6600",
                                        "#FF9933",
                                        "#FF0066",
                                        "#CC3300",
                                        "#66CCCC",
                                        "#FFB90F",
                                        "#00CC00",
                                        "#BEBEBE",
                                        "#00FF7F",
                            ]
                        });
                    </script>
                    </section>'; 

            
    $result = $content. ' ' .$content2. ' ' .$content3. ' ' .$content4. ' ' .$content5;
                    return  $result ;


	}

    
}
