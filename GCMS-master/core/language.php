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
 * คลาสสำหรับจัดการเกี่ยวกับ ภาษาของ GCMS
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Language
{
    private static $lng;
    private static $source;
    /**
     * อ่านค่าตัวแปรภาษา
     *
     * @param array|string $key ชื่อตัวแปรที่ต้องการ
     * string เป็นการเรียกใช้ฟังก์ชั่นปกติ หรือ
     * array เป็นการเรียกใช้จ่ากฟังก์ชั่น preg_replace_callback
     *
     * @return mixed ค่าตัวแปรที่อ่านได้
     */
    public static function get($key) {
        if (!isset(self::$lng)) {
            $lng = array();
            $file = DATA_PATH.'language/'.LANGUAGE.'.php';
            if (is_file($file)) {
                include $file;
                self::$source = $file;
            }
            self::$lng = $lng;
        }
        $key = is_array($key) ? $key[1] : $key;
        return isset(self::$lng[$key]) ? self::$lng[$key] : '';
    }
}