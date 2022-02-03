<?php
/**
 * @filesource modules/repair/views/printasset.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Printasset;

use Kotchasan\Template;

/**
 * module=repair-printasset
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
        $template = Template::createFromFile(ROOT_PATH.'modules/repair/template/printasset.html'); 
        $template->add($content);
        echo $template->render();
    }
}
