<?php

namespace Css;

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
 * Controller สำหรับจัดการ CSS ของ GCMS
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
        // cache 1 month
        $expire = 2592000;
        \Core\Gcms::header(array(
            'Content-type' => 'text/css; charset: UTF-8',
            'Cache-Control' => 'max-age='.$expire.', must-revalidate, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + $expire).' GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s', time() - $expire).' GMT'
        ));
        // โหลด css หลัก
        $data = preg_replace('/url\(([\'"])?fonts\//isu', "url(\\1".WEB_URL.'/skin/fonts/', file_get_contents(ROOT_PATH.'skin/fonts.css'));
        $data .= file_get_contents(ROOT_PATH.'skin/gcss.css');
        // โหลดจาก template
        $template = str_replace(ROOT_PATH, '', \Core\Gcms::get('config', 'template_root'));
        $skin = 'skin/'.\Core\Gcms::get('config', 'skin');
        $data2 = file_get_contents(ROOT_PATH.$template.$skin.'/style.css');
        $data2 = preg_replace('/url\(([\'"])?(img|fonts)\//isu', "url(\\1".WEB_URL.'/'.$skin.'/\\2/', $data2);
        // compress css
        $data = preg_replace(array('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '/\s?([:;,>\{\}])\s?/s'), array('', '\\1', ' '), $data.$data2);
        // ตัด > ใน ie ต่ำกว่า 7
        if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', \Core\Gcms::get($_SERVER, 'HTTP_USER_AGENT'), $matched)) {
            if ((int)$matched[1] < 7) {
                $data = str_replace('>', ' ', $data);
            }
        }
        // render
        \Core\Gcms::createClass('Css\View')->render($data);
    }
}