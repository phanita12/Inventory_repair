<?php
/**
 * @filesource modules/repair/views/receive.php
 *
 */

namespace Repair\Receive;
use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Language;

/**
 * module=repair-receive
 */
class View extends \Gcms\View
{
    /**
     * เพิ่ม-แก้ไข แจ้งซ่อม
     *
     * @param object $index
     *
     * @return string
     */
    public function render($index)
    {
<<<<<<< HEAD
        //form resive
            $form = Html::create('form', array(
                'id' => 'setup_frm',
                'class' => 'setup_frm',
                'autocomplete' => 'off',
                'action' => 'index.php/repair/model/receive/submit',
                'onsubmit' => 'doFormSubmit',
                'ajax' => true,
                'token' => true,
            ));
            $fieldset = $form->add('fieldset', array('title' => '{LNG_'.($index->id == 0 ? 'Add' : 'Edit').'} {LNG_Details} '.($index->id == 0 ? '' : '{LNG_'.$index->job_id.'}' )));    
            $groups = $fieldset->add('groups', array('comment' => '{LNG_Find equipment by}  {LNG_Registration No.}' ) ); 
            $groups_type = $fieldset->add('groups',); 
            $groups_date = $fieldset->add('groups',); 
            $type_work_name = ['{LNG_Visit Customer}','{LNG_Contact the government}','{LNG_Seminar}','{LNG_Deliver goods}','{LNG_Other}'];
            $type_easy_pass= ['{LNG_not_use}','{LNG_Use}'];
=======
         $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/repair/model/receive/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $fieldset = $form->add('fieldset', array('title' => '{LNG_Repair job description}', ));  
        $groups = $fieldset->add('groups',); //, array('comment' => '{LNG_Find equipment by}  {LNG_Serial/Registration No.}' ) array('urgency' => '{LNG_Find equipment by}  {LNG_Serial/Registration No.}', )
        $groups_type = $fieldset->add('groups',); //, array('comment' => '{LNG_Find equipment by} {LNG_Type},  {LNG_Category}, {LNG_Model}' )
       
        /*-------------------------------------------St moomai----------------------------------------*/
       
        // Category_id
         $groups_type->add('text', array(
            'id' => 'category_id',
            'labelClass' => 'g-input icon-edit',
            'itemClass' => 'displaynone',
            'label' => '{LNG_Category}',  
            'maxlength' => 20,
            'value' => $index->category_id,));
         $groups_type->add('text', array(
            'id' => 'category',
            'labelClass' => 'g-input icon-tools',
            'itemClass' => 'width25',
            'label' => '{LNG_Category}', 
            'disabled' => true,
            'maxlength' => 20, 
            'value' => $index->category,));
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036

        // begin
            $groups_date->add('datetime', array(
                'id' => 'begin',
                'label' => '{LNG_Begin date}/{LNG_Begin time}',
                'labelClass' => 'g-input icon-calendar',
                'itemClass' => 'width50',
                'title' => '{LNG_Begin date}',
                'min' => date('Y-m-d'),
                'value' => isset($index->begin_date) ? $index->begin_date : '', //date('Y-m-d H:i')
            ));
        // end
            $groups_date->add('datetime', array(
                'id' => 'end',
                'label' => '{LNG_End date}/{LNG_End time}',
                'labelClass' => 'g-input icon-calendar',
                'itemClass' => 'width50',
                'title' => '{LNG_End date}',
                'min' => date('Y-m-d'),
                'value' => isset($index->end_date) ? $index->end_date : '', //date('Y-m-d H:i')
            ));
        // product_no
            $groups->add('text', array(
                'id' => 'product_no',
                'labelClass' => 'g-input icon-number',
                'itemClass' => 'width50',
                'label' => '{LNG_Registration No.}',
                'title' => '{LNG_Registration No.}',
                'maxlength' => 20,
                'value' => $index->product_no,
            ));
        // topic
            $groups->add('text', array(
                'id' => 'topic',
                'labelClass' => 'g-input icon-edit',
                'itemClass' => 'width50',
                'label' => '{LNG_Car information}', 
                'maxlength' => 64,
                'disabled' => true,
                'value' => $index->topic,
            ));
        // Category_id
                $groups_type->add('hidden', array(
                    'id' => 'category_id',
                    'labelClass' => 'g-input icon-edit',
                    'itemClass' => 'displaynone',
                    'label' => '{LNG_Category}',  
                    'maxlength' => 20,
                    'value' => $index->category_id,));
                    $groups_type->add('hidden', array(
                    'id' => 'category',
                /* 'labelClass' => 'g-input icon-tools',
                    'itemClass' => 'width10',
                    'label' => '{LNG_Category}', 
                    'disabled' => true,
                    'maxlength' => 20, */
                   // 'value' => $index->category,
                ));
        // Type

            $groups_type->add('text', array(
                'id' => 'type_repair',
                'labelClass' => 'g-input icon-tools',
                'itemClass' => 'width10',
                'label' => '{LNG_Type}',  
                'maxlength' => 20, 
                'disabled' => true,
                'value' => $index->type_repair,
                'options' => $index->type_id,
                
            ));

        // Model
            $groups_type->add('text', array(
                'id' => 'model_id',
                'labelClass' => 'g-input icon-tools',
                'itemClass' => 'displaynone',
                'label' => '{LNG_Model}',  
                'maxlength' => 10, 
                'value' => $index->model_id,)); 
            $groups_type->add('text', array(
                'id' => 'model',
                'labelClass' => 'g-input icon-tools',
                'itemClass' => 'width10',
                'label' => '{LNG_Model}',  
                'maxlength' => 10, 
                'disabled' => true,
                'value' => $index->model,
            ));

        // easy pass
            $fieldset->add('radiogroups', array(
                'id' => 'easy_pass',
                'labelClass' => 'g-input icon-list',
                'itemClass' => 'item',
                'label' => 'Easy pass', 
                'multiline' => false,
                'scroll' => false,
                'options' => $type_easy_pass, 
                'value' => $index->easy_pass,
            ));
        // type_work
            $fieldset->add('radiogroups', array(
                'id' => 'type_work',
                'labelClass' => 'g-input icon-list',
                'itemClass' => 'item',
                'label' => '{LNG_Types of objective}', 
                'multiline' => false,
                'scroll' => false,
                'options' => $type_work_name, 
                'value' => $index->types_objective,
            ));
        // destination
              $fieldset->add('textarea', array(
                'id' => 'destination',
                'labelClass' => 'g-input icon-file',
                'itemClass' => 'item',
                'label' => '{LNG_destination}',
                'rows' => 1,
                'value' => $index->destination,
            ));
        // job_description
            $fieldset->add('textarea', array(
                'id' => 'job_description',
                'labelClass' => 'g-input icon-file',
                'itemClass' => 'item',
                'label' => '{LNG_Comment}',
                'rows' => 2,
                'value' => $index->job_description,
            ));
        //User upload file Attachment
               /*       $fieldset->add('file', array(
                    'id' => 'file_attachment_user',
                    'labelClass' => 'g-input icon-upload',
                    'itemClass' => 'item',
                    'label' => '{LNG_file_attachment}',
                    'comment' => Language::replace('Browse image uploaded, type :type', array(':type' => 'jpg, jpeg, png')).' ({LNG_resized automatically})',
                    'dataPreview' => 'imgPicture',
                // 'previewSrc' => $img,
                //'previewSrc_disable' => $img,
                    'accept' => array('jpg', 'jpeg', 'png'), 
                    ));  */

        // Approve_id
         $fieldset ->add('hidden', array(
            'id' => 'approve_id',
            'value' => $index->send_approve,
         ));
        // List Name Approve 
         $fieldset ->add('text', array(
            'id' => 'approve',
            'labelClass' => 'g-input icon-user',
            'itemClass' => 'item',
            'label' => '{LNG_Approve}',
            'maxlength' => 20,
            'value' => $index->send_approve2,//$index->approve,
         ));
        // comment Level of Urgency
         if ($index->id == 0) {
        
         $fieldset->add('radiogroups', array(
                'id' => 'urgency',
         /*       'label' => ' {LNG_Lavel Urgency}',
                'labelClass' => 'g-input icon-star2',
                'itemClass' => 'item',//item
                'options' => Language::get('LEVEL_FIELDS'),
                'value' => $index->urgency, */
            ));
        // status_id
            $fieldset->add('hidden', array(
                'id' => 'status_id',
                'value' => $index->status_id,
            ));
            }
            $fieldset = $form->add('fieldset', array(
                'class' => 'submit',
            ));
        // submit
            $fieldset->add('submit', array(
                'id' => 'save',
                'class' => 'button save large icon-save',
                'value' => '{LNG_Save}',
            ));
        // id
            $fieldset->add('hidden', array(
                'id' => 'id',
                'value' => $index->id,
                
            ));
        // Javascript
            $form->script('initRepairGet();');
        // คืนค่า HTML
            return $form->render();
        }
}
