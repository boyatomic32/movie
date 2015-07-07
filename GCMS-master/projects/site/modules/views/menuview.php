<?php

namespace Menu;

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
 * สร้างเมนูหลัก
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends GcmsView
{
    /**
     * inint Controller.
     *
     * @param array $items
     *
     * @return string
     */
    public function render($items) {
        // รายการเมนู
        $menus['home'] = WEB_URL.'/index.php';
        $menus['about'] = WEB_URL.'/index.php?module=about';
        // สร้างเมนู
        $menu = '';
        foreach ($menus as $key => $value) {
            $c = $items['module'] == $key ? ' class=select' : '';
            $menu .= '<li'.$c.'><a href="'.$value.'"><span>'.ucfirst($key).'</span></a></li>';
        }
        return $menu;
    }
}