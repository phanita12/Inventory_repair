<?php
/**
 * @filesource modules/repair/controllers/printrepair.php
 *
 */

namespace Repair\Printrepair;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;
use Kotchasan\Date;
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
<<<<<<< HEAD
                $templates = Language::get('Booking');

            if ($index && isset($templates[$index->status])) { 
                // โหลด template
                $billing = $this->getTemplate('Print_Booking');
                $item = \Repair\Printrepair\Model::get($index->id);
                /*เอารูปภาพE-sig approve มาแสดง  */
                $img = is_file(ROOT_PATH.DATA_FOLDER.'approve/'.'R'.$index->id.'-'.Date::format($index->date_approve, 'md').'.jpg') ? WEB_URL.DATA_FOLDER.'approve/'.'R'.$index->id.'-'.Date::format($index->date_approve, 'md').'.jpg' : WEB_URL.'modules/inventory/img/noesig.png';   
                /*เอารูปภาพE-sig User ที่เปิด Job มาแสดง  */
                $imgU = is_file(ROOT_PATH.DATA_FOLDER.'E-signature/'.'Esig_'.$item->user.'.jpg') ? WEB_URL.DATA_FOLDER.'E-signature/'.'Esig_'.$item->user.'.jpg' : WEB_URL.'modules/inventory/img/noesig.png';
                /*เอารูปภาพE-sig User ที่ปิด Job   */
                $imgUC = is_file(ROOT_PATH.DATA_FOLDER.'E-signature/'.'Esig_'.$item->operator_id.'.jpg') ? WEB_URL.DATA_FOLDER.'E-signature/'.'Esig_'.$item->operator_id.'.jpg' : WEB_URL.'modules/inventory/img/noesig.png';
                //เช็คกลุ่มผู้ใช้งาน
                $gmember = \Index\Member\Model::getMemberstatus($index->s_group);

                        //สำหรับผู้ขอรับบริการ (User)
                                $detail = '';
                                $detail .= ' <caption style="background-color: white;color: black; text-align: right; font-weight: bold; font-family: THSarabunNew,Tahoma,Loma;">เอกสารเลขที่ '.$index->job_id.'</caption>';
                                $detail .= ' <caption style="background-color: white;color: black; text-align: right; font-weight: bold; font-family: THSarabunNew,Tahoma,Loma;">วันที่แจ้ง '.Date::format($item->create_date, 'd F Y H:i').' น. </caption>';
                                $detail .= ' <caption style="border-style:groove;"> ผู้ขอรับบริการ</caption>';
                                $detail .= '<tr>';
                                $detail .= '<th style="width: 12.5%;">ชื่อ - นามสกุล :</th>';
                                $detail .= '<td style="width: 21%;">'.$item->name.'</td>';
                                $detail .= '<th class="date_create" style="width: 6%;" >อีเมล :</th>';
                                $detail .= '<td style="border-right-style: groove; width: 33%;">'.$item->username.'</td>';
                                $detail .= '</tr>';
                                $detail .= '<tr>';
                                $detail .= '<th  >ตำแหน่ง :</th>';
                                $detail .= '<td >'.$item->id_card.'</td>';
                                $detail .= '<th >แผนก :</th>';
                                $detail .= '<td style="border-right-style: groove;">'.$gmember.'</td>';
                                $detail .= '</tr>';
                        //ส่วนข้อมูลการจองรถ
                                $detailhead = '';
                                $detailhead .= ' <caption style="border-style:groove;"> ข้อมูลการจองรถ</caption>';
                                $detailhead .= '<tr>';      
                                $detailhead .= '<th style="width: 15.7%;">วันที่ขอยืม :</th>';
                                $detailhead .= '<td style="width: 22%;">'.Date::format($item->begin_date, 'd F Y H:i').' น.</td>';
                                $detailhead .= '<th class="date_create" style="width: 12%;" >ถึง :</th>';
                                $detailhead .= '<td style="border-right-style: groove; width: 33%;">'.Date::format($item->end_date,'d F Y H:i').' น.</td>';
                                $detailhead .= '</tr>';
                                $detailhead .= '<tr>';                      
                                $detailhead .= '<th  >ทะเบียนรถ :</th>';
                                $detailhead .= '<td>'.$item->product_no.'</td>';
                                $detailhead .= '<th>ประเภท/ยี่ห้อ :</th>';
                                $detailhead .= '<td style="border-right-style: groove;">'.$item->type.' / '.$item->model.' </td>';        
                                $detailhead .= '</tr>';
                                $detailhead .= '<tr>';
                                $detailhead .= '<th  >หมวดหมู่ :</th>';
                                $detailhead .= '<td >'.$item->catagory.'</td>';
                                $detailhead .= '<th style="border-bottom: hidden;"> ข้อมูลรถ :</th>';
                                $detailhead .= '<td style="border-right-style: groove;width:41.5%">'.$item->topic.'</td>';
                                $detailhead .= '</tr>';
                                $detailhead .= '<tr>';
                                $detailhead .= '<th style="width: 15.7%;">สถานที่ปลายทาง :</th>';
                                $detailhead .= '<td colspan=4 style="border-right-style: groove;">'.$item->destination.'</td>';  //style="border-bottom: groove;border-right-style: groove;border-width: 1px;"
                                $detailhead .= '</tr>';
                                $detailhead .= '<tr>';
                                $detailhead .= '<th >หมายเหตุ :</th>';
                                $detailhead .= '<td colspan=4 style="border-right-style: groove;">'.$item->job_description.'</td>';  //style="border-bottom: groove;border-right-style: groove;border-width: 1px;"
                                $detailhead .= '</tr>';
                        // Table detailtopic แสดงวัตถุประส่งค์ แบบ checkbox 
                                $detailtopic = '';
                                //   $detailtopic = '<caption style="border-style:groove;">วัตถุประสงค์</caption>';               
                                if($index->types_objective == '0'){
                                    $detailtopic .= '<tr >';
                                    $detailtopic = '<th style="border-style:groove;    width: 3.50%;">เพื่อวัตถุประสงค์ :</th>';
                                    $detailtopic .= '<td style="width:30%;border-top-style: groove;"><input style="width:10%;" type="checkbox" checked>เข้าพบลูกค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">ติดต่อราชการ</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">สัมมนา</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">ส่งสินค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">อื่นๆ</td>';
                                    $detailtopic .= '</tr>';
                                }else if($index->types_objective == '1'){
                                    $detailtopic .= '<tr >';
                                    $detailtopic = '<th style="border-style:groove;    width: 3.50%;">เพื่อวัตถุประสงค์ :</th>';
                                    $detailtopic .= '<td style="width:30%;border-top-style: groove;"><input style="width:10%;" type="checkbox" disabled="disabled">เข้าพบลูกค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" checked>ติดต่อราชการ</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">สัมมนา</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">ส่งสินค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled" >อื่นๆ</td>';
                                    $detailtopic .= '</tr>';
                                }else if($index->types_objective == '2'){
                                    $detailtopic .= '<tr >';
                                    $detailtopic = '<th style="border-style:groove;    width: 3.50%;">เพื่อวัตถุประสงค์ :</th>';
                                    $detailtopic .= '<td style="width:30%;border-top-style: groove;"><input style="width:10%;" type="checkbox" disabled="disabled">เข้าพบลูกค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">ติดต่อราชการ</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" checked>สัมมนา</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">ส่งสินค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">อื่นๆ</td>';
                                    $detailtopic .= '</tr>';
                                }else if($index->types_objective == '3'){
                                    $detailtopic .= '<tr >';
                                    $detailtopic = '<th style="border-style:groove;    width: 3.50%;">เพื่อวัตถุประสงค์ :</th>';
                                    $detailtopic .= '<td style="width:30%;border-top-style: groove;"><input style="width:10%;" type="checkbox" disabled="disabled">เข้าพบลูกค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">ติดต่อราชการ</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">สัมมนา</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" checked>ส่งสินค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">อื่นๆ</td>';
                                    $detailtopic .= '</tr>';
                                }else{
                                    $detailtopic .= '<tr >';
                                    $detailtopic = '<th style="border-style:groove;    width: 3.50%;">เพื่อวัตถุประสงค์ :</th>';
                                    $detailtopic .= '<td style="width:30%;border-top-style: groove;"><input style="width:10%;" type="checkbox" disabled="disabled">เข้าพบลูกค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">ติดต่อราชการ</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">สัมมนา</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">ส่งสินค้า</td>';
                                    $detailtopic .= '</tr>';
                                    $detailtopic .= '<tr >';
                                    $detailtopic .= '<td style="border-top-style: hidden;"></td>';
                                    $detailtopic .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" checked>อื่นๆ</td>';
                                    $detailtopic .= '</tr>';
                                }
                        // Table detail easy pass แสดงแบบ checkbox 
                                    $detaileasypass= '';
                            if($index->easy_pass == '0'){
                                    $detaileasypass .= '<tr >';
                                    $detaileasypass = '<th style="border-style:groove;border-top-style: hidden;width: 17.1%;">Easy Pass :</th>';
                                    $detaileasypass .= '<td style="border-right-style: hidden;border-top-style: hidden;width: 45%;"><input style="margin-left: 15px;width:11%;" type="checkbox" checked>	ไม่ใช้งาน</td>';
                                    $detaileasypass .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" disabled="disabled">ใช้งาน</td>';
                                    $detaileasypass .= '</tr>';
                            }else if($index->easy_pass == '1'){
                                    $detaileasypass .= '<tr >';
                                    $detaileasypass = '<th style="border-style:groove;border-top-style: hidden;width: 17.1%;">Easy Pass :</th>';
                                    $detaileasypass .= '<td style="border-right-style: hidden;border-top-style: hidden;width: 45%;"><input style="margin-left: 15px;width:11%;" type="checkbox" disabled="disabled">	ไม่ใช้งาน</td>';
                                    $detaileasypass .= '<td style="border-top-style: hidden;"><input style="width:10%;" type="checkbox" checked>ใช้งาน</td>';
                                    $detaileasypass .= '</tr>';
                            }        
                       
                        //ส่วนแสดงลายเซ็น
                                $detailapprove = '';
                               // $detailapprove .= '<caption>สำหรับผู้อนุมัติ (Approved) </caption>';   
                                $arr_app = \Repair\Printrepair\Model::getapp($index->id);   
                               
                                if($item->status != 1 && $item->status != 2 && $item->status != 3 && $item->status != 5 && $item->status != 6 && $item->status != 8  ){ //$item->status == 9 || $item->status == 10
                                        for($i=0;$i<= count($arr_app);$i++){
                                            if($arr_app[$i]->status == 9){
                                                $A[$i] = $arr_app[$i]->comment.' ';
                                                $B[$i] = $arr_app[$i]->date_approve;
                                            }
                                        } //foreach($A as $comment)foreach($B as $date_approve)
                                        if($item->status ==9 || $item->status ==10)
                                        {
                                                $detailapprove .= '<tr>';
                                                $detailapprove .= '<td  style="text-align: center;border-bottom-style: hidden;"><img class=signature src="'.$imgU.'"></td>';
                                                $detailapprove .= '<td  style="text-align: center;border-bottom-style: hidden;"><img class=signature src="'.$img.'"></td>';
                                                $detailapprove .= '<td  style="text-align: center;border-bottom-style: hidden;"><img class=signature src="'.WEB_URL.'modules/inventory/img/noesig.png'.'"></td>';
                                                $detailapprove .= '</tr>';
                                                $detailapprove .= '<tr>';
                                                $detailapprove .= '<td style="text-align: center;">( '.$item->name.' )</td>';
                                                $detailapprove .= '<td style="text-align: center;">( '.$item->send_approve2.' )</td>';  
                                                $detailapprove .= '<td style="text-align: center;"></td>'; 
                                                $detailapprove .= '</tr>';
                                                $detailapprove .= '<tr>';
                                                $detailapprove .= '<th style="text-align: center;border-bottom-style: groove;">ลงชื่อผู้ขอรับบริการ</th>';
                                                $detailapprove .= '<th style="text-align: center;border-bottom-style: groove;border-left-style: groove;">ลงชื่อผู้บังคับบัญชา</th>';
                                                $detailapprove .= '<th style="text-align: center;border-right-style: groove;border-left-style: groove;    border-bottom-style: groove;">ลงชื่อผู้อนุมัติ</th>';
                                                $detailapprove .= '</tr>';
                                        }else{
                                                $detailapprove .= '<tr>';
                                                $detailapprove .= '<td  style="text-align: center;border-bottom-style: hidden;"><img class=signature src="'.$imgU.'"></td>';
                                                $detailapprove .= '<td  style="text-align: center;border-bottom-style: hidden;"><img class=signature src="'.$img.'"></td>';
                                                $detailapprove .= '<td  style="text-align: center;border-bottom-style: hidden;"><img class=signature src="'.$imgUC.'" ></td>';
                                                $detailapprove .= '</tr>';
                                                $detailapprove .= '<tr>';
                                                $detailapprove .= '<td style="text-align: center;">( '.$item->name.' )</td>';
                                                $detailapprove .= '<td style="text-align: center;">( '.$item->send_approve2.' )</td>';  
                                                $detailapprove .= '<td style="text-align: center;">( '.$item->name_close.' )</td>'; 
                                                $detailapprove .= '</tr>';
                                                $detailapprove .= '<tr>';
                                                $detailapprove .= '<th style="text-align: center;border-bottom-style: groove;">ลงชื่อผู้ขอรับบริการ</th>';
                                                $detailapprove .= '<th style="text-align: center;border-bottom-style: groove;border-left-style: groove;">ลงชื่อผู้บังคับบัญชา</th>';
                                                $detailapprove .= '<th style="text-align: center;border-right-style: groove;border-left-style: groove;    border-bottom-style: groove;">ลงชื่อผู้อนุมัติ</th>';
                                                $detailapprove .= '</tr>';
                                        }
                                }else{
                                    $detailapprove .= '<tr>';
                                    $detailapprove .= '<td  style="text-align: center;border-bottom-style: hidden;"><img class=signature src="'.$imgU.'"></td></td>';
                                    $detailapprove .= '<td  style="text-align: center;border-bottom-style: hidden;"><img class=signature src="'.WEB_URL.'modules/inventory/img/noesig.png'.'"></td>';
                                    $detailapprove .= '<td  style="text-align: center;border-bottom-style: hidden;"><img class=signature src="'.WEB_URL.'modules/inventory/img/noesig.png'.'" ></td>';
                                    $detailapprove .= '</tr>';
                                    $detailapprove .= '<tr>';
                                    $detailapprove .= '<td style="text-align: center;">( '.$item->name.' )</td>';
                                    $detailapprove .= '<td style="text-align: center;">( '.$item->send_approve2.' )</td>';   
                                    $detailapprove .= '<td style="text-align: center;"></td>'; 
                                    $detailapprove .= '</tr>';
                                   /* $detailapprove .= '<tr>';
                                    $detailapprove .= '<td style="text-align: center;border-top-style: hidden;border-bottom-style: hidden;">( '.Date::format($item->create_date, 'd/m/Y').' )</td>';
                                    $detailapprove .= '<td style="text-align: center;border-top-style: hidden;border-bottom-style: hidden;">'.Date::format($date_approve, 'd/m/Y').' </td>';  
                                    $detailapprove .= '<td style="text-align: center;border-top-style: hidden;border-bottom-style: hidden;">( '.Date::format( $item->date_approve, 'd/m/Y').' )</td>'; 
                                    $detailapprove .= '</tr>';*/
                                    $detailapprove .= '<tr>';
                                    $detailapprove .= '<th style="text-align: center;border-bottom-style: groove;">ลงชื่อผู้ขอรับบริการ</th>';
                                    $detailapprove .= '<th style="text-align: center;border-bottom-style: groove;border-left-style: groove;">ลงชื่อผู้บังคับบัญชา</th>';
                                    $detailapprove .= '<th style="text-align: center;border-right-style: groove;border-left-style: groove;    border-bottom-style: groove;">ลงชื่อผู้อนุมัติ</th>';
                                    $detailapprove .= '</tr>';
                                } 
                        
                        //บันทึกจากเจ้าหน้าที่ส่วนกลาง (สำหรับฝ่าย HR )
                                    $detailit = '';
                                    $detailit .= '<caption style="border-style:groove;">บันทึกจากเจ้าหน้าที่ส่วนกลาง</caption>';
                                    if($item->status == 1 || $item->status == 2 || $item->status == 8){       //|| $item->status == 9 || $item->status == 10
                                        $detailit .= '<tr>';
                                        $detailit .= '<th>ยอดเงินคงเหลือ Easy Pass :</th>';
                                        $detailit .= '<td  colspan=2 style="width: 39%;">0.00   บาท</td>';
                                        $detailit .= '<th>รวมระยะทาง :</th>';
                                        $detailit .= '<td  colspan=4 style="border-right-style: groove;border-bottom-style: groove;border-width: 1px;width: 24%;">0  กิโลเมตร</td>';
                                        $detailit .= '</tr>';
                                        $detailit .= '<tr>';
                                        $detailit .= '<th style="border-bottom-style: groove;">เลขไมล์เริ่มต้น :</th>';
                                        $detailit .= '<td colspan=2 style="border-bottom: groove;border-width: 1px;">0  กิโลเมตร</td>';
                                        $detailit .= '<th style="border-bottom-style: groove;">เลขไมล์สิ้นสุด :</th>';
                                        $detailit .= '<td  colspan=4 style="border-right-style: groove;border-bottom-style: groove;border-width: 1px;width: 24%;">0  กิโลเมตร</td>';
                                        $detailit .= '</tr>';
                                      /*  $detailit .= '<tr>';
                                        $detailit .= '<th style="border-bottom-style: groove;">เลขไมล์สิ้นสุด :</th>';
                                        $detailit .= '<td  colspan=4 style="border-right-style: groove;border-bottom-style: groove;border-width: 1px;width: 24%;">0  กิโลเมตร</td>';
                                        $detailit .= '</tr>';*/
                                    }else{   
                                        $detailit .= '<tr>';
                                        $detailit .= '<th>ยอดเงินคงเหลือ Easy Pass :</th>';
                                        $detailit .= '<td colspan=2 style="width: 39%;">'.$index->cost.'  บาท</td>';
                                        $detailit .= '<th>รวมระยะทาง :</th>';
                                        $detailit .= '<td  colspan=4 style="border-right-style: groove;border-bottom-style: groove;border-width: 1px;width: 24%;">'.($index->car_mileage_end - $index->car_mileage_start).'  กิโลเมตร</td>';
                                        $detailit .= '</tr>';
                                        $detailit .= '<tr>';
                                        $detailit .= '<th style="border-bottom-style: groove;">เลขไมล์เริ่มต้น :</th>';
                                        $detailit .= '<td colspan=2 style="border-bottom: groove;border-width: 1px;">'.$index->car_mileage_start.'  กิโลเมตร</td>';
                                        $detailit .= '<th style="border-bottom-style: groove;">เลขไมล์สิ้นสุด :</th>';
                                        $detailit .= '<td  colspan=4 style="border-right-style: groove;border-bottom-style: groove;border-width: 1px;width: 24%;">'.$index->car_mileage_end.'  กิโลเมตร</td>';
                                        $detailit .= '</tr>';
                                       /*$detailit .= '<tr>';
                                        $detailit .= '<th style="border-bottom-style: groove;">รวมระยะทาง :</th>';
                                        $detailit .= '<td  colspan=4 style="border-right-style: groove;border-bottom-style: groove;border-width: 1px;width: 24%;">'.($index->car_mileage_end - $index->car_mileage_start).'  กิโลเมตร</td>';
                                        $detailit .= '</tr>';*/
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
                                    '/<tr>[\r\n\s\t]{0,}<td>[\r\n\s\t]{0,}%DETAILTOPIC%[\r\n\s\t]{0,}<\/td>[\r\n\s\t]{0,}<\/tr>/' => $detailtopic,
                                    '/<tr>[\r\n\s\t]{0,}<td>[\r\n\s\t]{0,}%DETAILEASYPASS%[\r\n\s\t]{0,}<\/td>[\r\n\s\t]{0,}<\/tr>/' => $detaileasypass, 
                                    '/<tr>[\r\n\s\t]{0,}<td>[\r\n\s\t]{0,}%DETAILHEAD%[\r\n\s\t]{0,}<\/td>[\r\n\s\t]{0,}<\/tr>/' => $detailhead,
                                    '/%LOGO%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/logo.png') ? '<img  class="logo" src="'.WEB_URL.DATA_FOLDER.'/logo/logo.png">' : '',
                                    '/%LOGO_2%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-01_0.png') ? '<img class="logo2"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-01_0.png">' : '',
                                    '/%LOGO_3%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-03.png') ? '<img class="logo3"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-03.png">' : '',
                                    '/%LOGO_4%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-02.png') ? '<img class="logo4"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-02.png">' : '',
                                );
                            \Repair\Printrepair\View::toPrint($content); 

               
=======
                $templates = Language::get('Repair');
                if ($index && isset($templates[$index->status])) { 
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
                        $detail .= ' <caption style="border-style: solid;font-family: "THSarabunNew",Tahoma,Loma;">สำหรับผู้ขอรับบริการ (User) </caption>';
                        $detail .= '<tr>';
                        $detail .= '<th >ชื่อ - นามสกุล :</th>';
                        $detail .= '<td colspan="2" style="width: 30%;">'.$item->name.'</td>';
                        $detail .= '<th class="date_create" >อีเมล :</th>';
                        $detail .= '<td style="border-right-style: solid;">'.$item->username.'</td>';
                        $detail .= '</tr>';
                        $detail .= '<tr>';
                        $detail .= '<th  >ตำแหน่ง :</th>';
                        $detail .= '<td colspan="2">'.$item->id_card.'</td>';
                        $detail .= '<th >แผนก :</th>';
                        $detail .= '<td style="border-right-style: solid;">'.$gmember.'</td>';
                        $detail .= '</tr>';
                        $detail .= '<tr>';                   
                        $detail .= '<th  >หมวดหมู่ :</th>';
                        $detail .= '<td colspan="2">'.$item->catagory.'</td>';
                        $detail .= '<th  style="width: 22%;">หมายเลขเครื่อง/ชื่อระบบ :</th>';
                        $detail .= '<td style="border-right-style: solid;">'.$item->product_no.'</td>';
                        $detail .= '</tr>';
                        $detail .= '<tr>';
                        $detail .= '<th>ประเภท/ยี่ห้อ :</th>';
                        $detail .= '<td colspan="2">'.$item->type.' / '.$item->model.' </td>';
                        $detail .= '<th style="border-bottom: hidden;"> อุปกรณ์/ชื่อเมนูย่อย :</th>';
                        $detail .= '<td style="border-right-style: solid;">'.$item->topic.'</td>';
                        $detail .= '</tr>';
                        $detail .= '<tr>';
                        $detail .= '<th  style="width: 19%;">ความต้องการ/แก้ไข :</th>';
                        $detail .= '<td colspan=4 style="border-bottom: groove;border-right-style: solid;border-width: 1px;width: 75%;">'.$item->job_description.' </td>';
                        $detail .= '</tr>';
                        $detail .= '<tr>';
                        $detail .= '<th colspan=4 style="width: 70%;">ลงชื่อผู้ขอรับบริการ</th>';
                        $detail .= '<td  style="border-right-style: solid;"><img class=signature src="'.$imgU.'"></td>';
                        $detail .= '</tr>';

                    //สำหรับฝ่ายไอที (IT)
                            $detailit = '';
                            $detailit .= '<caption style="border-style: solid;font-family: "THSarabunNew",Tahoma,Loma;">สำหรับฝ่ายไอที (IT) </caption>';
                            if($item->status == 1 || $item->status == 2 || $item->status == 8){       //|| $item->status == 9 || $item->status == 10
                                $detailit .= '<tr>';
                                $detailit .= '<th >ชื่อผู้ตรวจสอบ :</th>';
                                $detailit .= '<td  colspan=2 style="width: 24%;"></td>';
                                $detailit .= '<th style="border-bottom: hidden;width: 28%;">วันที่ดำเนินการแล้วเสร็จ :</th>';
                                $detailit .= '<td style="border-right-style: solid;"></td>';
                                $detailit .= '</tr>';
                                $detailit .= '<tr>';
                                $detailit .= '<th >วิธีปรับปรุง/คำอธิบาย :</th>';
                                $detailit .= '<td colspan=4 style="border-bottom: groove;border-right-style: solid;border-width: 1px;"></td>';
                                $detailit .= '</tr>';
                            }else{                    
                                $detailit .= '<tr>';
                                $detailit .= '<th style="width: 20%;">ชื่อผู้ตรวจสอบ :</th>';
                                $detailit .= '<td style="width: 24%;">'.$item->name_close.'</td>';
                                $detailit .= '<th style="border-bottom: hidden;width: 28%;">วันที่ดำเนินการแล้วเสร็จ :</th>';
                                $detailit .= '<td style="border-right-style: solid;" colspan="2">'.Date::format( $item->end_date, 'd M Y H:i').'</td>'; 
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
                            $detailit .= '<th colspan=4>ลงชื่อผู้ตรวจสอบ</th>'; // style="width: 70%;"
                            $detailit .= '<td  style="border-right-style: solid;border-bottom: hidden;"><img class=signature src="'.$imgUC.'" ></td>';
                            $detailit .= '</tr>';
                            $detailit .= '<tr >';
                            $detailit .= '<th colspan=4 style="border-bottom: solid;border-width: 1px;"></th>';
                            $detailit .= '<td colspan=4 style="border-bottom: solid;border-width: 1px; border-right-style: solid;"></td>';
                            $detailit .= '</tr>';

                   

                    //สำหรับผู้อนุมัติ (Approved)
                            $detailapprove = '';
                            $detailapprove .= '<caption style="border-style: solid;font-family: "THSarabunNew",Tahoma,Loma;">สำหรับผู้อนุมัติ (Approved) </caption>';   
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
                                    $detailapprove .= '<th  style="width: 20%;">ชื่อหัวหน้างาน :</th>';
                                    $detailapprove .= '<td style="width: 37%;">'.$item->send_approve2.'</td>';
                                    $detailapprove .= '<th style="width: 15%;">วันที่อนุมัติ :</th>';
                                    $detailapprove .= '<td style="border-right-style: solid;">'.Date::format($date_approve, 'd M Y H:i').'</td>'; 
                                    $detailapprove .= '</tr>';
                                    $detailapprove .= '<tr>';
                                    $detailapprove .= '<th>รายละเอียด :</th>';
                                    $detailapprove .= '<td colspan=4 style="border-bottom: groove;border-right-style: solid;border-width: 1px;">'.$comment.'</td>';
                                    $detailapprove .= '</tr>'; 
                                    $detailapprove .= '<tr>';
                                    $detailapprove .= '<th colspan=3 style="width: 70%;">ลงชื่อผู้อนุมัติ</th>';
                                    $detailapprove .= '<td  style="border-right-style: solid;"><img class=signature src="'.$img.'"></td>';
                                    $detailapprove .= '</tr>';
                            }else{
                                $detailapprove .= '<tr>';
                                $detailapprove .= '<th style="width: 20%;">ชื่อหัวหน้างาน :</th>';
                                $detailapprove .= '<td style="width: 37%;"></td>';
                                $detailapprove .= '<th style="border-bottom: hidden;width: 15%;">วันที่อนุมัติ :</th>';
                                $detailapprove .= '<td style="border-right-style: solid;"></td>';
                                $detailapprove .= '</tr>';
                                $detailapprove .= '<th>รายละเอียด :</th>';
                                $detailapprove .= '<td colspan=4 style="border-bottom: groove;border-right-style: solid;border-width: 1px;"></td>';
                                $detailapprove .= '</tr>';
                                $detailapprove .= '<tr>';
                                $detailapprove .= '<th colspan=3 style="width: 70%;">ลงชื่อผู้อนุมัติ</th>';
                                $detailapprove .= '<td  style="border-right-style: solid;"><img class=signature src="'.WEB_URL.'modules/inventory/img/noesig.png'.'"></td>';
                                $detailapprove .= '</tr>';
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
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
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
      
        $file = ROOT_PATH.'modules/repair/template/Print_Booking.html';

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
