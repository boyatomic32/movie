<?php

namespace Core;

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
 * Router base class.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Router
{
    /**
     * Class constructor
     */
    public function __construct() {
        $request_uri = explode('?', rawurldecode($_SERVER['REQUEST_URI']));
        // query จาก URL ที่ส่งมา
        $modules = $_GET;
        unset($_GET);
        // แยก path
        Router::parseRoutes($request_uri[0], $modules);
        if (!empty($modules['return']) && is_file(APP_PATH.'modules/controllers/'.strtolower($modules['return']).'controller.php')) {
            // เรียก controller ที่กำหนดเอง
            $Controller = Gcms::createClass(ucfirst($modules['return']).'\Controller');
        } else {
            // เรียก site controller
            $Controller = Gcms::createClass('Site\Controller');
        }
        $Controller->inint($modules);
    }
    /**
     * แปลง path เป็น query string ตามที่กำหนดโดย $url_rules
     *
     * @param string path เช่น /a/b/c.html
     * @param pointer $modules คืนค่า query string ที่ตัวแปรนี้
     */
    public static function parseRoutes($path, &$modules) {
        if (preg_match('/^\/(.*)((\.html?)|\/)$/u', str_replace(BASE_PATH, '', $path), $match)) {
            $my_path = $match[1];
        } else if (preg_match('/^\/(.*)$/u', str_replace(BASE_PATH, '', $path), $match)) {
            $my_path = $match[1];
        }
        if (isset($my_path)) {
            /**
             * กฏของ Router สำหรับการแยกหน้าเว็บไซต์
             */
            $config = array(
                // css,js
                '/^(css|js).*?/' => array('', 'return'),
                // admin,sys-admin
                '/^((sys\-)?admin).*?/' => array('', 'user'),
                // user/module/cat/id/document
                '/^([0-9a-z\-]+)\/([a-z]+)\/([0-9]+)\/([0-9]+)(\/(.*))?$/' => array('', 'user', 'module', 'cat', 'id', '', 'document'),
                // module/cat/id/document
                '/^([a-z]+)\/([0-9]+)\/([0-9]+)(\/(.*))?$/' => array('', 'module', 'cat', 'id', '', 'document'),
                // user/module/cat|id/document
                '/^([0-9a-z\-]+)\/([a-z]+)\/([0-9]+)(\/(.*))?$/' => array('', 'user', 'module', 'cat', '', 'document'),
                // module/cat|id/document
                '/^([a-z]+)\/([0-9]+)(\/(.*))?$/' => array('', 'module', 'cat', '', 'document'),
                // user/module/document
                '/^([0-9a-z\-]+)\/([a-z]+)(\/(.*))?$/' => array('', 'user', 'module', '', 'document'),
                // module/document
                '/^([a-z]+)(\/(.*))?$/' => array('', 'module', '', 'document')
            );
            foreach (\Core\Gcms::get('router', 'route_rules', $config) AS $patt => $items) {
                if (preg_match($patt, $my_path, $match)) {
                    foreach ($items AS $i => $key) {
                        if (!empty($key) && isset($match[$i])) {
                            $modules[$key] = $match[$i];
                        }
                    }
                    break;
                }
            }
        }
    }
}