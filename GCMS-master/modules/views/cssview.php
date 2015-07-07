<?php

namespace Css;

use Core\View as GcmsView;

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
 * Generate CSS file
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends GcmsView
{
    /**
     * สร้างไฟล์ CSS
     *
     * @param mixed $params
     */
    public function render($param = null) {
        // header
        foreach (\Core\Gcms::get('header') as $key => $value) {
            header("$key: $value");
        }
        // result
        echo preg_replace(array('/[\r\n\t]/s', '/[\s]{2,}/s', '/;}/'), array('', ' ', '}'), $param);
    }
}