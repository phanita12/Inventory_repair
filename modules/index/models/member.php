<?php
/**
 * @filesource modules/index/models/member.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Member;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=member
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านข้อมูลสำหรับใส่ลงในตาราง
     *
     * @param array $params
     *
     * @return \Kotchasan\Database\QueryBuilder
     */
    public static function toDataTable($params)
    {
        $where = array();
        if ($params['status'] > -1) {
            $where[] = array('U.status', $params['status']);
        }
        return static::createQuery()
            ->select('U.id', 'U.username', 'U.name', 'U.active', 'U.social', 'U.phone', 'U.status', 'U.create_date', 'U.lastvisited', 'U.visited')
            ->from('user U')
            ->where($where);
    }

    /**
     * ฟังก์ชั่นอ่านจำนวนสมาชิกทั้งหมด
     *
     * @return int
     */
    public static function getCount()
    {
        $query = static::createQuery()
            ->selectCount()
            ->from('user')
            ->execute();
        return $query[0]->count;
    }

    /**
     * ตารางสมาชิก (member.php)
     *
     * @param Request $request
     */
    public function action(Request $request)
    {
        $ret = array();
        // session, referer, admin, ไม่ใช่สมาชิกตัวอย่าง
        if ($request->initSession() && $request->isReferer()) {
            if (Login::notDemoMode(Login::isAdmin())) {
                // รับค่าจากการ POST
                $action = $request->post('action')->toString();
                // id ที่ส่งมา
                if (preg_match_all('/,?([0-9]+),?/', $request->post('id')->toString(), $match)) {
                    if ($action === 'delete') {
                        // ลบสมาชิก
                        $this->db()->delete($this->getTableName('user'), array(
                            array('id', $match[1]),
                            array('id', '!=', 1),
                        ), 0);
                        // reload
                        $ret['location'] = 'reload';
                    } elseif ($action === 'sendpassword') {
                        // ขอรหัสผ่านใหม่
                        $query = $this->db()->createQuery()
                            ->select('id', 'username')
                            ->from('user')
                            ->where(array(
                                array('id', $match[1]),
                                array('id', '!=', 1),
                                array('social', 0),
                                array('username', '!=', ''),
                                array('active', 1),
                            ))
                            ->toArray();
                        $msgs = array();
                        foreach ($query->execute() as $item) {
                            // ส่งอีเมลขอรหัสผ่านใหม่
                            $err = \Index\Forgot\Model::execute($item['id'], $item['username']);
                            if ($err != '') {
                                $msgs[] = $err;
                            }
                        }
                        if (isset($err)) {
                            if (empty($msgs)) {
                                // ส่งอีเมล สำเร็จ
                                $ret['alert'] = Language::get('Your message was sent successfully');
                            } else {
                                // มีข้อผิดพลาด
                                $ret['alert'] = implode("\n", $msgs);
                            }
                        }
                    } elseif (preg_match('/active_([01])/', $action, $match2)) {
                        // สถานะการเข้าระบบ
                        $this->db()->update($this->getTableName('user'), array(
                            array('id', $match[1]),
                            array('id', '!=', '1'),
                        ), array(
                            'active' => (int) $match2[1],
                        ));
                        // reload
                        $ret['location'] = 'reload';
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
     * ฟังก์ชั่นอ่านจำนวนสมาชิกทั้งหมด
     *
     * @return int
     */
    public static function getMemberstatus($id)
    {
        //เช็คกลุ่มผู้ใช้งาน
          if($id == 0)$gmember = "IT Support";
        elseif($id == 1)$gmember    = "ผู้ดูแลระบบ (IT)";
        elseif($id == 2)$gmember   = "บุคคล ธุรการ";
        elseif($id == 3)$gmember   = "บัญชี การเงิน";
        elseif($id == 4)$gmember    = "จัดซื้อ";
        elseif($id == 5)$gmember    = "ซ่อมบำรุง";
        elseif($id == 6)$gmember   = "วางแผนการตลาด";
        elseif($id == 7)$gmember    = "คลังวัตถุดิบ";
        elseif($id == 8)$gmember    = "คลังสินค้าฯจัดส่ง";
        elseif($id == 9)$gmember    = "ขายฮาร์ดแวร์";
        elseif($id == 10)$gmember    = "ขายอุตสาหกรรม";
        elseif($id == 11)$gmember    = "เทคนิคQC Lab3";
        elseif($id == 12)$gmember    = "เทคนิคQC Lab6";
        elseif($id == 13)$gmember   = "ส่วนผลิต"; 
        elseif($id == 14)$gmember   = "ผลิต บรรจุ G1";
        elseif($id == 15)$gmember   = "ผลิต G2-4";
        elseif($id == 16)$gmember   = "บรรจุ G2-4";
        elseif($id == 17)$gmember   = "ผลิต บรรจุ G5-6";
        elseif($id == 18)$gmember   = "บริหาร";
        
        


        return  $gmember;
    }
    
}
