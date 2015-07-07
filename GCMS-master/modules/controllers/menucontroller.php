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
    private $menu;
    /**
     * Class constructor.
     */
    public function __construct() {
        $this->menus = array();
    }
    /**
     * โหลดรายการเมนูทั้งหมด.
     *
     * @param array $params
     *
     * @return array รายการเมนูทั้งหมด
     */
    public function inint($modules = null) {
        $menus = array();
        // โหลดเมนูและจัดลำดับเมนูตตามระดับของเมนู
        foreach (\Core\Gcms::createClass('Menu\Model')->inint() AS $i => $item) {
            if ($item['level'] == 0) {
                $menus[$item['parent']]['toplevel'][$i] = $item;
            } else {
                $menus[$item['parent']][$toplevel[$item['level'] - 1]][$i] = $item;
            }
            $toplevel[$item['level']] = $i;
        }
        $this->menu = new \Core\ListItem($menus);
        return $this->menu->items();
    }
    /**
     * สร้างเมนูตามตำแหน่งของเมนู (parent)
     *
     * @return array รายการเมนูทั้งหมด
     */
    public function render() {
        $view = \Core\Gcms::createClass('Menu\View');
        $result = array();
        foreach ($this->menu->items() AS $parent => $items) {
            if ($parent != '') {
                $result[$parent] = $view->render($items);
            }
        }
        return $result;
    }
    /**
     * อ่านเมนูรายการแรกสุด (หน้าหลัก)
     */
    public function homeMenu() {
        $items = $this->menu->items();
        if (sizeof($items) > 0 && isset($items['MAINMENU']['toplevel'][0])) {
            $menu = $items['MAINMENU']['toplevel'][0];
        } else {
            $menu = array();
        }
        return $menu;
    }
}