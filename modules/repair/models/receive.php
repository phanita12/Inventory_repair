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
use Kotchasan\Database\Sql;

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
          $q0_name = static::createQuery()
                ->select('U1.name as send_approve2')
                ->from('user U1')
                ->where(array('U1.id', 'R.send_approve'));

                return static::createQuery()
                ->from('repair R')
                ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
                ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
                ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
                ->where(array('R.id', $id))
                ->first('R.*', 'V.topic'
                            ,array($sq1_catagory,'category')
                            ,array($sq1_type,'type_repair')
                            ,array($sq1_model,'model')
                            ,'V.category_id'
                            ,'V.model_id'
                            ,'V.type_id'
                            ,array( $q0_name,'send_approve2')
                        ) ;
        }
    }

     /**
     * ตรวจสอบรถว่าง
     * คืนค่า true ถ้ารถว่าง
     * ไม่ว่าง คืนค่า false
     *
     * @param array $save
     * @param int   $id
     *
     * @return bool
     */
    public static function availability($repair, $id = 0)
    {
        $where = array(
            array('product_no', $repair['product_no']),
           // array('status', 1),
        );
        if ($id > 0) {
            $where[] = array('id', '!=', $id);
        }
        $search = \Kotchasan\Model::createQuery()
            ->from('carbooking')
            ->where($where)
            ->andWhere(array(
                Sql::create("('$repair[end_date]' BETWEEN `begin_date` AND `end_date`)"),
                Sql::create("('$repair[begin_date]' BETWEEN `begin_date` AND `end_date`)"),
                Sql::create("(`begin_date` BETWEEN '$repair[begin_date]' AND '$repair[end_date]' AND `end_date` BETWEEN '$repair[begin_date]' AND '$repair[end_date]')"),
            ), 'OR')
            ->first('id');
            return $search === false ? false : true ;
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
                        'product_no' => $request->post('product_no')->topic(),
                        'appraiser' => 0, //คชจ.
                        'send_approve' => $request->post('approve_id')->topic(),     
                        'types_objective' => $request->post('type_work')->topic(),
                        'begin_date' => $request->post('begin')->topic(),
                        'end_date' => $request->post('end')->topic(),  
                        'easy_pass' => $request->post('easy_pass')->topic(), 
                        'destination' => $request->post('destination')->topic(), 
                        'job_description' => $request->post('job_description')->textarea(),
                    );
                // ตาราง
                    $repair_table = $this->getTableName('repair');
                    $repair_status_table = $this->getTableName('repair_status');
                 // Database
                    $db = $this->db();
                //check input     
                    if (empty($repair['begin_date']) && empty($repair['product_no'])) {
                        // ไม่ได้กรอก begin
                        $ret['ret_begin'] = 'Please fill in';
                    } else {
                        $repair['begin_date'] .= ':01';
                    }
                    if (empty($repair['end_date']) && empty($repair['begin_date'])  && empty($repair['product_no']) ) {
                        // ไม่ได้กรอก end
                        $ret['ret_end'] = 'Please fill in';
                    } else {
                        $repair['end_date'] .= ':00';
                    }
                    
                    if (empty($repair['product_no'] ) ) { 
                        // ไม่พบรายการที่เลือก
                        $ret['ret_product_no'] = Language::get('Please select from the search results');
                    } 
                    if (empty($repair['types_objective'])  && empty($repair['types_objective']) && empty($repair['begin_date']) && empty($repair['end_date']) && empty($repair['product_no'])) { 
                        // ไม่พบรายการที่เลือก
                        $ret['alert']  = Language::get('Please select Types of objective');
                    }
                    if (empty($repair['easy_pass'])  && empty($repair['begin_date']) && empty($repair['end_date'])  && empty($repair['product_no'])) { 
                        // ไม่พบรายการที่เลือก
                        $ret['alert']  = Language::get('Please select Easy Pass');
                    }     
                    if(empty($repair['destination'] ) && empty($repair['begin_date']) && empty($repair['end_date']) && empty($repair['product_no']) ){ 
                        $ret['ret_destination'] = Language::get('Please Fill in destination');
                    }           
                    if(empty($repair['send_approve'] ) && empty($repair['begin_date']) && empty($repair['end_date']) && empty($repair['product_no']) ){ 
                        $ret['ret_approve'] = Language::get('Please select chief of work');
                    }
                // ตรวจสอบรายการที่เลือก
                $index = self::get($request->post('id')->toInt(), $repair['product_no']);
                // ตรวจสอบรถว่างในช่วงเวลานั้น
                        if (   $repair['begin_date'] < $repair['end_date'] ) {
                            if (self::availability($repair, isset($index->id) ? $index->id : 0)) {
                                $ret['ret_begin'] = Language::get('Vehicles cannot be used at the selected time');
                            }
                        } else if($repair['product_no'] != ''){
                            // วันที่ ไม่ถูกต้อง
                            $ret['ret_end'] = Language::get('End date must be greater than begin date');
                        }   
                if(empty($ret)){
                // สามารถเข้าเมนูนี้ได้
                    $can_manage_repair = Login::checkPermission($login, 'can_manage_car_booking');
                // ไม่พบรายการที่แก้ไข
                    if (!$index || $index->id > 0 && ($login['id'] != $index->customer_id && !$can_manage_repair)) { 
                         $ret['alert'] = Language::get('Sorry, Item not found It&#39;s may be deleted');
                    } else {
                
                        if ($index->id == 0) {
                                // job_id
                                $repair['job_id'] = \Index\Number\Model::get(0, 'repair_job_no', $repair_table, 'id');
                                $repair['customer_id'] = $login['id'];
                                $repair['create_date'] = date('Y-m-d H:i:s');
                // บันทึกรายการแจ้งซ่อม
                                      $log = array(
                                        'repair_id' => $db->insert($repair_table, $repair),
                                        'member_id' => $login['id'],
                                        'comment' => $request->post('comment')->topic(),
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
