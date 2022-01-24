<?php
/**
 * @filesource modules/repair/models/receive.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Receive;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;
use Kotchasan\File;
/**
 * module=repair-receive
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านข้อมูลรายการที่เลือก
     * ถ้า $id = 0 หมายถึงรายการใหม่
     * คืนค่าข้อมูล object ไม่พบคืนค่า null
     *
     * @param int $id ID
     * @param string $product_no
     *
     * @return object|null
     */
    public static function get($id, $product_no = '')
    {
        if (empty($id)) {
            // ใหม่
            
            if ($product_no == '') {
                return (object) array(
                    'id' => 0,
                    'product_no' => '',
                    'topic' => '',
                    'job_description' => '',
                    'comment' => '',
                    'status_id' => 0,
                    'category_id' => '',
                    'type_id' => '',
                    'model_id' => '',
                    'urgency' => '',
                    'category' => '',
                    'type_repair' => '',
                    'model' => '',
                    'approve_id' => '',
                    'approve' => '',
                );
            } else {
                return static::createQuery()
                    ->from('inventory_items I')
                    ->where(array('I.product_no', $product_no))
                    ->first('0 id', 'I.product_no');
            }

           
        } else {
            // แก้ไข

            $sq1_catagory =  static::createQuery()
                ->select('C1.topic as category')
                ->from('category C1')
                ->where(array('C1.category_id', 'V.category_id'))
                ->andWhere(array('C1.type','category_id')
           );

            $sq1_type =  static::createQuery()
                ->select('C1.topic as category')
                ->from('category C1')
                ->where(array('C1.category_id', 'V.type_id'))
                ->andWhere(array('C1.type','type_id')
          );

            $sq1_model =  static::createQuery()
                ->select('C1.topic as category')
                ->from('category C1')
                ->where(array('C1.category_id', 'V.model_id'))
                ->andWhere(array('C1.type','model_id')
          );

                return static::createQuery()
                ->from('repair R')
                ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
                ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
                ->where(array('R.id', $id))
                ->first('R.*', 'V.topic'
                            ,array($sq1_catagory,'category')
                            ,array($sq1_type,'type_repair')
                            ,array($sq1_model,'model')
                            ,'V.category_id'
                            ,'V.model_id'
                            ,'V.type_id'
                        ) ;
        }
    }
    
  
    /**
     * บันทึกค่าจากฟอร์ม (receive.php)
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {       
        $ret = array();
        // session, token, member
        if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
            try {
                // รับค่าจากการ POST
                $repair = array(
                    'job_description' => $request->post('job_description')->textarea(),
                    'product_no' => $request->post('product_no')->topic(),
                    'appraiser' => 0,
                    'send_approve' => $request->post('approve_id')->topic(),     
                );
                    
                if ($repair['product_no'] == '' ) {
                    // ไม่พบรายการพัสดุที่เลือก
                    $ret['ret_product_no'] = Language::get('Please select from the search results');
                }elseif($repair['send_approve'] == ''){
                    $ret['ret_approve'] = Language::get('Please select from the search results');
                }else {

                    
                    // สามารถจัดการรายการซ่อมได้
                    $can_manage_repair = Login::checkPermission($login, 'can_manage_repair');
                    // ตรวจสอบรายการที่เลือก
                    $index = self::get($request->post('id')->toInt(), $repair['product_no']);
                    
                    if (!$index || $index->id > 0 && ($login['id'] != $index->customer_id && !$can_manage_repair)) {
                        // ไม่พบรายการที่แก้ไข
                        $ret['alert'] = Language::get('Sorry, Item not found It&#39;s may be deleted');
                    } else {
                        // ตาราง
                        $repair_table = $this->getTableName('repair');
                        $repair_status_table = $this->getTableName('repair_status');
                        // Database
                        $db = $this->db();
                        if ($index->id == 0) {
                                // job_id
                                $repair['job_id'] = \Index\Number\Model::get(0, 'repair_job_no', $repair_table, 'job_id');
                                $repair['customer_id'] = $login['id'];
                                $repair['create_date'] = date('Y-m-d H:i:s');
                                if (empty($ret) || $ret == "") {
                                    // อัปโหลดไฟล์
                                    $dir = ROOT_PATH.DATA_FOLDER.'file_attachment_user/';
                                    foreach ($request->getUploadedFiles() as $item => $file) {
                                        if ($item == 'file_attachment_user') {
                                        //if (preg_match('/^E-signature)$/', $item, $match)) {
                                            /* @var $file \Kotchasan\Http\UploadedFile */
                                            if (!File::makeDirectory($dir)) {
                                                // ไดเรคทอรี่ไม่สามารถสร้างได้
                                                $ret['ret_file_file_attachment_user'.$item] = sprintf(Language::get('Directory %s cannot be created or is read-only.'), DATA_FOLDER.'file_attachment_user/');
                                            } elseif ($file->hasUploadFile()) {
                                                if (!$file->validFileExt(array('jpg', 'jpeg', 'png'))) {
                                                    // ชนิดของไฟล์ไม่รองรับ
                                                    $ret['ret_file_file_attachment_user'] = Language::get('The type of file is invalid'); //.$match[1]]
                                                } else {
                                                    try {
                                                       // $file->moveTo($dir.$match[1].'.png');
                                                       $file->moveTo($dir.'U_'.$repair['job_id'].'.jpg');
                                                    } catch (\Exception $exc) {
                                                        // ไม่สามารถอัปโหลดได้
                                                        $ret['ret_file_file_attachment_user'] = Language::get($exc->getMessage()); //.$match[1]]
                                                    }
                                                }
                                            } elseif ($file->hasError()) {
                                                // ข้อผิดพลาดการอัปโหลด
                                                $ret['ret_file_file_attachment_user']  = Language::get($file->getErrorMessage()); //'.$match[1]] 
                                            }
                                        }
                                    }
                                }
                                
                              /*   // อัปโหลดไฟล์
                                $dir = ROOT_PATH.DATA_FOLDER.'file_attachment_user/';  
                                    /* @var $file \Kotchasan\Http\UploadedFile   
                                foreach ($request->getUploadedFiles() as $item => $file) {
                                    if ($item == 'file_attachment_user') {
                        
                                        if ($file->hasUploadFile()) {
                                            if (!File::makeDirectory($dir)) {
                                                // ไดเรคทอรี่ไม่สามารถสร้างได้
                                                $ret['ret_'.$item] = sprintf(Language::get('Directory %s cannot be created or is read-only.'), DATA_FOLDER.'file_attachment_user/');
                                            }else {
                                                try {
                                                    $file->resizeImage(array('jpg', 'jpeg', 'png'), $dir, 'U_'.$repair['job_id'].'.jpg', self::$cfg->inventory_w);//$save['id']                                          
                                                } catch (\Exception $exc) {
                                                    // ไม่สามารถอัปโหลดได้
                                                    $ret['ret_'.$item] = Language::get($exc->getMessage());
                                                }
                                            }
                                        } elseif ($file->hasError()) {
                                            // ข้อผิดพลาดการอัปโหลด
                                            $ret['ret_'.$item] = Language::get($file->getErrorMessage());

                                        }
                                    }

                                }            */              
                            
                            // บันทึกรายการแจ้งซ่อม
                            $log = array(
                                'repair_id' => $db->insert($repair_table, $repair),
                                'member_id' => $login['id'],
                                'comment' => $request->post('comment')->topic(),
                                //'urgency' => $request->post('urgency')->topic(),
                                'status' => isset(self::$cfg->repair_first_status) ? self::$cfg->repair_first_status : 1,
                                'create_date' => $repair['create_date'],
                                'operator_id' => 0,
                            );
                                                
                            // บันทึกประวัติการทำรายการ แจ้งซ่อม
                            $db->insert($repair_status_table, $log);
                            // ใหม่ ส่งอีเมลไปยังผู้ที่เกี่ยวข้อง
                            $ret['alert'] = \Repair\Email\Model::send($log['repair_id']);    
                        } else {

                        
                            // แก้ไขรายการแจ้งซ่อม
                            $db->update($repair_table, $index->id, $repair);
                            // คืนค่า
                            $ret['alert'] = Language::get('Saved successfully');

                        }
                        if ($can_manage_repair && $index->id > 0) {
                            // สามารถจัดการรายการซ่อมได้
                            $ret['location'] = $request->getUri()->postBack('index.php', array('module' => 'repair-setup', 'id' => null));
                        } else {
                            // ใหม่
                            $ret['location'] = $request->getUri()->postBack('index.php', array('module' => 'repair-history', 'id' => null));
                        }
                        // clear
                        $request->removeToken();
                    }
                }
            } catch (\Kotchasan\InputItemException $e) {
                $ret['alert'] = $e->getMessage();
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่าเป็น JSON
        echo json_encode($ret);
    }
}
