<?php
/**
 * @filesource modules/index/controllers/download.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Download;

use Kotchasan\Http\Request;
use Kotchasan\Language;
use Gcms\Login;
use Kotchasan\Date;

/**
 * module=index-download
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
 
class Controller extends \Kotchasan\Controller
{
  
    public function export(Request $request)
    {
        if ($request->isReferer()) {
                $header = array();
                if (Login::checkPermission(Login::isMember(), 'report')) {
                    
                    //รับค่าไปค้นหาข้อมูล
                    $params = array(); 
                    $params['no']                  = '';
                    $params['status']              = $request->get('status')->toInt();
                    $params['product_no']          = $request->get('product_no')->toInt();
                    $params['category_id']         = $request->get('category_id')->topic();
                    $params['Model_id']            = $request->get('Model_id')->toInt();
                    $params['Type_id']             = $request->get('Type_id')->toInt();
                    $params['topic_id']            = $request->get('topic_id')->toInt();
                    $params['user_id']             = $request->get('user_id')->toInt();
                    $params['memberstatus']        = $request->get('memberstatus')->toInt();
                    $params['operator_id']         = $request->get('operator_id')->toInt();
                    $params['begindate']           = $request->get('begindate')->topic();
                    $params['enddate']             = $request->get('enddate')->topic();

                    //ส่วนหัวคอลัมน์ ใช้ eng เนื่องจากบางที csv อ่านไทยไม่ได้
                    $header['no']               = '#';
                    $header['job_id']           = Language::get('Job No.');
                    $header['status']           = Language::get('Repair status');
                    $header['begindate']        = Language::get('Received date');
                    $header['enddate']          = Language::get('End date');
                    $header['time']             = Language::get('working_hours');
                    $header['user_id']          = Language::get('Informer');
                    $header['memberstatus']     = Language::get('Member');
                    $header['product_no']       = Language::get('Registration No.');
                    $header['category_id']      = Language::get('Category');
                    //$header['model_id']         = Language::get('Model');
                    $header['type_id']          = Language::get('Type');
                    $header['topic_id']         = Language::get('Equipment');
                    $header['job_description']  = Language::get('Description');
                    
                    $header['operator_id']      = Language::get('Operator');
                    $header['send_approve2']    = Language::get('Approve');
                    $header['date_approve']     = Language::get('Approve_date');
                    $header['comment']          = Language::get('Comment');
                    $header['cost']             = Language::get('Cost');

                   
               
                    /*   1 วัน=24 ชั่วโมง / 24 ชั่วโมง=3,600 นาที / 3,600 นาที=86,400 วินาที */$i=0;
                    foreach(\index\Report\Model::toDataTable2($params) as $value){
                        
                        $time = DATE::DATEDiff($value->create_date,$value->end_date);
                        $Alltime = $time['d'].':'.$time['h'].':'.$time['i'];//.':'.$time['s']; $time['m'].':'.
                        
                        if( $Alltime <> ''){
                                $in[$i]["id"]       = $value->id;
                                $in[$i]["job_id"]   = $value->job_id;
                                $in[$i]["status"]   = $value->status;
                                $in[$i]["create_date"]  = $value->create_date;
                            //  $in[$i]["end_date"]     = $value->end_date;
                                $in[$i]["product_no"]   = $value->product_no;
                                $in[$i]["topic"]        = $value->topic;
                                $in[$i]["cost"]         = $value->cost ;
                                //$in[$i]["Alltime2"]      = $value->Alltime2;
                                $in[$i]["Alltime"]      = $Alltime;

                            $i+=1;
                        } 
                    } 

                $datas = array();
                $person  = array();
                // query report
                foreach (\Index\Download\Model::getAll($params) as $item) { 
                    $time = DATE::DATEDiff($item->create_date,$item->enddate);
                    $Alltime = $time['d'].':'.$time['h'].':'.$time['i'];//.':'.$time['s']; $time['m'].':'. 
                    
                    if($item->status == 1 || $item->status == 2 || $item->status == 8){

                    //ดึงข้อมูลลงคอลัมน์
                         ++$person['no'];
                            $person['job_id']           = $item->job_id;
                            $person['status']           = $item->repairstatus;
                            $person['begindate']        = $item->create_date;
                            $person['enddate']          = $item->enddate;
                            //$person['time']             = $item->Alltime;
                            $person['time']             =  $Alltime;
                            $person['user_id']          = $item->name;
                            $person['memberstatus']     = $item->id_card;
                            $person['product_no']       = $item->product_no;
                            $person['category_id']      = $item->catagory;
                            $person['type_id']          = $item->type;
                            $person['topic']            = $item->topic;
                            $person['job_description']  = $item->job_description;
                           
                            $person['operator_id']      = $item->name_close;
                            $person['send_approve2']    = '';
                            $person['date_approve']     = '';
                            $person['comment']          = $item->comment;
                            $person['cost']             = $item->cost;
                    }else{

                        //ดึงข้อมูลลงคอลัมน์
                        ++$person['no'];
                            $person['job_id']           = $item->job_id;
                            $person['status']           = $item->repairstatus;
                            $person['begindate']        = $item->create_date;
                            $person['enddate']          = $item->enddate;
                            //$person['time']             = $item->Alltime;
                            $person['time']             =  $Alltime;
                            $person['user_id']          = $item->name;
                            $person['memberstatus']     = $item->id_card;
                            $person['product_no']       = $item->product_no;
                            $person['category_id']      = $item->catagory;
                            $person['type_id']          = $item->type;
                            $person['topic']            = $item->topic;
                            $person['job_description']  = $item->job_description;
                           
                            $person['operator_id']      = $item->name_close;
                            $person['send_approve2']    = $item->send_approve2;
                            $person['date_approve']     = $item->date_approve;
                            $person['comment']          = $item->comment;
                            $person['cost']             = $item->cost;
                    }

                    $datas[] = $person;
                }
                //mb_convert_encoding($header, 'utf-8','Windows-874');
                // export
                return \Kotchasan\Csv::send('Report Repair Online', $header, $datas, self::$cfg->csv_language); 
                
                
            }else {
                    // 404
                    header('HTTP/1.0 404 Not Found');
            }
                exit;

        }
    }

    /**
     * ส่งออกไฟล์ csv
     *
     * @param Request $request
     */
 /*   public function export(Request $request)
    {
       if ($request->isReferer()) {
            // ค่าที่ส่งมา
            $type = $request->get('type')->toString();
            if ($type == 'report') {
                $this->report($request);
            } 
        } else {
            // 404
            header('HTTP/1.0 404 Not Found');
        }
        exit;
    }*/

    public static function w1250_to_utf8($text) {
        // map based on:
        // http://konfiguracja.c0.pl/iso02vscp1250en.html
        // http://konfiguracja.c0.pl/webpl/index_en.html#examp
        // http://www.htmlentities.com/html/entities/

       
        $map = array(
            chr(0x8A) => chr(0xA9),
            chr(0x8C) => chr(0xA6),
            chr(0x8D) => chr(0xAB),
            chr(0x8E) => chr(0xAE),
            chr(0x8F) => chr(0xAC),
            chr(0x9C) => chr(0xB6),
            chr(0x9D) => chr(0xBB),
            chr(0xA1) => chr(0xB7),
            chr(0xA5) => chr(0xA1),
            chr(0xBC) => chr(0xA5),
            chr(0x9F) => chr(0xBC),
            chr(0xB9) => chr(0xB1),
            chr(0x9A) => chr(0xB9),
            chr(0xBE) => chr(0xB5),
            chr(0x9E) => chr(0xBE),
            chr(0x80) => '&euro;',
            chr(0x82) => '&sbquo;',
            chr(0x84) => '&bdquo;',
            chr(0x85) => '&hellip;',
            chr(0x86) => '&dagger;',
            chr(0x87) => '&Dagger;',
            chr(0x89) => '&permil;',
            chr(0x8B) => '&lsaquo;',
            chr(0x91) => '&lsquo;',
            chr(0x92) => '&rsquo;',
            chr(0x93) => '&ldquo;',
            chr(0x94) => '&rdquo;',
            chr(0x95) => '&bull;',
            chr(0x96) => '&ndash;',
            chr(0x97) => '&mdash;',
            chr(0x99) => '&trade;',
            chr(0x9B) => '&rsquo;',
            chr(0xA6) => '&brvbar;',
            chr(0xA9) => '&copy;',
            chr(0xAB) => '&laquo;',
            chr(0xAE) => '&reg;',
            chr(0xB1) => '&plusmn;',
            chr(0xB5) => '&micro;',
            chr(0xB6) => '&para;',
            chr(0xB7) => '&middot;',
            chr(0xBB) => '&raquo;',
        );

      //  print_r(mb_convert_encoding(strtr($text, $map), 'utf-8','Windows-874'));
        return html_entity_decode(mb_convert_encoding(strtr($text, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');
    }
  

}
