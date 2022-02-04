<?php
/**
 * @filesource modules/repair/models/approve.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Approve;

use Gcms\Login;
use Kotchasan\Database\Sql;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=repair-approve
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * Query ข้อมูลสำหรับส่งให้กับ DataTable
     *
     * @param array $params
     *
     * @return \Kotchasan\Database\QueryBuilder
     */
    public static function toDataTable($params)
    {
        $login = Login::isMember();

        $where = array();
        if (!empty($params['operator_id'])) {
            $where[] = array('S.operator_id', $params['operator_id']);
        }
        if ($params['status'] > -1) {
            $where[] = array('S.status', $params['status']);
        }else{
            $where[] = array('S.status', 'IN', array(8 ,9, 10));
        }
        $where[] =  array('S.status', 'IN', array(8 ,9, 10));
        $where[] =  array('R.send_approve',$login['id']);
        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'max_id'))
            ->from('repair_status')
            ->groupBy('repair_id');
<<<<<<< HEAD
     
        return static::createQuery()
            ->select('R.id', 'R.job_id', 'U.name', 'U.phone', 'R.product_no',SQL::CASE_WHEN('R.types_objective','types_objective'),'R.create_date', 'S.operator_id', 'S.status') //'V.topic'
=======
            return  static::createQuery()
            ->select('R.id', 'R.job_id', 'U.name', 'U.phone', 'V.topic', 'R.create_date', 'S.operator_id', 'S.status')
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
            ->from('repair R')
            ->join(array($q1, 'T'), 'LEFT', array('T.repair_id', 'R.id'))
            ->join('repair_status S', 'LEFT', array('S.id', 'T.max_id'))
            ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
            ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
            ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
<<<<<<< HEAD
            ->where($where);
            //->andwhere(array('S.status', 'IN', array(8 ,9, 10)))
            //->andWhere(array('R.send_approve',$login['id']));
=======
            ->where($where)
           // ->andwhere(array('S.status', 'IN', array(8 ,9, 10),))
            ->andWhere(array('R.send_approve',$login['id']))
            ->order('S.status');


>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
    }
   
    /**
     * รับค่าจาก action (approve.php)
     *
     * @param Request $request
     */
    public function action(Request $request)
    {
        $ret = array();
        // session, referer, member, ไม่ใช่สมาชิกตัวอย่าง
        if ($request->initSession() && $request->isReferer() && $login = Login::isMember()) {
            if (Login::notDemoMode($login)) {
                // รับค่าจากการ POST
                $action = $request->post('action')->toString();
                // id ที่ส่งมา
                if (preg_match_all('/,?([0-9]+),?/', $request->post('id')->toString(), $match)) {
                    if ($action === 'delete' && Login::checkPermission($login, 'approve_repair')) {
                        // ลบรายการสั่งซ่อม
                        $this->db()->delete($this->getTableName('repair'), array('id', $match[1]), 0);
                        $this->db()->delete($this->getTableName('repair_status'), array('repair_id', $match[1]), 0);
                        // reload
                        $ret['location'] = 'reload';
                    } elseif ($action === 'status' && Login::checkPermission($login, array('approve_repair'))) {
                        // อ่านข้อมูลรายการที่ต้องการ
                        $index = \Repair\Detail\Model::get($request->post('id')->toInt());
                        if ($index) {
                        //ปัญหาเรียกไฟล์อื่นไม่ได้  
                            $ret['modal'] = Language::trans(\Repair\Action\View::create()->render2($index, $login));
                                               
                        }
                    }
                    
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่า JSON
        echo json_encode($ret);
    }

  
   
    /**
     * รับค่าจาก action (approve.php)
     *
     * @param Request $request
     */
}
