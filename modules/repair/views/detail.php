<?php
/**
 * @filesource modules/repair/views/detail.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Detail;

use Gcms\Login;
use Kotchasan\DataTable2;
use Kotchasan\Date;
use Kotchasan\Template;

/**
 * module=repair-detail
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * @var mixed
     */
    private $statuses;

    /**
     * แสดงรายละเอียดการซ่อม
     *
     * @param object $index
     * @param array  $login
     *
     * @return string
     */
    public function render($index, $login)
    {
        // สถานะการซ่อม
        $this->statuses = \Repair\Status\Model::create();
        // อ่านสถานะการทำรายการทั้งหมด
        // URL สำหรับส่งให้ตาราง
        $uri = self::$request->createUriWithGlobals(WEB_URL.'index.php');
        /*เอารูปภาพE-sig มาแสดง  */
        $img = is_file(ROOT_PATH.DATA_FOLDER.'approve/'.'R'.$index->id.'-'.Date::format($index->date_approve, 'md').'.jpg') ? WEB_URL.DATA_FOLDER.'approve/'.'R'.$index->id.'-'.Date::format($index->date_approve, 'md').'.jpg' : WEB_URL.'modules/inventory/img/noimage.png';
        //เช็คกลุ่มผู้ใช้งาน
        $gmember = \Index\Member\Model::getMemberstatus($index->s_group);
        if($index->status == '9' || $index->status == '10'){ // template for approve/none approve
            if (Login::checkPermission($login, array('can_manage_car_booking','can_repair','approve_manage_repair','approve_repair')) ){
                           $template = Template::createFromFile(ROOT_PATH.'modules/repair/views/detail2.html');
            }else{      $template = Template::createFromFile(ROOT_PATH.'modules/repair/views/detail3.html');  } 
        }else{      $template = Template::createFromFile(ROOT_PATH.'modules/repair/views/detail.html');   }// template standard All Status
        /*เอารูปภาพแนบเปิดงานมาแสดง  */
            $img2 = is_file(ROOT_PATH.DATA_FOLDER.'file_attachment_user/'.'U_'.$index->job_id.'.jpg') ? WEB_URL.DATA_FOLDER.'file_attachment_user/'.'U_'.$index->job_id.'.jpg' : WEB_URL.'modules/inventory/img/noimage.png';  
        //รูปรถบรรทุก
            $thumb = is_file(ROOT_PATH.DATA_FOLDER.'inventory/'.$index->inventory_id.'.jpg') ? WEB_URL.DATA_FOLDER.'inventory/'.$index->inventory_id.'.jpg' : WEB_URL.'modules/inventory/img/noimage.png';
        //check type request
        foreach (self::$cfg->type_request as $key => $value) {
            if($index->types_objective == $key){
                $types_objective = $value;
                
                if( $types_objective == "อื่นๆ (โปรดระบุลงในหมายเหตุ)"){
                    $types_objective = 'อื่นๆ';
                }
            }    
        }
       
        // ตาราง
        $table = new DataTable2(array(
            /* Uri */
            'uri' => $uri,
            /* array datas */
            'datas' => \Repair\Detail\Model::getAllStatus($index->id),//$statuses,
            /* ฟังก์ชั่นจัดรูปแบบการแสดงผลแถวของตาราง */
            'onRow' => array($this, 'onRow'),
            /* คอลัมน์ที่ไม่ต้องแสดงผล */
            'hideColumns' => array('id'),
            /* ตั้งค่าการกระทำของของตัวเลือกต่างๆ ด้านล่างตาราง ซึ่งจะใช้ร่วมกับการขีดถูกเลือกแถว */
            'action' => 'index.php/repair/model/detail/action?repair_id='.$index->id,
            'actionCallback' => 'dataTableActionCallback',
            /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
            'headers' => array(
                'name' => array(
                    'text' => '{LNG_Operator}',
                ),
                'status' => array(
                    'text' => '{LNG_Task status}',
                    'class' => 'center',
                ),
                'create_date' => array(
                    'text' => '{LNG_Transaction date}', 
                    'class' => 'center',
                ),
                'comment' => array(
                    'text' => '{LNG_Comment}',
                ),
             /*  'attachment' => array(
                    'text' => '{LNG_file_attachment}',
                ),*/
                'picture' => array(
                    'text' => '{LNG_Image}',
                ),
                
            ),
            /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
            'cols' => array(
                'status' => array(
                    'class' => 'center',
                ),
                'create_date' => array(
                    'class' => 'center',
                ),
            ),
        ));

        if (Login::checkPermission($login, array('can_manage_car_booking', 'can_repair'))) {
            /* ปุ่มแสดงในแต่ละแถว */
            $table->buttons = array(
            /*    'file_attachment' => array(
                    'class' => 'button purple notext notext icon-download',
                    'id' => ':id',
                    'title' => '{LNG_File}',             
                ),   */
                'delete' => array(
                    'class' => 'icon-delete button red notext',
                    'id' => ':id',
                    'title' => '{LNG_Delete}',
                ),
            );
        }
//var_dump( $index);

        $template->add(array(
            '/%NAME%/' => $index->name,
            '/%PHONE%/' => $index->phone,
            '/%TOPIC%/' => $index->topic,
            '/%PRODUCT_NO%/' => $index->product_no,
            '/%JOB_DESCRIPTION%/' => nl2br($index->job_description), 
            '/%DESTINATION%/' => nl2br($index->destination),  
            '/%BEGIN_DATE%/' =>  Date::format($index->begin_date, 'd M Y H:i'),
            '/%END_DATE%/' =>  Date::format($index->end_date, 'd M Y H:i'),
            '/%CREATE_DATE%/' => Date::format($index->create_date, 'd M Y H:i'),
            '/%COMMENT%/' => $index->comment,
            '/%DETAILS%/' => $table->render(),
            '/%NAMEAPPROVE%/' => $index->send_approve2,
            '/%JOB%/' => $index->job_id,
            '/%GROUP%/' => $gmember,
            '/%ESIG%/' => $img,
            '/%DATE_APPROVE%/' => Date::format($index->date_approve, 'd M Y H:i'),
            '/%COST%/' => $index->cost,
            '/%UPIC%/' => $img2, 
            '/%INVENTORYPIC%/' => $thumb,
            '/%TYPE%/' =>  $types_objective,
           // '/%FILE_ATTACHMENT%/' => $file_attachment,
        ));
        
        // คืนค่า HTML
        return $template->render();


    }
  

    /**
     * จัดรูปแบบการแสดงผลในแต่ละแถว
     *
     * @param array  $item ข้อมูลแถว
     * @param int    $o    ID ของข้อมูล
     * @param object $prop กำหนด properties ของ TR
     *
     * @return array คืนค่า $item กลับไป
     */
    public function onRow($item, $o, $prop)
    {
        $item['comment'] = nl2br($item['comment']);
        $item['create_date'] = Date::format($item['create_date'], 'd M Y H:i');
        $item['status'] = '<mark class=term style="background-color:'.$this->statuses->getColor($item['status']).'">'.$this->statuses->get($item['status']).'</mark>';

        return $item;
    }
}
