<?php
/**
 * @filesource modules/repair/models/autocomplete.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Autocomplete;

use Gcms\Login;
use Kotchasan\Http\Request;

/**
 * ค้นหา สำหรับ autocomplete
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * ค้นหา Inventory สำหรับ autocomplete
     * เฉพาะรายการที่ตัวเองรับผิดชอบ และ ที่ไม่มีผู้รับผิดชอบ
     * คืนค่าเป็น JSON
     *
     * @param Request $request
     */
    public function find(Request $request)
    {
        if ($request->initSession() && $request->isReferer() && Login::isMember()) {
            try {
                // ข้อมูลที่ส่งมา
                if ($request->post('topic')->exists()) {
                    $search = $request->post('topic')->topic();
                    $order = 'V.topic';
                } elseif ($request->post('product_no')->exists()) {
                    $search = $request->post('product_no')->topic();
                    $order = 'I.product_no';
                }elseif ($request->post('category')->exists()) {
                    $search = $request->post('category')->topic();
                    $order = 'topic';
                }elseif ($request->post('type_repair')->exists()) {
                    $search = $request->post('type_repair')->topic();
                    $order = 'topic';
                }
                    
                //ส่วนที่จะเอาคำมาค้นหา
                $where = array();
                if (isset($search)) {
                    $where[] = array($order, 'LIKE', "%$search%");
                }

                 // query1 เลือกรายการแรกใส่รายการที่เหลือ
                 $sq1_catagory =  $this->db()->createQuery()
                    ->select('C1.topic as category')
                    ->from('category C1')
                    ->where(array('C1.category_id', 'V.category_id'))
                    ->andWhere(array('C1.type','category_id')
                   );

                $sq1_type =  $this->db()->createQuery()
                   ->select('C1.topic as category')
                   ->from('category C1')
                   ->where(array('C1.category_id', 'V.type_id'))
                   ->andWhere(array('C1.type','type_id')
                  );

                $sq1_model =  $this->db()->createQuery()
                   ->select('C1.topic as category')
                   ->from('category C1')
                   ->where(array('C1.category_id', 'V.model_id'))
                   ->andWhere(array('C1.type','model_id')
                  );
                    
                 //-------------------------------   
                 $query = $this->db()->createQuery()
                 ->select('V.topic'
                 ,'I.product_no'
                 ,array($sq1_catagory,'category')
                 ,array($sq1_type,'type_repair')
                 ,array($sq1_model,'model')
                 ,'V.category_id'
                 ,'V.model_id'
                 ,'V.type_id'
                 ,)
                 ->from('inventory V')
                 ->join('inventory_items I', 'INNER', array('I.inventory_id', 'V.id'))
                 ->where($where)
                 ->andWhere(array('v.inuse','1'))
                 ->limit($request->post('count', 20)->toInt())
                 ->toArray();


               // query2 เลือกรายการแบบเยกอิสระ ยกเว้น topic / product_no
               if ($request->post('category')->exists()) {
                    $query = $this->db()->createQuery()
                    ->select( 'topic as category', 'category_id')
                    ->from('category')
                    ->where($where)
                    ->andWhere(array('type','category_id'))
                    ->order('topic')  
                    ->toArray();
               }elseif ($request->post('type_repair')->exists()) {
                    $query = $this->db()->createQuery()
                    ->select( 'topic as type_repair', 'category_id')
                    ->from('category')
                    ->where($where)
                    ->andWhere(array('type','type_id'))
                    ->order('topic')  
                    ->toArray();
                }elseif ($request->post('model')->exists()) {
                    $query = $this->db()->createQuery()
                    ->select( 'topic as model', 'category_id')
                    ->from('category')
                    ->where($where)
                    ->andWhere(array('type','model_id'))
                    ->order('topic')  
                    ->toArray();
                   
                }elseif ($request->post('approve')->exists()) {

                   /* $sqlgmember = \Kotchasan\Model::createQuery()
                    ->select('U1.status')
                    ->from('user U1');*/
                    //->where(array('U1.id', 'U2.id'));
                 
                    //$gmember='';
                  //  $arr_getstatusmember = Login::isMember();
                   // $getstatusmember = $arr_getstatusmember['status'];
                    $login = Login::isMember();

                            $query = $this->db()->createQuery()
                            ->select('U2.name as approve','U2.id as approve_id',)
                            ->from('user U2')
                            ->where($where)
                            ->andwhere(array('U2.id',$login['head']))       
                            //->andwhere(array('U2.status',$getstatusmember))
                            //->order('U2.name')  
                            ->toArray();  

                          // print_r($query); //$login['id']
                            
                }
                if (isset($order)) {
                    $query->order($order);
                }
                $result = $query->execute();
                if (!empty($result)) {
                    // คืนค่า JSON
                    echo json_encode($result);
                }
            } catch (\Kotchasan\InputItemException $e) {
            }

          
        }

              
    }
}
