<?php
/**
 * @filesource modules/repair/views/export.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Export;

use Kotchasan\Template;

/**
 * module=repair-export
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

        $template = Template::createFromFile(ROOT_PATH.'modules/repair/template/export.html'); 
        $template->add($content);
        echo $template->render();
    }
}
