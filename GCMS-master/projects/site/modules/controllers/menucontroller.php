<?php

namespace Menu;

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
 * Controller สำหรับจัดการเมนูของ GCMS
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends GcmsController
{
    /*
     * inint Controller.
     *
     * @param array $params
     *
     * @return string
     */
    public function inint($modules = null) {
        // สร้างเมนู
        return \Core\Gcms::createClass('Menu\View')->render($modules);
    }
}