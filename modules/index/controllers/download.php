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
                    $header['product_no']       = Language::get('Serial/Registration No.');
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

                $datas = array();
                $person  = array();
                // query report
                foreach (\Index\Download\Model::getAll($params) as $item) {  
                    
                    if($item->status == 1 || $item->status == 2 || $item->status == 8){

                    //ดึงข้อมูลลงคอลัมน์
                         ++$person['no'];
                            $person['job_id']           = $item->job_id;
                            $person['status']           = $item->repairstatus;
                            $person['begindate']        = $item->create_date;
                            $person['enddate']          = $item->enddate;
                            $person['time']             = $item->Alltime;
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
                            $person['time']             = $item->Alltime;
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

                mb_convert_encoding($header, 'Windows-874','utf-8');
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

    /**
     * ส่งออกรายชื่อนักเรียน.
     *
     * @param Request $request
     */
   /* public function report(Request $request)
    {

        var_dump('MC');

        $header = array();
        $header[] = Language::get('status');
        $header[] = Language::get('product_no');
        //$header[] = Language::trans('{LNG_Name}');
        $header[] = Language::get('category_id');
        $header[] = Language::get('model_id');
        $header[] = Language::get('type_id');
        $header[] = Language::get('topic_id');
        $header[] = Language::trans('user_id');
        $header[] = Language::trans('memberstatus');
        $header[] = Language::trans('operator_id');
        $header[] = Language::trans('begindate');
        $header[] = Language::trans('enddate');

        $params = array();
        foreach (Language::get('SCHOOL_CATEGORY') as $k => $v) {
            $params[$k] = $request->get($k)->toInt();
            $header[] = $v;
        }
       $sexes = Language::get('SEXES');
        $category = \School\Category\Model::init();
        $datas = array();
        foreach (\School\Download\Model::student($params, $request->get('active')->toInt()) as $item) {
            foreach ($params as $k => $v) {
                $item[$k] = $category->get($k, $item[$k]);
            }
            if (isset($sexes[$item['sex']])) {
                $item['sex'] = $sexes[$item['sex']];
            }
            $datas[] = $item;
        }
        return \Kotchasan\Csv::send('report', $header, $datas, self::$cfg->csv_language);
    }*/

}
