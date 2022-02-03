<?php
/**
 * @filesource modules/repair/controllers/printasset.php
 *
 */

namespace Repair\Printasset;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;
use Kotchasan\Date;
use Kotchasan\Currency;
use Kotchasan\Text;
/**
 * module=repair-printasset
 *
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller 
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
        if (Login::isMember() ) {
            // อ่านข้อมูลที่เลือก 
            $index = \Repair\Printasset\Model::get($request->request('id')->toInt(),$request->request('tab')->topic());  
            // ข้อมูลการทำรายการ
            $item = \Repair\Printasset\Model::getdetail($index[0]->product_no);
            // template
            // $templates = Language::get('Repair');
            // โหลด template
            $billing = $this->getTemplate('Asset');
              //เช็คกลุ่มผู้ใช้งาน
            $gmember = \Index\Member\Model::getMemberstatus($index[0]->s_group);
            $head= '';  $detail = '';
            if ($index[0] && count($item) != 0 ){  
                //ออกฟอร์ม
                    if(count($item) <= 9 ){
                        //ข้อมูลทรัพย์สิน
                            $head .= ' <h1>ประวัติ '.$index[0]->type.'</h1>';
                            $head .= '<tr>';                   
                            $head .= '<th  style="width:16%">หมายเลขเครื่อง</th>';
                            $head .= '<td  style="width:25%;vertical-align: middle;">'.$index[0]->product_no.'</td>';
                            $head .= '<th  style="width:17%">ยี่ห้อ</th>';
                            $head .= '<td  style="width:8%;vertical-align: middle;">'.$index[0]->model.' </td>';
                            $head .= '<th  style="width:8%">รุ่น</th>';
                            $head .= '<td  style="vertical-align: middle;"> '.$index[0]->topic.'</td>';
                            $head .= '</tr>';
                            $head .= '<tr>';
                            $head .= '<th>วัน/เดือน/ปี ที่ซื้อ</th>';
                            $head .= '<td style="vertical-align: middle;"> '.DATE::format($index[0]->purchase_date,'d/m/Y').'</td>';
                            $head .= '<th style="border-bottom: hidden;"> ราคา</th>';
                            $head .= '<td style="vertical-align: middle;text-align:right;"> '.Currency::format($index[0]->purchase_price).'</td>';
                            $head .= '<th style="border-bottom: hidden;"> บริษัท</th>';
                            $head .= '<td style="vertical-align: middle;"> '.$index[0]->purchase_company.'</td>';
                            $head .= '</tr>';
                            $head .= '<tr>';
                            $head .= '<th>เบอร์โทรติดต่อ</th>';
                            $head .= '<td  style="vertical-align: middle;"> '.$index[0]->purchase_contact.'</td>';
                            $head .= '<th> หน่วยงานที่ขอซื้อ</th>';
                            $head .= '<td colspan=3 style="vertical-align: middle;">'. $gmember.'</td>';
                            $head .= '</tr>';
                        //ประวัติทรัพย์สิน
                                $detail .= ' <br><h2>ประวัติการซ่อม</h2>';
                                $detail .= '<tr>';
                                $detail .= '<th style="width: 10%;text-align:center;border-style: outset;">วันที่แจ้ง</th>';
                                $detail .= '<th style="width: 26%;text-align:center;border-style: outset;"  >ข้อมูลการแจ้ง</th>';
                                $detail .= '<th style="width: 26%;text-align:center;border-style: outset;"  >การแก้ไข</th>';
                                $detail .= '<th style="width: 8%;text-align:center;border-style: outset;">ราคา</th>';
                                $detail .= '<th style="width: 12%;text-align:center;border-style: outset;">การรับประกัน</th>';
                                $detail .= '<th style="text-align:center;border-style: outset;">บริษัทที่ซ่อม</th>';
                                $detail .= '</tr>';
                                foreach( $item as $key =>   $value){ $item2[$key] = $value;
                                    $detail .= '<tr>';
                                    $detail .= '<td style="text-align:center;height:30px">'.Date::format($item2[$key]->create_date, 'd/m/Y').'</td>';
                                    $detail .= '<td style="border-style: outset;"  >'.TEXT::oneLine($item2[$key]->job_description).' </td>';
                                    $detail .= '<td style="border-style: outset;"   >'.TEXT::oneLine($item2[$key]->comment).' </td>';
                                    $detail .= '<td style="text-align:right;">'.Currency::format($item2[$key]->cost) .' </td>';
                                    $detail .= '<td style="border-style: outset;"> '.$item2[$key]->_warranty_name.'</td>';
                                    $detail .= '<td >&nbsp; '.$item2[$key]->repair_company.'</td>';
                                    $detail .= '</tr>';
                                }
                    }else{  
                        // โหลด template
                            $billing = $this->getTemplate('Asset_2');
                        //เช็คตัวอักษร
                            $str = '';
                            foreach( $item as $key =>   $value){ 
                                if( $key <= 10){
                                    $item2[$key] = $value;
                                    $str .= Date::format($item2[$key]->create_date, 'd/m/Y').'/';
                                    $str .= TEXT::oneLine($item2[$key]->job_description).' /';
                                    $str .=TEXT::oneLine($item2[$key]->comment).'/';
                                    $str .=Currency::format($item2[$key]->cost) .' /';
                                    $str .= $item2[$key]->_warranty_name.'/';
                                    $str .= $item2[$key]->repair_company.'/';
                                }
                            }
                            
                            if(Strlen($str) > 3000){
                                    //หาหน้าที่ต้องแบ่ง
                                        $total_page_data = 0;  // เก็บจำนวนหน้า รายการทั้งหมด
                                        $total_page_item = 9; // จำนวนรายการที่แสดงสูงสุดในแต่ละหน้า
                                        $total_page_item_all = 0; // ไว้เก็บจำนวนรายการจริงทั้งหมด
                                        $arr_data_set=array(array()); // [][];
                                        $i = 1; $detail ='';
                                        if($item&& count($item)>0){  // คิวรี่ข้อมูลสำเร็จหรือไม่ และมีรายการข้อมูลหรือไม่
                                            $total_page_item_all = count($item); // จำนวนรายการทั้งหมด
                                            $total_page_data = ceil($total_page_item_all/$total_page_item); // หาจำนวนหน้าจากรายการทั้งหมด    
                                        //  print_r($total_page_data .'/'.$total_page_item_all.'/'.$total_page_item .'/'.count($item).'/'.Strlen($str))      ;                  
                                                for($row=0;$row<=count($item);$row++){        
                                                        $arr_data_set['create_date'][$row]=$item[$row]->create_date;
                                                        $arr_data_set['job_description'][$row]=  trim(preg_replace('/\s\s+/', ' ', $item[$row]->job_description)); 
                                                        $arr_data_set['comment'][$row]=$item[$row]->comment;
                                                        $arr_data_set['cost'][$row]= Currency::format($item[$row]->cost) ;
                                                        $arr_data_set['warranty'][$row]=$item[$row]->warranty;
                                                        $arr_data_set['repair_company'][$row]=$item[$row]->repair_company;  
                                                $i++;           
                                            }                       
                                        } 
                            }else{
                                    //หาหน้าที่ต้องแบ่ง
                                        $total_page_data = 0;  // เก็บจำนวนหน้า รายการทั้งหมด
                                        $total_page_item = 15; // จำนวนรายการที่แสดงสูงสุดในแต่ละหน้า
                                        $total_page_item_all = 1; // ไว้เก็บจำนวนรายการจริงทั้งหมด
                                        $arr_data_set=array(array()); // [][];
                                        $i = 1; $detail ='';
                                        if($item&& count($item)>0){  // คิวรี่ข้อมูลสำเร็จหรือไม่ และมีรายการข้อมูลหรือไม่
                                            $total_page_item_all = count($item); // จำนวนรายการทั้งหมด
                                            $total_page_data = ceil($total_page_item_all/$total_page_item); // หาจำนวนหน้าจากรายการทั้งหมด  
                                        // print_r($total_page_data .'/'.$total_page_item_all.'/'.$total_page_item .'/'.count($item).'/'.Strlen($str))      ;             
                                                for($row=0;$row<=count($item);$row++){        
                                                        $arr_data_set['create_date'][$row]=$item[$row]->create_date;
                                                        $arr_data_set['job_description'][$row]=  trim(preg_replace('/\s\s+/', ' ', $item[$row]->job_description)); 
                                                        $arr_data_set['comment'][$row]=$item[$row]->comment;
                                                        $arr_data_set['cost'][$row]= Currency::format($item[$row]->cost) ;
                                                        $arr_data_set['warranty'][$row]=$item[$row]->warranty;
                                                        $arr_data_set['repair_company'][$row]=$item[$row]->repair_company;  
                                                $i++;           
                                            }                       
                                        } 
                            }
                        
                        //หาข้อมูลที่จะยัดลง page    
                            for($i=1;$i<=$total_page_data;$i++){
                                    //Logo Header 
                                        $LOGO = is_file(ROOT_PATH.DATA_FOLDER.'/logo/logo.png') ? '<img  class="logo" src="'.WEB_URL.DATA_FOLDER.'/logo/logo.png">' : '';
                                        $LOGO_2 = is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-01_0.png') ? '<img class="logo2"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-01_0.png">' : '';
                                        $LOGO_3 = is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-03.png') ? '<img class="logo3"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-03.png">' : '';      
                                    //Header 
                                        $detail .='<table   class="table" style="font-size: 10px;">';
                                        $detail .='<tr>';
                                        $detail .='<td style="border: hidden;">'.$LOGO.'</td>';
                                        $detail .=' <td style="border: hidden;">'.$LOGO_2.'</td>';
                                        $detail .='</tr>';
                                        $detail .='<tr> ';
                                        $detail .='<td colspan="4" style="border: hidden;">'. $LOGO_3 .'</td>'; 
                                        $detail .='</tr>';
                                    //ข้อมูลทรัพย์สิน  
                                        $detail .='<table   class="table" style="border-style: solid;font-size: 10px;">';
                                        $detail .='<tbody class="detail">';
                                        $detail .= ' <h1>ประวัติ '.$index[0]->type.'</h1>';
                                        $detail .= '<tr>';                   
                                        $detail .= '<th  style="width:16%">หมายเลขเครื่อง</th>';
                                        $detail .= '<td  style="width:25%;vertical-align: middle;">'.$index[0]->product_no.'</td>';
                                        $detail .= '<th  style="width:14%">ยี่ห้อ</th>';
                                        $detail .= '<td  style="width:8%;vertical-align: middle;">'.$index[0]->model.' </td>';
                                        $detail .= '<th  style="width:5%">รุ่น</th>';
                                        $detail .= '<td  style="vertical-align: middle;"> '.$index[0]->topic.'</td>';
                                        $detail .= '</tr>';
                                        $detail .= '<tr>';
                                        $detail .= '<th  style="width:10%">วัน/เดือน/ปี ที่ซื้อ</th>';
                                        $detail .= '<td style="vertical-align: middle;"> '.DATE::format($index[0]->purchase_date,'d/m/Y').' </td>';
                                        $detail .= '<th style="border-bottom: hidden;"> ราคา</th>';
                                        $detail .= '<td style="vertical-align: middle;text-align:right;"> '.Currency::format($index[0]->purchase_price).'</td>';
                                        $detail .= '<th style="border-bottom: hidden;"> บริษัท</th>';
                                        $detail .= '<td style="vertical-align: middle;width: 20%;"> '.$index[0]->purchase_company.'</td>';
                                        $detail .= '</tr>';
                                        $detail .= '<tr>';
                                        $detail .= '<th  style="border-bottom-style: groove;">เบอร์โทรติดต่อ</th>';
                                        $detail .= '<td  style="vertical-align: middle;"> '.$index[0]->purchase_contact.'</td>';
                                        $detail .= '<th> หน่วยงานที่ขอซื้อ</th>';
                                        $detail .= '<td colspan=3 style="vertical-align: middle;">'. $gmember.'</td>';
                                        $detail .= '</tr>';                       
                                        $detail .=' </tbody></table> ';  
                                        $detail .='<table   class="table" style="border-style: solid;font-size: 10px;">';
                                        $detail .='<tbody class="detail">';
                                        $detail .= '<tr>';
                                        $detail .= ' <th colspan=6 style="text-align:center;border-left: hidden;border-right: hidden;"><h2>ประวัติการซ่อม</h2></th>';
                                        $detail .= '</tr>';
                                        $detail .= '<tr>';
                                        $detail .= '<th   style="width: 10%;text-align:center;border-style: outset;">วันที่แจ้ง</th>';
                                        $detail .= '<th style="width: 26%;text-align:center;border-style: outset;">ข้อมูลการแจ้ง</th>';
                                        $detail .= '<th style="width: 26%;text-align:center;border-style: outset;"  >การแก้ไข</th>';
                                        $detail .= '<th style="width: 8%;text-align:center;border-style: outset;">ราคา</th>';
                                        $detail .= '<th style="width: 12%;text-align:center;border-style: outset;">การรับประกัน</th>';
                                        $detail .= '<th style="text-align:center;border-style: outset;">บริษัทที่ซ่อม</th>';
                                        $detail .= '</tr>'; 
                                    //loop ทรัพย์สิน
                                        for($v=0;$v<=$total_page_item;$v++){     
                                            $item_i= (( $i-1)*$total_page_item)+$v;
                                            $_create_date_name = isset($arr_data_set['create_date'][$item_i])?$arr_data_set['create_date'][$item_i]:"";
                                            $_job_description_name = isset($arr_data_set['job_description'][$item_i])?$arr_data_set['job_description'][$item_i]:"";
                                            $_cost_name = isset($arr_data_set['cost'][$item_i])?$arr_data_set['cost'][$item_i]:"";
                                            $_warranty_name = isset($arr_data_set['warranty'][$item_i])?$arr_data_set['warranty'][$item_i]:"";
                                            $_repair_company_name = isset($arr_data_set['repair_company'][$item_i])?$arr_data_set['repair_company'][$item_i]:"";
                                            $_comment =  isset($arr_data_set['comment'][$item_i])?$arr_data_set['comment'][$item_i]:""; 
                                            if(!empty($arr_data_set['job_description'][$item_i] && !empty($arr_data_set['create_date'][$item_i] ))){ 
                                                    $detail .= '<tr> ';
                                                    $detail .= '<td style="text-align:center;">&nbsp; '.DATE::format($_create_date_name,'d/m/Y').'</td>';
                                                    $detail .= '<td style="border-style: outset;"  >&nbsp; '.TEXT::oneLine($_job_description_name).'</td>';
                                                    $detail .= '<td style="border-style: outset;"   >'.TEXT::oneLine($_comment).' </td>';
                                                    $detail .= '<td style="text-align:right;">&nbsp; '.Currency::format($_cost_name).'</td>';
                                                    $detail .= '<td style="border-style: outset;">&nbsp; '.$_warranty_name.'</td>';
                                                    $detail .= '<td >&nbsp; '.$_repair_company_name.'</td>';
                                                    $detail .= '</tr> '; 
                                            }  
                                            $item_i = isset($arr_data_set['create_date'][$item_i])?$item_i:"";  
                                        }    
                                    //Footer
                                        $detail .=' </tbody></table> ';  
                                        if($total_page_data > 1) $detail .= ' <div style="text-align:right;font-size: 08px;">Page '.$i.'/'.$total_page_data.'</div>';
                                        $detail .=' <div style="text-align: left;font-family: Tahoma, Loma,Times New Roman, Times, serif;margin-top: 5px;font-size: 10px;">ITF02-0-08/04/54</div>';  
                                        $detail .=' <footer > %LOGO_4% </footer></table>';
                                        $detail .='<div style="page-break-after: always"></div>';           
                            }        
                    }        
            }else{
                    $head= '';  $detail = '';
                    //ข้อมูลทรัพย์สิน
                            $head .= ' <h1>ประวัติ '.$index[0]->type.'</h1>';
                            $head .= '<tr>';                   
                            $head .= '<th  style="width:16%">หมายเลขเครื่อง</th>';
                            $head .= '<td  style="width:25%;vertical-align: middle;">'.$index[0]->product_no.'</td>';
                            $head .= '<th  style="width:17%">ยี่ห้อ</th>';
                            $head .= '<td  style="width:8%;vertical-align: middle;">'.$index[0]->model.' </td>';
                            $head .= '<th  style="width:8%">รุ่น</th>';
                            $head .= '<td  style="vertical-align: middle;"> '.$index[0]->topic.'</td>';
                            $head .= '</tr>';
                            $head .= '<tr>';
                            $head .= '<th>วัน/เดือน/ปี ที่ซื้อ</th>';
                            $head .= '<td> '.DATE::format($index[0]->purchase_date,'d/m/Y').'</td>';
                            $head .= '<th style="border-bottom: hidden;"> ราคา</th>';
                            $head .= '<td style="text-align:right;"> '.Currency::format($index[0]->purchase_price).'</td>';
                            $head .= '<th style="border-bottom: hidden;"> บริษัท</th>';
                            $head .= '<td>'.$index[0]->purchase_company.'</td>';
                            $head .= '</tr>';
                            $head .= '<tr>';
                            $head .= '<th>เบอร์โทรติดต่อ</th>';
                            $head .= '<td  style="vertical-align: middle;"> '.$index[0]->purchase_contact.'</td>';
                            $head .= '<th> หน่วยงานที่ขอซื้อ</th>';
                            $head .= '<td colspan=3>'. $gmember.'</td>';
                            $head .= '</tr>';   
                    //ประวัติทรัพย์สิน
                            $detail .= ' <br><h2>ประวัติการซ่อม</h2>';
                            $detail .= '<tr>';
                            $detail .= '<th style="width: 10%;text-align:center;border-style: outset;">วันที่แจ้ง</th>';
                            $detail .= '<th style="width: 30%;text-align:center;border-style: outset;">ข้อมูลการแจ้ง</th>';
                            $detail .= '<th style="width: 20%;text-align:center;border-style: outset;"  >การแก้ไข</th>';
                            $detail .= '<th style="width: 10%;text-align:center;border-style: outset;">ราคา</th>';
                            $detail .= '<th style="width: 15%;text-align:center;border-style: outset;">การรับประกัน</th>';
                            $detail .= '<th style="text-align:center;border-style: outset;">บริษัทที่ซ่อม</th>';
                            $detail .= '</tr>';
                            for($i=0;$i<14;$i++){
                                $detail .= '<tr>';
                                $detail .= '<td style="text-align:center;height: 30px;"></td>';
                                $detail .= '<td style="border-style: outset;"></td>';
                                $detail .= '<td style="border-style: outset;"></td>';
                                $detail .= '<td style="text-align:right;"></td>';
                                $detail .= '<td style="border-style: outset;"></td>';
                                $detail .= '<td></td>';
                                $detail .= '</tr>';
                            }
            }
                    // ภาษาที่ใช้งานอยู่
                        $lng = Language::name();
                    // ใส่ลงใน template
                        $content = array(
                            '/%Job%/' => 'เลขที่'.$index[0]->job_id,
                            '/{LANGUAGE}/' => $lng,
                            '/{CONTENT}/' => $billing['detail'],
                            '/{WEBURL}/' => WEB_URL,
                            '/{TITLE}/' => $billing['title'].'_'.$index[0]->product_no.'_'.$index[0]->topic,
                            '/<tr>[\r\n\s\t]{0,}<td>[\r\n\s\t]{0,}%HEAD%[\r\n\s\t]{0,}<\/td>[\r\n\s\t]{0,}<\/tr>/' => $head,
                            '/<tr>[\r\n\s\t]{0,}<td>[\r\n\s\t]{0,}%DETAIL%[\r\n\s\t]{0,}<\/td>[\r\n\s\t]{0,}<\/tr>/' => $detail,
                            '/%LOGO%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/logo.png') ? '<img  class="logo" src="'.WEB_URL.DATA_FOLDER.'/logo/logo.png">' : '',
                            '/%LOGO_2%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-01_0.png') ? '<img class="logo2"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-01_0.png">' : '',
                            '/%LOGO_3%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-03.png') ? '<img class="logo3"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-03.png">' : '',
                            '/%LOGO_4%/' => is_file(ROOT_PATH.DATA_FOLDER.'/logo/latterhead-02.png') ? '<img class="logo4"  src="'.WEB_URL.DATA_FOLDER.'/logo/latterhead-02.png">' : '',
                        );
                    //ยัดลง page
                        \Repair\Printasset\View::toPrint($content);         
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
        $file = ROOT_PATH.'modules/repair/template/'.$tempate.'.html';
        if (is_file($file)) {
            // โหลด template
            $file = file_get_contents($file);
            // parse template
            $patt = '/(.*?)<title>(.*?)<\/title>?(.*?)(<detail>(.*?)<\/detail>)?(.*?)<body>(.*?)<\/body>(.*?)/isu';
            $billing = array();
            if (preg_match($patt, $file, $match)) {
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
