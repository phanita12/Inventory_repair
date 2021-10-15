<?php
/**
 * @filesource modules/index/models/reportgraph.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\reportgraph;

use Gcms\Login;
use Kotchasan\Database\Sql;
use Kotchasan\Http\Request;

/**
 * module=reportg
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * Query ข้อมูล report
     *
     * @param array $params
     *
     * @return array
     */
    public static function get($params)
    {
        var_dump('BB');

        $where = array(
            array('B.family_id', $params['family_id']),
            array('B.member_id', $params['member_id']),
        );
        if ($params['tag'] > 0) {
            $where[] = array('B.tag', $params['tag']);
        }
        if (!empty($params['from'])) {
            $where[] = array(Sql::DATE('B.create_date'), '>=', $params['from']);
        }
        if (!empty($params['to'])) {
            $where[] = array(Sql::DATE('B.create_date'), '<=', $params['to']);
        }
        return static::createQuery()
            ->select(
                'B.id',
                'B.create_date',
                'A.sys',
                'A.dia',
                'A.pulse',
                'B.height',
                'B.weight',
                'B.tag'
            )
            ->from('bp B')
            ->join('bp_items A', 'LEFT', array('A.bp_id', 'B.id'))
            ->where($where)
            ->order('B.create_date ASC')
            ->cacheOn()
            ->execute();
    }
}
