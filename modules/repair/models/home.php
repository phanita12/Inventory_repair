<?php

/**
 * @filesource modules/repair/models/home.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Home;

use Gcms\Login;
use Kotchasan\Database\Sql;
use Kotchasan\Language;
use Gcms\Config;
use Kotchasan\Http\Request;

/**
 * โมเดลสำหรับอ่านข้อมูลแสดงในหน้า  Home
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านงานซ่อมใหม่วันนี้
     *
     * @return object
     */
    public static function getNew($login)
    {
        $where = array(
            array(Sql::DATE('S.create_date'), date('Y-m-d')),
        );
        // พนักงาน
        $isStaff = Login::checkPermission($login, array('can_manage_repair', 'can_repair'));
        if ($isStaff) {
            $status = isset(self::$cfg->repair_first_status) ? self::$cfg->repair_first_status : 1;
            $where[] = array('S.status', $status);
        } else {
            $where[] = array('R.customer_id', $login['id']);
        }
        $q1 = static::createQuery()
            ->select('repair_id', Sql::min('id', 'id'))
            ->from('repair_status')
            ->groupBy('repair_id');
        $query = static::createQuery()
            ->selectCount()
            ->from('repair_status S')
            ->join(array($q1, 'T'), 'INNER', array(array('T.repair_id', 'S.repair_id'), array('T.id', 'S.id')))
            ->where($where);
        if (!$isStaff) {
            $query->join('repair R', 'INNER', array('R.id', 'S.repair_id'));
        }
        $search = $query->toArray()
            ->execute();

        if (!empty($search)) {
            return (object) array(
                'isStaff' => $isStaff,
                'count' => $search[0]['count'],
            );
        }
        return 0;
    }
    public static function getNew3($login)
    {
        /*$where = array(
            array(Sql::DATE('S.create_date'), date('Y-m-d')),
        );*/
        // พนักงาน
        $isStaff = Login::checkPermission($login, array('can_manage_repair', 'can_repair'));
        if ($isStaff) {
            $status = isset(self::$cfg->repair_first_status) ? self::$cfg->repair_first_status : 1;
            $where[] = array('S.status', $status);
        } else {
            $where[] = array('R.customer_id', $login['id']);
        }
        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'id'))
            ->from('repair_status')
            ->groupBy('repair_id');
        $query = static::createQuery()
            ->selectCount()
            ->from('repair_status S')
            ->join(array($q1, 'T'), 'INNER', array(array('T.repair_id', 'S.repair_id'), array('T.id', 'S.id')))
            ->where($where);


        if (!$isStaff) { // if (!$isStaff) {
            $query->join('repair R', 'INNER', array('R.id', 'S.repair_id'));
        } else {
            $query->join('repair R', 'INNER', array('R.id', 'S.repair_id'));
            $q3 =  date('Y-m' . '-01 00:00:00');
            $q2 = SQL::LAST_DAY(date('Y-m-d 23:59:59'));
            $query->andwhere(SQL::BETWEEN('R.create_date', $q3, $q2));
        }
        $search = $query->toArray()
            ->execute();
        if (!empty($search)) {
            return (object) array(
                'isStaff' => $isStaff,
                'count' => $search[0]['count'],
            );
        }
        return 0;
    }
    //Test commit
    public static function getAlltoday($login)
    {

        $q3 =  date('Y-m' . '-01 00:00:00');
        $q2 = SQL::LAST_DAY(date('Y-m-d 23:59:59'));


        $where = array(
            // array(Sql::DATE('S.create_date'), date('Y-m-d')),

        );
        // พนักงาน
        $isStaff = Login::checkPermission($login, array('can_manage_repair', 'can_repair'));
        if ($isStaff) {
            $status = isset(self::$cfg->repair_first_status) ? self::$cfg->repair_first_status : 1;
            $where[] = array('S.status', $status);
        } else {
            $where[] = array('R.customer_id', $login['id']);
        }
        $q1 = static::createQuery()
            ->select('repair_id', Sql::min('id', 'id'))
            ->from('repair_status')
            ->groupBy('repair_id');
        $query = static::createQuery()
            ->selectCount()
            ->from('repair_status S')
            ->join(array($q1, 'T'), 'INNER', array(array('T.repair_id', 'S.repair_id'), array('T.id', 'S.id')))
            ->where($where);

        if (!$isStaff) { // if (!$isStaff) {
            $query->join('repair R', 'INNER', array('R.id', 'S.repair_id'));
            $query->andwhere(SQL::BETWEEN('R.create_date', $q3, $q2));
        } else {
            $query->join('repair R', 'INNER', array('R.id', 'S.repair_id'));
            $query->andwhere(SQL::BETWEEN('R.create_date', $q3, $q2));
        }
        $search = $query->toArray()
            ->execute();

        if (!empty($search)) {
            return (object) array(
                'isStaff' => $isStaff,
                'count' => $search[0]['count'],
            );
        }
        return 0;
    }

    public static function getSendapprove($login)
    {
        $where = array();
        // array(Sql::DATE('S.create_date'), date('Y-m-d')),
        //);
        // พนักงาน
        $isStaff = Login::checkPermission($login, array('can_manage_repair', 'can_repair', 'approve_manage_repair', 'approve_repair'));
        if ($isStaff) {

            $status = isset(self::$cfg->repair_status) ? self::$cfg->repair_status : 8;
            $where[] = array('S.status', 8);
        } else {
            $where[] = array('R.customer_id', $login['id']);
        }



        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'id'))
            ->from('repair_status')
            ->groupBy('repair_id');
        $query = static::createQuery()
            ->selectCount()
            //->select(date('Y-m-d'))
            ->from('repair_status S')
            ->join(array($q1, 'T'), 'INNER', array(array('T.repair_id', 'S.repair_id'), array('T.id', 'S.id')))
            ->where($where);

        if (!$isStaff) { // if (!$isStaff) {
            $query->join('repair R', 'INNER', array('R.id', 'S.repair_id'));
        } else {
            $query->join('repair R', 'INNER', array('R.id', 'S.repair_id'));
            $q3 =  date('Y-m' . '-01 00:00:00');
            $q2 = SQL::LAST_DAY(date('Y-m-d 23:59:59'));
            $query->andwhere(SQL::BETWEEN('R.create_date', $q3, $q2));
        }

        $search = $query->toArray()
            ->execute();

        if (!empty($search)) {
            return (object) array(
                'isStaff' => $isStaff,
                'count' => $search[0]['count'],
            );
        }
        return 0;
    }
    public static function getSendapprove2($login)
    {
        $where = array();
        // array(Sql::DATE('S.create_date'), date('Y-m-d')),
        //);
        // พนักงาน
        $isStaff = Login::checkPermission($login, array('approve_repair'));
        if ($isStaff) {

            $status = isset(self::$cfg->repair_status) ? self::$cfg->repair_status : 8;
            // $where[] = array('S.status', 8);
        } else {
            $where[] = array('R.customer_id', $login['id']);
        }
        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'id'))
            ->from('repair_status')
            ->groupBy('repair_id');
        $query = static::createQuery()
            ->selectCount()
            ->from('repair_status S')
            ->join(array($q1, 'T'), 'INNER', array(array('T.repair_id', 'S.repair_id'), array('T.id', 'S.id')))
            ->where($where)
            ->andwhere(array('S.status', 8));
        if (!$isStaff) {
            $query->join('repair R', 'INNER', array('R.id', 'S.repair_id'));
        }
        $search = $query->toArray()
            ->execute();

        if (!empty($search)) {
            return (object) array(
                'isStaff' => $isStaff,
                'count' => $search[0]['count'],
            );
        }
        return 0;
    }

    public static function getApprove($login)
    {
        $where = array();
        // array(Sql::DATE('S.create_date'), date('Y-m-d')),
        //);
        // พนักงาน
        $isStaff = Login::checkPermission($login, array('can_manage_repair', 'can_repair', 'approve_manage_repair', 'approve_repair'));
        if ($isStaff) {

            //$status = isset(self::$cfg->repair_first_status) ? self::$cfg->repair_first_status : 8;
            $where[] = array('S.status', 9);
        } else {
            $where[] = array('R.customer_id', $login['id']);
        }
        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'id'))
            ->from('repair_status')
            ->groupBy('repair_id');
        $query = static::createQuery()
            ->selectCount()
            ->from('repair_status S')
            ->join(array($q1, 'T'), 'INNER', array(array('T.repair_id', 'S.repair_id'), array('T.id', 'S.id')))
            ->where($where);



        if ($isStaff) { //if (!$isStaff) {
            $query->join('repair R', 'INNER', array('R.id', 'S.repair_id'));
            $q3 =  date('Y-m' . '-01 00:00:00');
            $q2 = SQL::LAST_DAY(date('Y-m-d 23:59:59'));
            $query->andwhere(SQL::BETWEEN('R.create_date', $q3, $q2));
        }
        $search = $query->toArray()
            ->execute();

        if (!empty($search)) {
            return (object) array(
                'isStaff' => $isStaff,
                'count' => $search[0]['count'],
            );
        }
        return 0;
    }


    public static function get_monthly($login)
    {
        /*  $where = array();
        $isStaff = Login::checkPermission($login, array('can_manage_repair', 'can_repair','approve_manage_repair', 'approve_repair'));
        if ($isStaff) {
            //$status = isset(self::$cfg->repair_first_status) ? self::$cfg->repair_first_status : 1;
           // $where[] = array('S.status', $status);

           //var_dump($isStaff);
        } else {
            $where[] = array('R.customer_id', $login['id']);
        }*/

        return  static::createQuery()
            ->select(
                (array(
                    Sql::YEAR('create_date', 'YEAR'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 1, 1, 0), '1'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 2, 1, 0), '2'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 3, 1, 0), '3'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 4, 1, 0), '4'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 5, 1, 0), '5'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 6, 1, 0), '6'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 7, 1, 0), '7'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 8, 1, 0), '8'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 9, 1, 0), '9'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 10, 1, 0), '10'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 11, 1, 0), '11'),
                    Sql::SUM(Sql::IF(Sql::MONTH('create_date'), 12, 1, 0), '12')
                ))
            )
            ->from('repair')
            // ->where($where)
            ->toArray()
            ->execute();
    }
    public static function get_status($login)
    {
        /*  $where = array();
        if (!empty($params['operator_id'])) {
            $where[] = array('S.operator_id', $params['operator_id']);
        }
        if ($params['status'] > -1) {
            $where[] = array('S.status', $params['status']);
        }

        $where = array();
        $isStaff = Login::checkPermission($login, array('can_manage_repair', 'can_repair','approve_manage_repair', 'approve_repair'));
        if ($isStaff) {
            //$status = isset(self::$cfg->repair_first_status) ? self::$cfg->repair_first_status : 0;
            //$where[] = array('S.status', $status);

           //var_dump($isStaff);
        } else {
            $where[] = array('R.customer_id', $login['id']);
        } */

        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'max_id'))
            ->from('repair_status')
            ->groupBy('repair_id');


        return static::createQuery()
            ->select(
                (array(
                    Sql::YEAR('R.create_date', 'YEAR'),
                    Sql::SUM(Sql::IF('S.status', 1, 1, 0), '1'),
                    Sql::SUM(Sql::IF('S.status', 2, 1, 0), '2'),
                    Sql::SUM(Sql::IF('S.status', 3, 1, 0), '3'),
                    Sql::SUM(Sql::IF('S.status', 4, 1, 0), '4'),
                    Sql::SUM(Sql::IF('S.status', 5, 1, 0), '5'),
                    Sql::SUM(Sql::IF('S.status', 6, 1, 0), '6'),
                    Sql::SUM(Sql::IF('S.status', 7, 1, 0), '7'),
                    Sql::SUM(Sql::IF('S.status', 8, 1, 0), '8'),
                    Sql::SUM(Sql::IF('S.status', 9, 1, 0), '9'),
                    Sql::SUM(Sql::IF('S.status', 10, 1, 0), '10'),
                    Sql::count('R.create_date', 'ALL')


                ))
            )
            ->from('repair R')
            ->join(array($q1, 'T'), 'LEFT', array('T.repair_id', 'R.id'))
            ->join('repair_status S', 'LEFT', array('S.id', 'T.max_id'))
            ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
            ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
            ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
            ->where(array(SQL::MONTH('R.create_date'), SQL::MONTH(date('Y-m-d H:i:s'))))
            ->toArray()
            ->execute();
    }

    public static function get_category()
    {
        /*  $where = array();
        if (!empty($params['operator_id'])) {
            $where[] = array('S.operator_id', $params['operator_id']);
        }
        if ($params['status'] > -1) {
            $where[] = array('S.status', $params['status']);
        }*/

        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'max_id'))
            ->from('repair_status')
            ->groupBy('repair_id');

        return static::createQuery()
            ->select(
                (array(
                    Sql::SUM(Sql::IF('V.category_id', 2, 1, 0), '2'),
                    Sql::SUM(Sql::IF('V.category_id', 3, 1, 0), '3'),
                    Sql::SUM(Sql::IF('V.category_id', 4, 1, 0), '4'),
                ))
            )
            ->from('repair R')
            ->join(array($q1, 'T'), 'LEFT', array('T.repair_id', 'R.id'))
            ->join('repair_status S', 'LEFT', array('S.id', 'T.max_id'))
            ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
            ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
            ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
            ->where(array(SQL::MONTH('R.create_date'), SQL::MONTH(date('Y-m-d H:i:s'))))
            ->toArray()
            ->execute();
    }
    public static function get_type()
    {
        /*  $where = array();
            if (!empty($params['operator_id'])) {
                $where[] = array('S.operator_id', $params['operator_id']);
            }
            if ($params['status'] > -1) {
                $where[] = array('S.status', $params['status']);
            }*/

        $q1 = static::createQuery()
            ->select('repair_id', Sql::MAX('id', 'max_id'))
            ->from('repair_status')
            ->groupBy('repair_id');

        return static::createQuery() //return
            ->select(
                (array(
                    Sql::SUM(Sql::IF('V.type_id', 1, 1, 0), '1'),
                    Sql::SUM(Sql::IF('V.type_id', 2, 1, 0), '2'),
                    Sql::SUM(Sql::IF('V.type_id', 3, 1, 0), '3'),
                    Sql::SUM(Sql::IF('V.type_id', 4, 1, 0), '4'),
                    Sql::SUM(Sql::IF('V.type_id', 5, 1, 0), '5'),
                    Sql::SUM(Sql::IF('V.type_id', 6, 1, 0), '6'),
                ))
            )
            ->from('repair R')
            ->join(array($q1, 'T'), 'LEFT', array('T.repair_id', 'R.id'))
            ->join('repair_status S', 'LEFT', array('S.id', 'T.max_id'))
            ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
            ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
            ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
            // ->where($where)
            ->where(array(SQL::MONTH('R.create_date'), SQL::MONTH(date('Y-m-d H:i:s'))))
            ->toArray()
            ->execute();
        // print_r( $a->text());
    }

    /**
     * รับค่าจาก settings.php
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // session, token, can_config, ไม่ใช่สมาชิกตัวอย่าง
        if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
            if (Login::notDemoMode($login) && Login::checkPermission($login, 'can_config')) {
                // โหลด config
                $config = Config::load(ROOT_PATH . 'settings/config.php');
                $config->repair_first_status = $request->post('repair_first_status')->toInt();
                $config->repair_job_no = $request->post('repair_job_no')->topic();
                // save config
                if (Config::save($config, ROOT_PATH . 'settings/config.php')) {
                    // คืนค่า
                    $ret['alert'] = Language::get('Saved successfully');
                    $ret['location'] = 'reload';
                    // เคลียร์
                    $request->removeToken();
                } else {
                    // ไม่สามารถบันทึก config ได้
                    $ret['alert'] = sprintf(Language::get('File %s cannot be created or is read-only.'), 'settings/config.php');
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่าเป็น JSON
        echo json_encode($ret);
    }
}
