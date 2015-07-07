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
define('FORMAT_PCRE', 0);
define('FORMAT_TEXT', 1);
/**
 * View base class.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
abstract class View
{
    private static $instance;
    private static $datas = array();
    const FORMAT_PCRE = 0;
    const FORMAT_TEXT = 1;
    /**
     * Class constructor.
     */
    public function __construct() {
        self::$instance = &$this;
    }
    /**
     * ฟังก์ชั่นหลักสำหรับจัดการ View คืนค่าเป็น string.
     *
     * @param mixed $params
     *
     * @return string
     */
    abstract public function render($param);
    /**
     * ฟังก์ชั่นกำหนดค่าตัวแปรของ template
     *
     * @param array $array ชื่อที่ปรากฏใน template รูปแบบ array(key1=>val1,key2=>val2)
     * @param int $option FORMAT_TEXT = คีย์แบบข้อความ, FORMAT_PCRE = คีย์แบบ PCRE
     */
    public function set($array, $option = FORMAT_TEXT) {
        foreach ($array as $key => $value) {
            if ($option === FORMAT_TEXT) {
                self::$datas['/{'.$key.'}/'] = $value;
            } else {
                self::$datas[$key] = $value;
            }
        }
    }
    /**
     * โหลด template
     * ครั้งแรกจะตรวจสอบไฟล์จาก $module ถ้าไม่พบ จะใช้ไฟล์จาก $owner
     *
     * @param string $owner ชื่อโมดูลที่ติดตั้ง
     * @param string $module ชื่อโมดูลที่ลงทะเบียน
     * @param string $name ชื่อ template ไม่ต้องระบุนามสกุลของไฟล์
     *
     * @return string ถ้าไม่พบคืนค่าว่าง
     */
    public function loadTemplate($owner, $module, $name) {
        $template_root = \Core\Gcms::get('config', 'template_root');
        if ($module != '' && is_file($template_root.SKIN."{$module}/{$name}.html")) {
            $result = file_get_contents($template_root.SKIN."{$module}/{$name}.html");
        } else if ($owner != '' && is_file($template_root.SKIN."{$owner}/{$name}.html")) {
            $result = file_get_contents($template_root.SKIN."{$owner}/{$name}.html");
        } else if (is_file($template_root.SKIN."{$name}.html")) {
            $result = file_get_contents($template_root.SKIN."{$name}.html");
        } else {
            $result = '';
        }
        return $result;
    }
    /**
     * แสดงผล เป็น HTML.
     */
    public function renderHTML($template) {
        // default for template
        self::$datas['/{WEBTITLE}/'] = \Core\Gcms::get('config', 'web_title');
        self::$datas['/{WEBDESCRIPTION}/'] = \Core\Gcms::get('config', 'web_description');
        self::$datas['/{LANGUAGE}/'] = LANGUAGE;
        self::$datas['/{WEBURL}/'] = WEB_URL;
        self::$datas['/{SKIN}/'] = SKIN;
        self::$datas['/{ELAPSED}/'] = sprintf('%.3f', microtime(true) - BEGIN_TIME);
        self::$datas['/{USAGE}/'] = memory_get_usage(false) / 1024;
        // แทนที่ลงใน template
        echo \Core\Gcms::pregReplace(array_keys(self::$datas), array_values(self::$datas), $template);
    }
    /**
     * แสดงผล เป็น XML.
     */
    public function renderXML() {

    }
    /**
     * แสดงผล เป็น JSON.
     */
    public function renderSON() {

    }
    /**
     * ฟังก์ชั่น โหลดไฟล์ ตัด \t และ \r ออก.
     *
     * @param string $file ชื่อไฟล์รวม path
     *
     * @return string คืนค่าข้อมูลในไฟล์ ถ้าไม่พบคืนค่าว่าง
     */
    public static function loadFile($file) {
        return is_file($file) ? preg_replace('/[\t\r]/', '', file_get_contents($file)) : '';
    }
}