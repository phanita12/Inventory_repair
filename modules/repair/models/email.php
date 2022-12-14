<?php
/**
 * @filesource modules/repair/models/email.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Email;

use Kotchasan\Date;
use Kotchasan\Language;
use Kotchasan\Text;

/**
 * ส่งอีเมลไปยังผู้ที่เกี่ยวข้อง
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{
    /**
     * ส่งอีเมลแจ้งการทำรายการ
     *
     * @param int $id
     */
    public static function send($id)
    {
        //Query get data booking car
            $sq1_approve =  \Kotchasan\Model::createQuery()
                    ->select('U1.username as send_approve')
                    ->from('user U1')
                    ->where(array('U1.id', 'R.send_approve'));

            $sq1_username_approve =  \Kotchasan\Model::createQuery()
                    ->select('U2.name as username_approve')
                    ->from('user U2')
                    ->where(array('U2.id', 'R.send_approve'));

            $sq2_s_group =  \Kotchasan\Model::createQuery()
                    ->select('U3.status as s_group')
                    ->from('user U3')
                    ->where(array('U3.id', 'R.customer_id'));

            $sq2_topic =  \Kotchasan\Model::createQuery()
                    ->select('C.topic as topic')
                    ->from('category C')
                    ->where(array('C.category_id','S.status'))
                    ->andWhere(array('C.type','repairstatus'));
        
                    // ตรวจสอบรายการที่ต้องการ
            $order = \Kotchasan\Model::createQuery()
                ->from('repair R')
                ->join('inventory_items I', 'LEFT', array('I.product_no', 'R.product_no'))
                ->join('inventory V', 'LEFT', array('V.id', 'I.inventory_id'))
                ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
                ->join('repair_status S', 'LEFT' , array('S.repair_id','R.id'))
                ->where(array('R.id', $id))
                ->order('S.id DESC')
                ->limit(1)
                ->first('R.*'
                            , 'V.topic'
                            , 'U.username'
                            , 'U.name' 
                            , 'S.comment' 
                            , 'S.urgency' 
                            , 'S.status'
                            , array($sq1_approve,'send_approve')
                            , array($sq1_username_approve,'username_approve')
                            , array($sq2_s_group,'s_group')  
                            , 'S.create_date as approve_date' 
                            , 'R.customer_id'
                            , array($sq2_topic,'category')
                            ,'R.id'
                        ); 
        //เช็คกลุ่มผู้ใช้งาน
                $gmember = \Index\Member\Model::getMemberstatus($order->s_group);
       
            if ($order) {         
                $ret = array();
                 //check type request
                 foreach (self::$cfg->type_request as $key => $value) {
                    if($order->types_objective == $key){
                        $types_objective = $value;
                        if( $types_objective == "อื่นๆ (โปรดระบุลงในหมายเหตุ)"){
                            $types_objective = 'อื่นๆ';
                        }
                    }    
                }
                //ส่งอีเมลกรณี ส่งขออนุมัติรายการแจ้งซ่อม
<<<<<<< HEAD
                    if (!empty(self::$cfg->noreply_email) || !empty(self::$cfg->line_api_key)) {
                        //เช็คสถานะขออนุมัติ
                        if($order->status == 8){
                                    // ข้อความ
                                        $content = array(
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Booking}  : '.'</b> '.$order->job_id,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Registration No.} :'.'</b>'.$order->product_no,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Begin date} :'.'</b>'.Date::format($order->begin_date, 'd M Y H:i'), 
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_End date} :'.'</b>'.Date::format($order->end_date, 'd M Y H:i'), 
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Car information} :'.'</b>'.$order->topic,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Types of objective} :'.'</b>'.$types_objective, 
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_destination} :'.'</b>'.$order->destination,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Details} :'.'</b>'.$order->job_description,
                                            '<br>',
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Received date} :'.'</b>'.Date::format($order->create_date, 'd M Y H:i'),
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Informer} :'.'</b>'.$order->name,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Member status} :'.'</b>'.$gmember,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Approve} :'.'</b>'.$order->username_approve,
                                            '<br>',
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Status} :'.$order->category.'</b>',
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Booking note} :'.'</b>'.$order->comment,
                                        );   
                                        //เช็คส่งเมลสถานะ ส่งขออนุมัติ
                                        $msg = Language::trans(implode("\n", $content));
                                        $send_approve_msg = $msg."\nLink url: ".WEB_URL.'index.php?module=repair-approve';
                                        // หัวข้ออีเมล
                                        $subject = '['.self::$cfg->web_title.'] '.Language::get('Repair');
                                        // อีเมลของผู้ดูแล
                                        $query = \Kotchasan\Model::createQuery()
                                            ->select('username', 'name')
                                            ->from('user')
                                            ->where(array(
                                                array('social', 0),
                                                array('active', 1),
                                                array('username', '!=', $order->username),
                                            ))
                                            ->andWhere(array(
                                                array('status', 1),
                                                array('permission', 'LIKE', '%,can_config%'),
                                            ), 'OR')
                                            ->cacheOn();
                                        $emails = array();
                                        foreach ($query->execute() as $item) {
                                            $emails[$item->username] = $item->name.'<'.$item->username.'>';
                                        }
                                        if (!empty($emails)) {
                                            $err = \Kotchasan\Email::send(implode(',', $emails), self::$cfg->noreply_email, $subject, nl2br($send_approve_msg));
                                            if ($err->error()) {
                                                $ret[] = strip_tags($err->getErrorMessage());
                                            }
                                        }
                                        // ส่งอีเมลไปยังผู้อนุมัติ    
                                        $err = \Kotchasan\Email::send($order->username_approve.' <'.$order->send_approve.'>', self::$cfg->noreply_email, $subject, nl2br($msg),implode(',', $emails));
                                        if ($err->error()) {
                                            $ret[] = strip_tags($err->getErrorMessage());
                                        }
=======
                if($order->status == 8){
                        // ข้อความ
                        $content = array(
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_List of}'.'{LNG_Repair} '.$order->category.'</b>'.$order->job_id,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Serial/Registration No.} :'.'</b>'.$order->product_no,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Equipment} :'.'</b>'.$order->topic,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Problems and repairs details} :'.'</b>'.$order->job_description,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Received date} :'.'</b>'.Date::format($order->create_date, 'd M Y H:i'),
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Informer} :'.'</b>'.$order->name,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Member status} :'.'</b>'.$gmember,
                            '<br>',
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Approve} :'.'</b>'.$order->username_approve,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Status} :'.$order->category.'</b>',//Language::get('approve_wait')
                            '<br>',
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Repair note} :'.'</b>'.$order->comment,
                            
                        );   
                        //เช็คส่งเมลสถานะ ส่งขออนุมัติ
                        $msg = Language::trans(implode("\n", $content));
                        $send_approve_msg = $msg."\nLink url: ".WEB_URL.'index.php?module=repair-approve';
                        // หัวข้ออีเมล
                        $subject = '['.self::$cfg->web_title.'] '.Language::get('Repair');
                      
                        // อีเมลของผู้ดูแล
                        $query = \Kotchasan\Model::createQuery()
                            ->select('username', 'name')
                            ->from('user')
                            ->where(array(
                                array('social', 0),
                                array('active', 1),
                                array('username', '!=', $order->username),
                            ))
                            ->andWhere(array(
                                array('status', 1),
                                array('permission', 'LIKE', '%,can_config%'), //can_manage_repair
                            ), 'OR')
                            ->cacheOn();
                        $emails = array();
                        foreach ($query->execute() as $item) {
                            $emails[$item->username] = $item->name.'<'.$item->username.'>';
                        }
                        if (!empty($emails)) {
                            $err = \Kotchasan\Email::send(implode(',', $emails), self::$cfg->noreply_email, $subject, nl2br($send_approve_msg));
                            if ($err->error()) {
                                $ret[] = strip_tags($err->getErrorMessage());
                            }
                        }

                          
                        // ส่งอีเมลไปยังผู้อนุมัติ    
                        $err = \Kotchasan\Email::send($order->username_approve.' <'.$order->send_approve.'>', self::$cfg->noreply_email, $subject, nl2br($msg),implode(',', $emails));
                        //print_r($err);

                        if ($err->error()) {
                            $ret[] = strip_tags($err->getErrorMessage());
                        }
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036


                                        if (!empty(self::$cfg->line_api_key)) {
                                            // ส่งไลน์
                                            $err = \Gcms\Line::send($send_approve_msg);
                                            if ($err != '') {
                                                $ret[] = $err;
                                            }
                                        } 

<<<<<<< HEAD
=======
                        

                 //เช็คส่งเมลสถานะ ไม่อนุมัติ/อนุมัติ/ส่งมอบเรียบร้อย/ยกเลิกการซ่อม/ซ่อมไม่สำเร็จ/ซ่อมสำเร็จ/รออะไหล่/กำลังดำเนินการ
                }elseif($order->status == 10 || $order->status == 9 || $order->status == 5 || $order->status == 4 || $order->status == 3 || $order->status == 2 ){

                        // ข้อความ
                        $content = array(
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_List of}'.'{LNG_Repair} '.$order->category.' : </b>'.$order->job_id,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Serial/Registration No.} :'.'</b>'.$order->product_no,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Equipment} :'.'</b>'.$order->topic,
                           // '<b style="font-family":"Kanit";"font-size":"30px";"color:red">'.'{LNG_Lavel Urgency} :'.'</b>'.$order->urgency,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Problems and repairs details} :'.'</b>'.$order->job_description,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Received date} :'.'</b>'.Date::format($order->create_date, 'd M Y H:i'),
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Informer} :'.'</b>'.$order->name,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Member status} :'.'</b>'.$gmember,
                            '<br>',
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Approve} :'.'</b>'.$order->username_approve,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Status} :'.'</b>'.$order->category,
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Transaction date} :'.'</b>'.Date::format($order->approve_date, 'd M Y H:i'),
                            '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Repair note} :'.'</b>'.$order->comment,
                        );
            
                            //เช็คส่งเมลสถานะ อนุมัติ/ไม่อนุมัติ
                            $msg = Language::trans(implode("\n", $content));
                            //$send_approve_msg = $msg."\nURL: ".WEB_URL.'index.php?module=repair-approve';
                            $admin_msg = $msg."\nLink url: ".WEB_URL.'index.php?module=repair-setup';
                            
            
                            // หัวข้ออีเมล
                            $subject = '['.self::$cfg->web_title.'] '.Language::get('Repair');                
                            // ส่งอีเมลไปยังผู้ทำรายการเสมอ
                            $err = \Kotchasan\Email::send($order->name.'<'.$order->username.'>', self::$cfg->noreply_email, $subject, nl2br($msg));
                            if ($err->error()) {
                                $ret[] = strip_tags($err->getErrorMessage());
                            }
            
                            // อีเมลของผู้ดูแล
                            $query = \Kotchasan\Model::createQuery()
                                ->select('username', 'name')
                                ->from('user')
                                ->where(array(
                                    array('social', 0),
                                    array('active', 1),
                                    array('username', '!=', $order->username),
                                ))
                                ->andWhere(array(
                                    array('status', 1),
                                    array('permission', 'LIKE', '%,can_config%'),//can_manage_repair
                                ), 'OR')
                                ->cacheOn();
                            $emails = array();
            
                            foreach ($query->execute() as $item) {
                                $emails[$item->username] = $item->name.'<'.$item->username.'>';
                            }
                            if (!empty($emails)) {
                                $err = \Kotchasan\Email::send(implode(',', $emails), self::$cfg->noreply_email, $subject, nl2br($admin_msg));
                                if ($err->error()) {
                                    $ret[] = strip_tags($err->getErrorMessage());
                                }
                            }
                            if (!empty(self::$cfg->line_api_key)) {
                                // ส่งไลน์
                                $err = \Gcms\Line::send($admin_msg);
                                if ($err != '') {
                                    $ret[] = $err;
                                }
                            } 
                     //เช็คส่งเมลสถานะ ส่งมอบเรียบร้อย/ยกเลิกการซ่อม
                }elseif($order->status == 7 || $order->status == 6){
    
                            // ข้อความ
                            $content = array(
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_List of}'.'{LNG_Repair} '.$order->category.' : </b>'.$order->job_id,
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Serial/Registration No.} :'.'</b>'.$order->product_no,
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Equipment} :'.'</b>'.$order->topic,
                               // '<b style="font-family":"Kanit";"font-size":"30px";"color:red">'.'{LNG_Lavel Urgency} :'.'</b>'.$order->urgency,
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Problems and repairs details} :'.'</b>'.$order->job_description,
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Received date} :'.'</b>'.Date::format($order->create_date, 'd M Y H:i'),
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Informer} :'.'</b>'.$order->name,
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Member status} :'.'</b>'.$gmember,
                                '<br>',
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Approve} :'.'</b>'.$order->username_approve,
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Status} :'.'</b>'.$order->category,
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Transaction date} :'.'</b>'.Date::format($order->approve_date, 'd M Y H:i'),
                                '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Repair note} :'.'</b>'.$order->comment,
                                //'File Download :'.'<a href="'.WEB_URL.'index.php?_module=repair-setup&module=repair-printrepair&id='.$order->id.'>ไฟล์แบบฟอร์มแจ้งปัญหาการใช้งานไอที (IT)</a></b>',
                            );
                
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
                                

                        //เช็คส่งเมลสถานะ ไม่อนุมัติ/อนุมัติ/ส่งมอบเรียบร้อย/ยกเลิกการซ่อม/ซ่อมไม่สำเร็จ/ซ่อมสำเร็จ/รออะไหล่/กำลังดำเนินการ
                            }elseif($order->status == 10 || $order->status == 9 || $order->status == 5 || $order->status == 4 || $order->status == 3 || $order->status == 2 ){
                                    // ข้อความ
                                    $content = array(
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Booking}  : '.'</b> '.$order->job_id,
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Registration No.} :'.'</b>'.$order->product_no,
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Begin date} :'.'</b>'.Date::format($order->begin_date, 'd M Y H:i'), 
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_End date} :'.'</b>'.Date::format($order->end_date, 'd M Y H:i'), 
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Car information} :'.'</b>'.$order->topic,
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Types of objective} :'.'</b>'.$types_objective, 
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_destination} :'.'</b>'.$order->destination,
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Details} :'.'</b>'.$order->job_description,
                                        '<br>',
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Received date} :'.'</b>'.Date::format($order->create_date, 'd M Y H:i'),
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Informer} :'.'</b>'.$order->name,
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Member status} :'.'</b>'.$gmember,
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Approve} :'.'</b>'.$order->username_approve,
                                        '<br>',
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Status} :'.'</b>'.$order->category,
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Transaction date} :'.'</b>'.Date::format($order->approve_date, 'd M Y H:i'),
                                        '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Booking note} :'.'</b>'.$order->comment,
                                    );
                        
                                        //เช็คส่งเมลสถานะ อนุมัติ/ไม่อนุมัติ
                                        $msg = Language::trans(implode("\n", $content));
                                        //$send_approve_msg = $msg."\nURL: ".WEB_URL.'index.php?module=repair-approve';
                                        $admin_msg = $msg."\nLink url: ".WEB_URL.'index.php?module=repair-setup';
                                        
                        
                                        // หัวข้ออีเมล
                                        $subject = '['.self::$cfg->web_title.'] '.Language::get('Repair');                
                                        // ส่งอีเมลไปยังผู้ทำรายการเสมอ
                                        $err = \Kotchasan\Email::send($order->name.'<'.$order->username.'>', self::$cfg->noreply_email, $subject, nl2br($msg));
                                        if ($err->error()) {
                                            $ret[] = strip_tags($err->getErrorMessage());
                                        }
                        
                                        // อีเมลของผู้ดูแล
                                        $query = \Kotchasan\Model::createQuery()
                                            ->select('username', 'name')
                                            ->from('user')
                                            ->where(array(
                                                array('social', 0),
                                                array('active', 1),
                                                array('username', '!=', $order->username),
                                            ))
                                            ->andWhere(array(
                                                array('status', 1),
                                                array('permission', 'LIKE', '%,can_config,%'),//can_manage_repair
                                            ), 'OR')
                                            ->cacheOn();
                                        $emails = array();
                        
                                        foreach ($query->execute() as $item) {
                                            $emails[$item->username] = $item->name.'<'.$item->username.'>';
                                        }
                                        if (!empty($emails)) {
                                            $err = \Kotchasan\Email::send(implode(',', $emails), self::$cfg->noreply_email, $subject, nl2br($admin_msg));
                                            if ($err->error()) {
                                                $ret[] = strip_tags($err->getErrorMessage());
                                            }
                                        }
                                        if (!empty(self::$cfg->line_api_key)) {
                                            // ส่งไลน์
                                            $err = \Gcms\Line::send($admin_msg);
                                            if ($err != '') {
                                                $ret[] = $err;
                                            }
                                        } 
                        //เช็คส่งเมลสถานะ ส่งมอบเรียบร้อย/ยกเลิกการซ่อม     
                            }elseif($order->status == 7 || $order->status == 6){
                                        // ข้อความ
                                        $content = array(
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Booking}  : '.'</b> '.$order->job_id,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Registration No.} :'.'</b>'.$order->product_no,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Begin date} :'.'</b>'.Date::format($order->begin_date, 'd M Y H:i'), 
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_End date} :'.'</b>'.Date::format($order->end_date, 'd M Y H:i'), 
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Car information} :'.'</b>'.$order->topic,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Types of objective} :'.'</b>'.$types_objective, 
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_destination} :'.'</b>'.$order->destination,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Details} :'.'</b>'.$order->job_description,
                                            '<br>',
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Received date} :'.'</b>'.Date::format($order->create_date, 'd M Y H:i'),
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Informer} :'.'</b>'.$order->name,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Member status} :'.'</b>'.$gmember,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Approve} :'.'</b>'.$order->username_approve,
                                            '<br>',
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Status} :'.'</b>'.$order->category,
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Transaction date} :'.'</b>'.Date::format($order->approve_date, 'd M Y H:i'),
                                            '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Booking note} :'.'</b>'.$order->comment,
                                            //'File Download :'.'<a href="'.WEB_URL.'index.php?_module=repair-setup&module=repair-printrepair&id='.$order->id.'>ไฟล์แบบฟอร์มแจ้งปัญหาการใช้งานไอที (IT)</a></b>',
                                        );
                                            $msg = Language::trans(implode("\n", $content));
                                            $admin_msg = $msg."\nLink url: ".WEB_URL.'index.php?module=repair-setup';
                                            $msg2 = $msg."\nFile :".'<a href="'.WEB_URL.'index.php?_module=repair-setup&module=repair-printrepair&id='.$order->id.'">แบบฟอร์มแจ้งปัญหาการใช้งานไอที (IT) : '.$order->job_id.'</a>"';
                                            $admin_msg2 = $msg."\nLink แบบฟอร์ม : <a href=".WEB_URL.'index.php?_module=repair-setup&module=repair-printrepair&id='.$order->id.">".WEB_URL.'index.php?_module=repair-setup&module=repair-printrepair&id='.$order->id."</a> ";
                                            // หัวข้ออีเมล
                                            $subject = '['.self::$cfg->web_title.'] '.Language::get('Repair');                
                                            // ส่งอีเมลไปยังผู้ทำรายการเสมอ
                                            $err = \Kotchasan\Email::send($order->name.'<'.$order->username.'>', self::$cfg->noreply_email, $subject, nl2br($msg2));
                                            if ($err->error()) {
                                                $ret[] = strip_tags($err->getErrorMessage());
                                            }
                                            // อีเมลของผู้ดูแล
                                            $query = \Kotchasan\Model::createQuery()
                                                ->select('username', 'name')
                                                ->from('user')
                                                ->where(array(
                                                    array('social', 0),
                                                    array('active', 1),
                                                    array('username', '!=', $order->username),
                                                ))
                                                ->andWhere(array(
                                                    array('status', 1),
                                                    array('permission', 'LIKE', '%,can_config,%'),
                                                ), 'OR')
                                                ->cacheOn();
                                            $emails = array();
                            
                                            foreach ($query->execute() as $item) {
                                                $emails[$item->username] = $item->name.'<'.$item->username.'>';
                                            }
                                            if (!empty($emails)) {
                                                $err = \Kotchasan\Email::send(implode(',', $emails), self::$cfg->noreply_email, $subject, nl2br($admin_msg));
                                                if ($err->error()) {
                                                    $ret[] = strip_tags($err->getErrorMessage());
                                                }
                                            }
                                            if (!empty(self::$cfg->line_api_key)) {
                                                // ส่งไลน์
                                                $err = \Gcms\Line::send($admin_msg2);
                                                if ($err != '') {
                                                    $ret[] = $err;
                                                }
                                            } 
                        //สถานะอื่นๆ นอกเหนือจากด้านบน                     
                            }else{
                                // ข้อความ
                                $content = array(
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Booking}  : '.'</b> '.$order->job_id,
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Registration No.} :'.'</b>'.$order->product_no,
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Begin date} :'.'</b>'.Date::format($order->begin_date, 'd M Y H:i'), 
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_End date} :'.'</b>'.Date::format($order->end_date, 'd M Y H:i'), 
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Car information} :'.'</b>'.$order->topic,
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Types of objective} :'.'</b>'.$types_objective, 
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_destination} :'.'</b>'.$order->destination,
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Details} :'.'</b>'.$order->job_description,
                                    '<br>',
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Received date} :'.'</b>'.Date::format($order->create_date, 'd M Y H:i'),
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Informer} :'.'</b>'.$order->name,
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Member status} :'.'</b>'.$gmember,
                                    '<b style="font-family":"Kanit";"font-size":"30px;">'.'{LNG_Approve} :'.'</b>'.$order->username_approve,
                                    '<br>',
                                    '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Status} : {LNG_Booking} </b>', 
                                );

                                //เช็คส่งเมลสถานะ ส่งขออนุมัติ
                                    $msg = Language::trans(implode("\n", $content));
                                    $admin_msg = $msg."\nLink url: ".WEB_URL.'index.php?module=repair-setup';

                                // หัวข้ออีเมล
                                    $subject = '['.self::$cfg->web_title.'] '.Language::get('Repair');
                                // ส่งอีเมลไปยังผู้ทำรายการเสมอ
<<<<<<< HEAD
                                    $err = \Kotchasan\Email::send($order->name.'<'.$order->username.'>', self::$cfg->noreply_email, $subject, nl2br($msg));
=======
                                $err = \Kotchasan\Email::send($order->name.'<'.$order->username.'>', self::$cfg->noreply_email, $subject, nl2br($msg2));
                                if ($err->error()) {
                                    $ret[] = strip_tags($err->getErrorMessage());
                                }
                
                                // อีเมลของผู้ดูแล
                                $query = \Kotchasan\Model::createQuery()
                                    ->select('username', 'name')
                                    ->from('user')
                                    ->where(array(
                                        array('social', 0),
                                        array('active', 1),
                                        array('username', '!=', $order->username),
                                    ))
                                    ->andWhere(array(
                                        array('status', 1),
                                        array('permission', 'LIKE', '%,can_config%'),//can_manage_repair
                                    ), 'OR')
                                    ->cacheOn();
                                $emails = array();
                
                                foreach ($query->execute() as $item) {
                                    $emails[$item->username] = $item->name.'<'.$item->username.'>';
                                }
                                if (!empty($emails)) {
                                    $err = \Kotchasan\Email::send(implode(',', $emails), self::$cfg->noreply_email, $subject, nl2br($admin_msg));
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
                                    if ($err->error()) {
                                        $ret[] = strip_tags($err->getErrorMessage());
                                    }
                                // อีเมลของผู้ดูแล
                                    $query = \Kotchasan\Model::createQuery()
                                        ->select('username', 'name')
                                        ->from('user')
                                        ->where(array(
                                            array('social', 0),
                                            array('active', 1),
                                            array('username', '!=', $order->username),
                                        ))
                                        ->andWhere(array(
                                            array('status', 1),
                                            array('permission', 'LIKE', '%,can_config,%'),
                                        ), 'OR')
                                        ->cacheOn();
                                    $emails = array();

                                    foreach ($query->execute() as $item) {
                                        $emails[$item->username] = $item->name.'<'.$item->username.'>';
                                    }
                                    if (!empty($emails)) {
                                        $err = \Kotchasan\Email::send(implode(',', $emails), self::$cfg->noreply_email, $subject, nl2br($admin_msg));
                                        if ($err->error()) {
                                            $ret[] = strip_tags($err->getErrorMessage());
                                        }
                                    }
                                    

<<<<<<< HEAD
                                    if (!empty(self::$cfg->line_api_key)) {
                                        // ส่งไลน์
                                        $err = \Gcms\Line::send($admin_msg);
                                        if ($err != '') {
                                            $ret[] = $err;
                                        }
                                    } 
=======
                    // ข้อความ
                    $content = array(
                        '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Repair} :'.'</b> '.$order->job_id,
                        '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Serial/Registration No.} :'.'</b>'.$order->product_no,
                        '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Equipment} :'.'</b>'.$order->topic,
                        //'<b style="font-family":"Kanit";"font-size":"30px";"color:red">'.'{LNG_Lavel Urgency} :'.'</b>'.$order->urgency,
                        '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Problems and repairs details} :'.'</b>'.$order->job_description,
                        '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Received date} :'.'</b>'.Date::format($order->create_date, 'd M Y H:i'),
                        '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Informer} :'.'</b>'.$order->name,
                        '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Member status} :'.'</b>'.$gmember,
                        '<br>',
                        '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Approve} :'.'</b>'.$order->username_approve,
                        '<b style="font-family":"Kanit";"font-size":"30px">'.'{LNG_Status} : {LNG_Repair} </b>', //.Language::get('pending')
                        
                        
                    );

                    //เช็คส่งเมลสถานะ ส่งขออนุมัติ
                    $msg = Language::trans(implode("\n", $content));
                    $admin_msg = $msg."\nLink url: ".WEB_URL.'index.php?module=repair-setup';

                    // หัวข้ออีเมล
                        $subject = '['.self::$cfg->web_title.'] '.Language::get('Repair');
                        
                        // ส่งอีเมลไปยังผู้ทำรายการเสมอ
                        $err = \Kotchasan\Email::send($order->name.'<'.$order->username.'>', self::$cfg->noreply_email, $subject, nl2br($msg));
                        if ($err->error()) {
                            $ret[] = strip_tags($err->getErrorMessage());
                        }
                        // อีเมลของผู้ดูแล
                        $query = \Kotchasan\Model::createQuery()
                            ->select('username', 'name')
                            ->from('user')
                            ->where(array(
                                array('social', 0),
                                array('active', 1),
                                array('username', '!=', $order->username),
                            ))
                            ->andWhere(array(
                                array('status', 1),
                                array('permission', 'LIKE', '%,can_config%'),//can_manage_repair
                            ), 'OR')
                            ->cacheOn();
                        $emails = array();

                        foreach ($query->execute() as $item) {
                            $emails[$item->username] = $item->name.'<'.$item->username.'>';
                        }
                        if (!empty($emails)) {
                            $err = \Kotchasan\Email::send(implode(',', $emails), self::$cfg->noreply_email, $subject, nl2br($admin_msg));
                            if ($err->error()) {
                                $ret[] = strip_tags($err->getErrorMessage());
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
                            }
                    }
                // คืนค่า
                    if ($order->send_approve != '' || !empty(self::$cfg->line_api_key)) {
                        return empty($ret) ? Language::get('Your message was sent successfully') : implode("\n", $ret);
                    } else {
                        return Language::get('Saved successfully');
                    }
            }
        // not found
        return Language::get('Unable to complete the transaction');
    }
}
