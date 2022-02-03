<?php
/**
 * @filesource modules/repair/models/printrepair.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Printrepair;

use Kotchasan\Database\Sql;


/**
 * module=repair-printrepair
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
    public static function get($id)
    {
        $repairstatus = static::createQuery()
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
        $q0_end_date = static::createQuery()
            ->select('S2.create_date')
            ->from('repair_status S2')
            ->where(array('S2.repair_id', 'R.id'))
            ->andWhere(array('S2.status',array('6','7')));
        $q0_date_approve = static::createQuery()
            ->select('S3.create_date')
            ->from('repair_status S3')
            ->where(array('S3.repair_id', 'R.id'))
            ->andWhere(array('S3.status',array('9','10')));
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
            ->select('R.*', 'U.name', 'U.username', 'V.topic' ,'U.id_card','U.id as user' // , 'S.create_date as date_approve'
           , 'S.status', 'S.comment'
            , 'S.operator_id', 'S.id status_id'
            ,array( $q0_name,'send_approve2')
            ,array( $q0_group,'s_group')
            ,array( $q2_user,'name_close')
            ,array( $catagory,'catagory')
            ,array( $model,'model')
            ,array( $type,'type')
            ,array( $repairstatus,'repairstatus')
            ,array($q0_end_date,'end_date')
            ,array($q0_date_approve,'date_approve')
            )
            ->from('repair R')
            ->join(array($q1, 'T'), 'LEFT', array('T.repair_id', 'R.id'))
            ->join('repair_status S', 'LEFT', array('S.id', 'T.max_id'))
            ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
            ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
            ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
            ->where(array('R.id', $id))
            ->order('S.id DESC');
            return static::createQuery() 
            ->from(array($sql, 'Q'))
            ->groupBy('Q.id')
            ->first();
            
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