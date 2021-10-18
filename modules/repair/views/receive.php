<?php
/**
 * @filesource modules/repair/views/receive.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Receive;
use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Language;

/**
 * module=repair-receive
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
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
        $login = Login::isMember();
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

        // Type
        $groups_type->add('text', array(
            'id' => 'type_id',
            'labelClass' => 'g-input icon-tools',
            'itemClass' => 'displaynone',
            'label' => '{LNG_Type}',  
            'maxlength' => 20, 
            'value' => $index->type_id,)); 
        $groups_type->add('text', array(
            'id' => 'type_repair',
            'labelClass' => 'g-input icon-tools',
            'itemClass' => 'width15',
            'label' => '{LNG_Type}',  
            'maxlength' => 20, 
            'disabled' => true,
            'value' => $index->type_repair,));

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
            'value' => $index->model,));
    
        // product_no
        $groups->add('text', array(
            'id' => 'product_no',
            'labelClass' => 'g-input icon-number',
            'itemClass' => 'width50',
            'label' => '{LNG_Serial/Registration No.}',
            'maxlength' => 20,
            'value' => $index->product_no,
        ));
        // topic
        $groups->add('text', array(
            'id' => 'topic',
            'labelClass' => 'g-input icon-edit',
            'itemClass' => 'width50',
            'label' => '{LNG_Equipment}', 
            'maxlength' => 64,
            'disabled' => true,
            'value' => $index->topic,
        ));
        // job_description
        $fieldset->add('textarea', array(
            'id' => 'job_description',
            'labelClass' => 'g-input icon-file',
            'itemClass' => 'item',
            'label' => '{LNG_Problems and repairs details}',
            'rows' => 5,
            'value' => $index->job_description,
        ));
      //User upload file Attachment
        $fieldset->add('file', array(
        'id' => 'file_attachment_user',
        'labelClass' => 'g-input icon-upload',
        'itemClass' => 'item',
        'label' => '{LNG_file_attachment}',
        'comment' => Language::replace('Browse image uploaded, type :type', array(':type' => 'jpg, jpeg, png')).' ({LNG_resized automatically})',
        'dataPreview' => 'imgPicture',
       // 'previewSrc' => $img,
      //'previewSrc_disable' => $img,
        'accept' => array('jpg', 'jpeg', 'png'), 
        ));  

         // Approve_id
         $fieldset ->add('hidden', array(
            'id' => 'approve_id',
            'value' => $index->approve_id,
        ));
         // List Name Approve 
         $fieldset ->add('text', array(
            'id' => 'approve',
            'labelClass' => 'g-input icon-user',
            'itemClass' => 'item',//item
            'label' => '{LNG_Approve}',
            //'comment' => '{LNG_Note or additional notes}',
            'maxlength' => 20,
            'value' => $index->approve,
        ));

        if ($index->id == 0) {
         // comment Level of Urgency
         $fieldset->add('radiogroups', array(
                'id' => 'urgency',
         /*       'label' => ' {LNG_Lavel Urgency}',
                'labelClass' => 'g-input icon-star2',
                'itemClass' => 'item',//item
                'options' => Language::get('LEVEL_FIELDS'),
                'value' => $index->urgency, */
            ));
      /*-------------------------------------------en moomai----------------------------------------*/
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
