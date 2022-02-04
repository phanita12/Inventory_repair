<?php
/**
 * @filesource modules/repair/views/action.php
 */

namespace Repair\Action;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Language;

/**
 * module=repair-action
 */
class View extends \Gcms\View
{
    /**
     * แสดงฟอร์ม Modal สำหรับการปรับสถานะการทำรายการ
     *
     * @param object $index
     * @param array  $login
     *
     * @return string
     */
    public function render($index, $login)
    {
      
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/repair/model/action/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $form->add('header', array(
            'innerHTML' => '<h3 class=icon-tools>{LNG_Update status} '.$index->job_id.'</h3>',
        ));
        $fieldset = $form->add('fieldset');
        $status = \Repair\Status\Model::create()->toSelect();
<<<<<<< HEAD
=======
        if($index->operator_id == 0){$operator = $login['id']; }else{ $operator =  $index->operator_id;  }
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
        // status
            $fieldset->add('select', array(
                'id' => 'status',
                'labelClass' => 'g-input icon-star0',
                'itemClass' => 'item',
<<<<<<< HEAD
                'label' => '{LNG_Task status}',
=======
                'label' => '{LNG_Repair status}',
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
                'options' => array(0 => '{LNG_Please select}') + $status,
                'value' => $index->status,
            ));
        // comment
            $fieldset->add('textarea', array(
                'id' => 'comment',
                'labelClass' => 'g-input icon-comments',
                'itemClass' => 'item',
                'label' => '{LNG_Comment}',
                'comment' => '{LNG_Note or additional notes}',
                'rows' => 2,
            ));
        // Cost
            $fieldset->add('number', array(
                'id' => 'cost',
<<<<<<< HEAD
                'labelClass' => 'g-input icon-money',
                'itemClass' => 'item',
                'label' => '{LNG_count_Easy_Pass}',
                'class'=> 'currency'
            ));
        // car_mileage_start
            $fieldset->add('number', array(
                'id' => 'car_mileage_start',
                'labelClass' => 'g-input icon-location',
                'itemClass' => 'item',
                'label' => '{LNG_Mileage starting}',
                'class'=> 'currency'
            )); 
        // car_mileage_start
            $fieldset->add('number', array(
                'id' => 'car_mileage_end',
                'labelClass' => 'g-input icon-location',
                'itemClass' => 'item',
                'label' => '{LNG_Mileage End}',
                'class'=> 'currency'
            )); 
=======
                'labelClass' => 'g-input icon-number',
                'itemClass' => 'item',
                'label' => '{LNG_Cost}',
                'class'=> 'currency'
            ));
        // warranty
            $fieldset->add('number', array(
                'id' => 'warranty',
                'labelClass' => 'g-input icon-number',
                'itemClass' => 'item',
                'label' => '{LNG_Warranty}',
            ));
         // Repair company
            $fieldset->add('number', array(
                'id' => 'repair_company',
                'labelClass' => 'g-input icon-number',
                'itemClass' => 'item',
                'label' => '{LNG_Repair company}',
            ));
>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
         // File attachment
            $fieldset->add('file', array(
                'id' => 'file_attachment',
                'labelClass' => 'g-input icon-upload',
                'itemClass' => 'item',
                'label' => '{LNG_Browse file}',
                'comment' => Language::replace('Upload :type files no larger than :size', array(':type' => '.pdf', ':size' => \Kotchasan\Http\UploadedFile::getUploadSize())),
                'accept' => array('pdf'), 
            ));  
<<<<<<< HEAD
=======

>>>>>>> 8eab65cd19e996f68c2857d36f83c28403366036
        if (Login::checkPermission($login, 'can_manage_repair')) {
            // operator_id
                $fieldset->add('select', array( 
                    'id' => 'operator_id',
                    'labelClass' => 'g-input icon-customer',
                    'itemClass' => 'item',
                    'label' => '{LNG_Operator}',
                    'options' => \Repair\Operator\Model::create()->toSelect(),
                    'value' => $operator 
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
            $fieldset->add('hidden', array(
                'id' => 'repair_id',
                'value' => $index->id,
            ));
        // คืนค่า HTML
            return $form->render();
    }
    public function render2($index, $login)
    {
   
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/repair/model/action/submit2',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $form->add('header', array(
            'innerHTML' => '<h3 class=icon-tools>{LNG_Update status} '.$index->job_id.'</h3>',
        ));
        $fieldset = $form->add('fieldset');
       // $status = \Repair\Status\Model::create()->toSelect();
        $status_approve = \Repair\Status\Model::create_approve()->toSelect_status_approve();

        // status approve
        $fieldset->add('select', array(
            'id' => 'status',
            'labelClass' => 'g-input icon-star0',
            'itemClass' => 'item',
            'label' => '{LNG_Task status}',
            'options' => array(0 => '{LNG_Please select}') + $status_approve,
            'value' => $index->status,
        ));
    
        // comment
        $fieldset->add('textarea', array(
            'id' => 'comment',
            'labelClass' => 'g-input icon-comments',
            'itemClass' => 'item',
            'label' => '{LNG_Comment}',
            'comment' => '{LNG_Note or additional notes}',
            'rows' => 2,
        ));

          // picture E-signature
          $img = WEB_URL.'modules/inventory/img/noimage.png';
          $fieldset->add('file', array(
            'id' => 'signature_approve',
            'labelClass' => 'g-input icon-upload',
            'itemClass' => 'item',
            'label' => '{LNG_signature approve}',
            'comment' => Language::replace('Browse image uploaded, type :type', array(':type' => 'jpg, jpeg, png')).' ({LNG_resized automatically})',
            'dataPreview' => 'imgPicture',
            'previewSrc' => $img,
            'accept' => array('jpg', 'jpeg', 'png'), 
        ));  

        if (Login::checkPermission($login, 'approve_repair')) {
            // operator_id
            $fieldset->add('hidden', array( //select
                'id' => 'operator_id',
              /*  'labelClass' => 'g-input icon-customer',
                'itemClass' => 'item',
                'label' => '{LNG_Operator}', */
                'options' => \Repair\Operator\Model::create_approve()->toSelect_approve(),
                'value' => $index->operator_id,
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
        // repair_id
        $fieldset->add('hidden', array(
            'id' => 'repair_id',
            'value' => $index->id,
        ));
       

        // คืนค่า HTML
        return $form->render();
    }

}
