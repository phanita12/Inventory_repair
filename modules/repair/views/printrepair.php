<?php
/**
 * @filesource modules/repair/views/printrepair.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Printrepair;

use Kotchasan\Template;

/**
 * module=repair-printrepair
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View //extends \Gcms\View
{
    /**
     * ส่งออกข้อมูลเป็น HTML หรือ หน้าสำหรับพิมพ์.     *
     * @param array $content
     */
    public static function toPrint($content)
    {
        $template = Template::createFromFile(ROOT_PATH.'modules/repair/template/printrepair.html'); 
        $template->add($content);
        echo $template->render();
    }
}
