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
 * Controller หลัก สำหรับแสดง GCMS
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
        $install_modules = array();
        $install_owners = array();
        $module_list = array();
        $site_model = \Core\Gcms::createClass('Site\Model');
        $site_view = \Core\Gcms::createClass('Site\View');
        $menu_controller = \Core\Gcms::createClass('Menu\Controller');
        // เมนูที่ติดตั้ง
        foreach ($menu_controller->inint() AS $item) {
            if (!empty($item['module']) && !isset($install_modules[$item['module']])) {
                $install_modules[$item['module']] = $item;
                $install_owners[$item['owner']][] = $item['module'];
            }
        }
        // โมดูลทั้งหมดที่ติดตั้ง
        foreach ($site_model->inint() AS $item) {
            if (!isset($install_modules[$item['module']])) {
                $install_modules[$item['module']] = $item;
                $install_owners[$item['owner']][] = $item['module'];
            }
        }
        // รายชื่อโมดูลทั้งหมด
        $module_list = array_keys($install_modules);
        // รายการ home
        $home = $menu_controller->homeMenu();
        // ถ้าไม่มีโมดูล เลือกเมนูรายการแรก
        if (empty($modules['module'])) {
            if (!empty($home['menu_url'])) {
                $url = \Core\Gcms::createUrl($home['menu_url']);
                foreach ($url->get('query') AS $k => $v) {
                    $modules[$k] = $v;
                }
                if (empty($modules['module'])) {
                    \Core\Router::parseRoutes($url->get('path'), $modules);
                }
            }
            if (empty($modules['module']) && !empty($home['module'])) {
                $modules['module'] = $home['module'];
            }
        }

        // ถ้าไม่มีโมดูลหรือ เลือกโมดูลแรกสุด
        if (empty($modules['module']) && !empty($module_list)) {
            $modules['module'] = $module_list[0];
        }
        if (empty($module_list) || !in_array($modules['module'], $module_list)) {
            // ไม่พบโมดูลที่เรียก 404
            $modules['module'] = '404';
        }
        // หน้าที่เรียก
        $page = $site_model->getModule($modules['module']);
        // โหลดเมนูใส่ template
        $site_view->set($menu_controller->render());
        // เนื้อหาทั่วไป
        $site_view->set(array(
            // content
            'CONTENT' => $page['detail'],
            // title
            'TITLE' => $page['topic'],
            // description
            'DESCRIPTION' => $page['description'],
            // keywords
            'KEYWORDS' => $page['keywords'],
            // quries
            'QURIES' => \Core\Gcms::database()->queryCount()
        ));
        // widget+ภาษา
        $site_view->set(array(
            // widgets
            '/{WIDGET_([A-Z]+)(([\s_])(.*))?}/e' => '\Core\Gcms::getWidgets(array(1=>"$1",3=>"$3",4=>"$4"))',
            // language
            '/{(LNG_[A-Z0-9_]+)}/e' => '\Core\Language::get(array(1=>"$1"))'
            ), FORMAT_PCRE);
        // โหลดไฟล์ index.html
        $template = $site_view->loadTemplate('', '', 'index');
        // output เป็น HTML
        $site_view->renderHTML($template);
    }
}