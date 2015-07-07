<?php

namespace Site;

use Core\Controller as GcmsController;

/**
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @link http://gcms.in.th/
 *
 * @copyright 2015 Goragod.com
 * @license http://gcms.in.th/license/
 */
defined('ROOT_PATH') or exit('No direct script access allowed');
/**
 * Controller หลัก สำหรับแสดงเว็บไซต์
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends GcmsController
{
    /**
     * inint Controller.
     *
     * @param array $modules
     */
    public function inint($modules = null) {
        // ถ้าไม่มีโมดูลเลือกหน้า home
        $modules['module'] = empty($modules['module']) ? 'home' : $modules['module'];
        // สร้าง View
        $view = \Core\Gcms::createClass('Site\View');
        // สร้างเมนู
        $Menu = \Core\Gcms::createClass('Menu\Controller');
        $view->set(array(
            'MENU' => $Menu->inint($modules),
            'TITLE' => 'Welcome to GCMS++',
            // โหลดหน้าที่เลือก (html)
            'CONTENT' => $view->loadTemplate('', '', $modules['module'])
        ));
        // โหลดไฟล์ index.html
        $template = $view->loadTemplate('', '', 'index');
        // output เป็น HTML
        $view->renderHTML($template);
    }
}