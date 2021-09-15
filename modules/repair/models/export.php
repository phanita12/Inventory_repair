<?php
/**
 * @filesource modules/repair/models/export.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Export;

use Kotchasan\Database\Sql;


/**
 * module=repair-export
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านรายละเอียดการทำรายการจาก $id
     *
     * @param int $id
     *
     * @return object
     */

     
    public static function get($index)
    {
        var_dump('export');
        //var_dump($begin,$enddate,$status,$operator_id,$memberstatus,$product_no,$topic_id,$type_id,$category_id,$user_id);

        $where = array();
        /*if (!empty($product_no)) {
           // $where[] = array('R.product_no', $params['product_no']); 
            $where[] =array('R.product_no', 'LIKE', '%'.$product_no.'%');
        }
        if (!empty($operator_id)) {
            $where[] = array('S.operator_id', $operator_id);
        }
        if ($status > -1) {
            $where[] = array('S.status', $status);
        }
        if (!empty($user_id)) {
            $where[] = array('R.customer_id', $user_id);
        }
        if ($memberstatus > -1) {
            $where[] = array('U.status', $memberstatus); 
        }
        if ($begin != '' || $enddate != '') {
                $where[] = Sql::BETWEEN('R.create_date', $begin." 00:00:00", $enddate." 23:59:59");
        }
       if (!empty($category_id)) {
            $where[] = array('V.category_id', $category_id); 
        }
       /* if (!empty($params['model_id'])) {
            $where[] = array('V.model_id', $params['model_id']);   
        }*/
        if (!empty($type_id)) {
            $where[] = array('V.type_id', $type_id); 
        }
        if (!empty($topic_id)) {
            $where[] = array('V.id', $topic_id); 
        }

        $endtime = static::createQuery()
            ->select('create_date')
            ->from('repair_status')
            ->where(array('id','T.max_id'));
        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'max_id'))
            ->from('repair_status')
            ->groupBy('repair_id');

        return static::createQuery() //return  $a =
            ->select('R.id', 'R.job_id','S.status', 'R.create_date'  //,'S.create_date as enddate'
            , 'R.product_no'
            , 'V.topic'
        // , 'U.name'
            ,'S.cost'//,array( $endtime,'endtime') //'S.operator_id',
            ,SQL::CONCAT(array(SQL::IFOVER(SQL::TIMESTAMPDIFF('DAY','R.create_date',$endtime),0,SQL::TIMESTAMPDIFF('DAY','R.create_date',$endtime),0),SQL::IFHOUR(SQL::TIMESTAMPDIFF('SECOND','R.create_date',$endtime),SQL::TIMESTAMPDIFF('SECOND','R.create_date',$endtime),SQL::TIMESTAMPDIFF('DAY','R.create_date',$endtime),'')),'Alltime', ':') 
            //,SQL::CONCAT(array(SQL::IFOVER(SQL::TIMESTAMPDIFF('DAY','R.create_date',$endtime),0,SQL::TIMESTAMPDIFF('DAY','R.create_date',$endtime),0),SQL::IFHOUR(SQL::TIMESTAMPDIFF('SECOND','R.create_date',$endtime),SQL::TIMESTAMPDIFF('SECOND','R.create_date',$endtime),SQL::TIMESTAMPDIFF('DAY','R.create_date',$endtime),0),(SQL::IFMINUTES(SQL::TIMESTAMPDIFF('SECOND','R.create_date',$endtime),SQL::TIMESTAMPDIFF('SECOND','R.create_date',$endtime),SQL::TIMESTAMPDIFF('HOUR','R.create_date',$endtime),0)),(SQL::IFSECOND(SQL::TIMESTAMPDIFF('SECOND','R.create_date',$endtime),SQL::TIMESTAMPDIFF('MINUTE','R.create_date',$endtime)))),'Alltime', ':') 
            )
            ->from('repair R')
            ->join(array($q1, 'T'), 'LEFT', array('T.repair_id', 'R.id'))
            ->join('repair_status S', 'LEFT', array('S.id', 'T.max_id'))
            ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
            ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
            ->join('user U', 'LEFT', array('U.id', 'R.customer_id'));
            //->where($where);

          //  print_r($a->text());


       /* $repairstatus = static::createQuery()
        ->select('C.topic')
        ->from('category C')
        ->where(array('C.category_id', 'S.status'))
        ->andwhere(array('C.type', 'repairstatus'));
        $catagory = static::createQuery()
            ->select('C.topic')
            ->from('category C')
            ->where(array('C.category_id', 'V.category_id'))
            ->andwhere(array('C.type', 'category_id'));
        $model = static::createQuery()
            ->select('C.topic')
            ->from('category C')
            ->where(array('C.category_id', 'V.model_id'))
            ->andwhere(array('C.type', 'model_id'));
        $type= static::createQuery()
            ->select('C.topic')
            ->from('category C')
            ->where(array('C.category_id', 'V.type_id'))
            ->andwhere(array('C.type', 'type_id'));
        $q0_name = static::createQuery()
            ->select('U1.name as send_approve2')
            ->from('user U1')
            ->where(array('U1.id', 'R.send_approve'));
        $q0_group = static::createQuery()    
            ->select('U3.status as s_group')
            ->from('user U3')
            ->where(array('U3.id', 'R.customer_id'));
        $q2_user = static::createQuery()    
            ->select('U4.name as name_close')
            ->from('user U4')
            ->where(array('U4.id', 'S.operator_id'));
        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'max_id'))
            ->from('repair_status')
            ->groupBy('repair_id');
        $sql = static::createQuery()
            ->select('R.*', 'U.name', 'U.username', 'V.topic' ,'U.id_card','U.id as user'
            , 'S.create_date as date_approve', 'S.status', 'S.comment'
            , 'S.operator_id', 'S.id status_id'
            ,array( $q0_name,'send_approve2')
            ,array( $q0_group,'s_group')
            ,array( $q2_user,'name_close')
            ,array( $catagory,'catagory')
            ,array( $model,'model')
            ,array( $type,'type')
            ,array( $repairstatus,'repairstatus')
            
            )
            ->from('repair R')
            ->join(array($q1, 'T'), 'LEFT', array('T.repair_id', 'R.id'))
            ->join('repair_status S', 'LEFT', array('S.id', 'T.max_id'))
            ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
            ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
            ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
            //->where(array('R.id', $id))
           // ->where($where)
            ->order('S.id DESC');
        $a = static::createQuery() //return
            ->from(array($sql, 'Q'))
            ->groupBy('Q.id')
            ->first(); */

           
            
    }

    public static function getapp($id)
    {
        $repairstatus = static::createQuery()
        ->select('C.topic')
        ->from('category C')
        ->where(array('C.category_id', 'S.status'))
        ->andwhere(array('C.type', 'repairstatus'));
            return static::createQuery()
            ->select('S.status' ,'S.comment','S.create_date as date_approve'
            ,array( $repairstatus,'repairstatus')
            )
            ->from('repair R')
            ->join('repair_status S', 'LEFT', array('S.repair_id', 'R.id'))
            ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
            ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
            ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
            ->where(array('R.id', $id))
            ->order('S.id DESC')
            ->execute();
            
    }

}