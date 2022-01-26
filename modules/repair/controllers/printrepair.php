<?php
/**
 * @filesource modules/repair/controllers/printrepair.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Printrepair;

use Gcms\Login;
//use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;
use Kotchasan\Date;
//use Kotchasan\Currency;

/**
 * module=repair-printrepair
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller //extends \Gcms\Controller
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

        // สมาชิก
        if (Login::isMember()) {
            // อ่านข้อมูลที่เลือก
            $index = \Repair\Detail\Model::get($request->request('id')->toInt());
            // template
          $templates = Language::get('Repair');
         // var_dump($templates);

            if ($index && isset($templates[$index->status])) { //$index->status
                // โหลด template
                $billing = $this->getTemplate('QUO');
                $item = \Repair\Printrepair\Model::get($index->id);

                /*เอารูปภาพE-sig approve มาแสดง  */
                $img = is_file(ROOT_PATH.DATA_FOLDER.'approve/'.'R'.$index->id.'-'.Date::format($index->date_approve, 'md').'.jpg') ? WEB_URL.DATA_FOLDER.'approve/'.'R'.$index->id.'-'.Date::format($index->date_approve, 'md').'.jpg' : WEB_URL.'modules/inventory/img/noesig.png';   
                /*เอารูปภาพE-sig User ที่เปิด Job มาแสดง  */
                $imgU = is_file(ROOT_PATH.DATA_FOLDER.'E-signature/'.'Esig_'.$item->user.'.jpg') ? WEB_URL.DATA_FOLDER.'E-signature/'.'Esig_'.$item->user.'.jpg' : WEB_URL.'modules/inventory/img/noesig.png';
                /*เอารูปภาพE-sig User ที่ปิด Job   */
                $imgUC = is_file(ROOT_PATH.DATA_FOLDER.'E-signature/'.'Esig_'.$item->operator_id.'.jpg') ? WEB_URL.DATA_FOLDER.'E-signature/'.'Esig_'.$item->operator_id.'.jpg' : WEB_URL.'modules/inventory/img/noesig.png';
               
                //เช็คกลุ่มผู้ใช้งาน
                $gmember = \Index\Member\Model::getMemberstatus($index->s_group);

                    // ข้อมูลการทำรายการ
                    $detail = '';

                    //สำหรับผู้ขอรับบริการ (User)
                    $detail .= ' <caption style="background-color: white;color: black; text-align: right; font-weight: bold; font-family: THSarabunNew,Tahoma,Loma;">เอกสารเลขที่ '.$index->job_id.'</caption>';
                    $detail .= ' <caption style="background-color: white;color: black; text-align: right; font-weight: bold; font-family: THSarabunNew,Tahoma,Loma;">วันที่ขอรับบริการ '.Date::format($item->create_date, 'd M Y H:i').' </caption>';
                    $detail .= ' <caption >สำหรับผู้ขอรับบริการ (User) </caption>';
                    $detail .= '<tr>';
                    $detail .= '<th >ชื่อ - นามสกุล :</th>';
                    $detail .= '<td>'.$item->name.'</td>';
                    $detail .= '<th class="date_create" >อีเมล :</th>';
                    $detail .= '<td style="border-right-style: solid;">'.$item->username.'</td>';
                    $detail .= '</tr>';
                    $detail .= '<tr>';
                    $detail .= '<th  >ตำแหน่ง :</th>';
                    $detail .= '<td >'.$item->id_card.'</td>';
                    $detail .= '<th >แผนก :</th>';
                    $detail .= '<td style="border-right-style: solid;">'.$gmember.'</td>';
                    $detail .= '</tr>';
                    $detail .= '<tr>';                   
                    $detail .= '<th  >หมวดหมู่ :</th>';
                    $detail .= '<td >'.$item->catagory.'</td>';
                    $detail .= '<th  >หมายเลขเครื่อง/ชื่อระบบ :</th>';
                    $detail .= '<td style="border-right-style: solid;">'.$item->product_no.'</td>';
                    $detail .= '</tr>';
                    $detail .= '<tr>';
                    $detail .= '<th>ประเภท/ยี่ห้อ :</th>';
                    $detail .= '<td>'.$item->type.' / '.$item->model.' </td>';
                    $detail .= '<th style="border-bottom: hidden;"> อุปกรณ์/ชื่อเมนูย่อย :</th>';
                    $detail .= '<td style="border-right-style: solid;">'.$item->topic.'</td>';
                    $detail .= '</tr>';
                    $detail .= '<tr>';
                    $detail .= '<th  >ความต้องการ/แก้ไข :</th>';
                    $detail .= '<td colspan=3 style="border-bottom: groove;border-right-style: solid;border-width: 1px;width: 75%;">'.$item->job_description.' </td>';
                    $detail .= '</tr>';
                    $detail .= '<tr>';
                    $detail .= '<th colspan=3 style="width: 70%;">ลงชื่อผู้ขอรับบริการ</th>';
                    $detail .= '<td  style="border-right-style: solid;"><img class=signature src="'.$imgU.'"></td>';
                    $detail .= '</tr>';

                    //สำหรับฝ่ายไอที (IT)
                    $detailit = '';
                    $detailit .= '<caption>สำหรับฝ่ายไอที (IT) </caption>';
                   
                    if($item->status == 1 || $item->status == 2 || $item->status == 8){       //|| $item->status == 9 || $item->status == 10
                        $detailit .= '<tr>';
                        $detailit .= '<th >ชื่อผู้ตรวจสอบ :</th>';
                        $detailit .= '<td  colspan=2 style="width: 24%;"></td>';
                        $detailit .= '<th style="border-bottom: hidden;width: 24%;">วันที่ดำเนินการแล้วเสร็จ :</th>';
                        $detailit .= '<td style="border-right-style: solid;"></td>';
                        $detailit .= '</tr>';
                        $detailit .= '<tr>';
                        $detailit .= '<th >วิธีปรับปรุง/คำอธิบาย :</th>';
                        $detailit .= '<td colspan=4 style="border-bottom: groove;border-right-style: solid;border-width: 1px;"></td>';
                        $detailit .= '</tr>';
                    }else{
                        
                        $detailit .= '<tr>';
                        $detailit .= '<th >ชื่อผู้ตรวจสอบ :</th>';
                        $detailit .= '<td style="width: 24%;">'.$item->name_close.'</td>';
                        $detailit .= '<th style="border-bottom: hidden;width: 24%;">วันที่ดำเนินการแล้วเสร็จ :</th>';
                        $detailit .= '<td style="border-right-style: solid;" colspan="2">'.Date::format( $item->date_approve, 'd M Y H:i').'</td>'; 
                        $detailit .= '</tr>';
                        $detailit .= '<tr>';
                        $detailit .= '<th >วิธีปรับปรุง/คำอธิบาย :</th>';
                        $detailit .= '<td colspan=4 style="border-bottom: groove;border-right-style: solid;border-width: 1px;">'.$item->comment.'</td>';
                        $detailit .= '</tr>';
                    }  
                    $detailit .= '<tr>';
                    $detailit .= '<th >สถานะเอกสาร :</th>';
                    $detailit .= '<td  colspan=4 style="border-right-style: solid;border-bottom-style: groove;border-width: 1px;width: 24%;">'.$item->repairstatus.'</td>';
                    $detailit .= '</tr>';
                    $detailit .= '<tr>';
                    $detailit .= '<th colspan=4 style="width: 70%;">ลงชื่อผู้ตรวจสอบ</th>';
                    $detailit .= '<td  style="border-right-style: solid;border-bottom: hidden;"><img class=signature src="'.$imgUC.'" ></td>';
                    $detailit .= '</tr>';
                    $detailit .= '<tr >';
                    $detailit .= '<th colspan=4 style="border-bottom: solid;border-width: 1px;"></th>';
                    $detailit .= '<td colspan=4 style="border-bottom: solid;border-width: 1px; border-right-style: solid;"></td>';
                    $detailit .= '</tr>';

                   

                    //สำหรับผู้อนุมัติ (Approved)
                    $detailapprove = '';
                    $detailapprove .= '<caption>สำหรับผู้อนุมัติ (Approved) </caption>';   
                    $arr_app = \Repair\Printrepair\Model::getapp($index->id);   
                    
                    if($item->status != 1 && $item->status != 2 && $item->status != 3 && $item->status != 5 && $item->status != 6  ){ //$item->status == 9 || $item->status == 10
                        for($i=0;$i<count($arr_app);$i++){
                            if($arr_app[$i]->status == 9){
                                $A[$i] = $arr_app[$i]->comment.' ';
                                $B[$i] = $arr_app[$i]->date_approve;
                            }
                        }
                        if(!empty($A)){
                              foreach($A as $comment);
                              foreach($B as $date_approve);
                        }else{
                            $date_approve = '';
                            $comment = '';
                        }
                        $detailapprove .= '<tr>';
                        $detailapprove .= '<th>ชื่อหัวหน้างาน :</th>';
                        $detailapprove .= '<td>'.$item->send_approve2.'</td>';
                        $detailapprove .= '<th>วันที่อนุมัติ :</th>';
                        $detailapprove .= '<td style="border-right-style: solid;">'.Date::format($date_approve, 'd M Y H:i').'</td>'; 
                        $detailapprove .= '</tr>';
                        $detailapprove .= '<tr>';
                        $detailapprove .= '<th>รายละเอียด :</th>';
                        $detailapprove .= '<td colspan=4 style="border-bottom: groove;border-right-style: solid;border-width: 1px;">'.$comment.'</td>';
                        $detailapprove .= '</tr>'; 
                        $detailapprove .= '<tr>';
                        $detailapprove .= '<th colspan=3 style="width: 70%;">ลงชื่อผู้อนุมัติ :</th>';
                        $detailapprove .= '<td  style="border-right-style: solid;"><img class=signature src="'.$img.'"></td>';
                        $detailapprove .= '</tr>';
                    }else{
                        $detailapprove .= '<tr>';
                        $detailapprove .= '<th style="width: 55px;">ชื่อหัวหน้างาน :</th>';
                        $detailapprove .= '<td style="width: 27%;"></td>';
                        $detailapprove .= '<th style="border-bottom: hidden;">วันที่อนุมัติ :</th>';
                        $detailapprove .= '<td style="border-right-style: solid;"></td>';
                        $detailapprove .= '</tr>';
                        $detailapprove .= '<th>รายละเอียด :</th>';
                        $detailapprove .= '<td colspan=4 style="border-bottom: groove;border-right-style: solid;border-width: 1px;"></td>';
                        $detailapprove .= '</tr>';
                        $detailapprove .= '<tr>';
                        $detailapprove .= '<th colspan=3 style="width: 70%;">ลงชื่อผู้อนุมัติ</th>';
                        $detailapprove .= '<td  style="border-right-style: solid;"><img class=signature src="'.WEB_URL.'modules/inventory/img/noesig.png'.'"></td>';
                        $detailapprove .= '</tr>';

                        /*$detailapprove .= '<tr>';
                        $detailapprove .= '<th colspan=3 style="width: 70%;">ลงชื่อผู้อนุมัติ :</th>';
                        $detailapprove .= '<td  style="border-right-style: solid;"><img class=signature src="'.WEB_URL.'modules/inventory/img/noimage.png'.'"></td>';
                        $detailapprove .= '</tr>';*/
                    } 
                 

                // ภาษาที่ใช้งานอยู่
                $lng = Language::name();
                // ใส่ลงใน template
                $content = array(
                    '/%Job%/' => 'เลขที่'.$index->job_id,
                    '/{LANGUAGE}/' => $lng,
                    '/{CONTENT}/' => $billing['detail'],
                    '/{WEBURL}/' => WEB_URL,
                    '/{TITLE}/' => $billing['title'].'_No.'.$index->job_id,
                    '/<tr>[\r\n\s\t]{0,}<td>[\r\n\s\t]{0,}%DETAIL%[\r\n\s\t]{0,}<\/td>[\r\n\s\t]{0,}<\/tr>/' => $detail,
                    '/<tr>[\r\n\s\t]{0,}<td>[\r\n\s\t]{0,}%DETAILIT%[\r\n\s\t]{0,}<\/td>[\r\n\s\t]{0,}<\/tr>/' => $detailit,
                    '/<tr>[\r\n\s\t]{0,}<td>[\r\n\s\t]{0,}%DETAILAPPROVE%[\r\n\s\t]{0,}<\/td>[\r\n\s\t]{0,}<\/tr>/' => $detailapprove,
                    '/%LOGO%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/logo.png') ? '<img  class="logo" src="'.WEB_URL.DATA_FOLDER.'/logo/logo.png">' : '',
                    '/%LOGO_2%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-01_0.png') ? '<img class="logo2"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-01_0.png">' : '',
                    '/%LOGO_3%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-03.png') ? '<img class="logo3"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-03.png">' : '',
                    '/%LOGO_4%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-02.png') ? '<img class="logo4"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-02.png">' : '',
                );
                \Repair\Printrepair\View::toPrint($content); 

               
            }
        }
    }

    /**
     * อ่าน template
     *
     * @param string $tempate
     *
     * @return array|null คืนค่าข้อมูล template ถ้าไม่พบคืนค่า null
     */
    public function getTemplate($tempate)
    {
      
        $file = ROOT_PATH.'modules/repair/template/QUO.html';

        if (is_file($file)) {
            // โหลด template
            $file = file_get_contents($file);
            //var_dump($match);
            // parse template
            $patt = '/(.*?)<title>(.*?)<\/title>?(.*?)(<detail>(.*?)<\/detail>)?(.*?)<body>(.*?)<\/body>(.*?)/isu';
            $billing = array();
            if (preg_match($patt, $file, $match)) {

                //var_dump($match[2]);

                $billing['title'] = $match[2];
                $billing['detail'] = $match[7];
                if (preg_match_all('/<item>([a-z]{0,})<\/item>/isu', $match[6], $items)) {
                    foreach ($items[1] as $i => $row) {
                        if ($row != '') {
                            $billing['details'][] = $row;
                        }
                    }
                }
            }
            return $billing;
        }
        return null;
    }
}
