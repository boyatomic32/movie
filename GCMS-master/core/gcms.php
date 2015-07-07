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
 * GCMS framework base class
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Gcms
{
    private static $instance;
    public static $CFG;
    public static $DB;
    private static $header;
    /**
     * inint class.
     */
    public function __construct() {
        self::$CFG = array();
        self::$header = array();
        return self::$instance;
    }
    /**
     * สร้าง Application สามารถเรียกใช้ได้ครั้งเดียวเท่านั้น
     *
     * @return object
     */
    public static function createWebApplication() {
        if (!isset(self::$instance)) {
            $obj = __CLASS__;
            self::$instance = new $obj();
        }
        // return current instance
        return self::$instance;
    }
    /**
     * โหลด GCMS เพื่อแสดงผลหน้าเว็บไซต์
     */
    public function run() {
        /**
         * inint session
         */
        session_start();
        if (!ob_get_status()) {
            if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
                // เปิดใช้งานการบีบอัดหน้าเว็บไซต์
                ob_start('ob_gzhandler');
            } else {
                ob_start();
            }
        }
        /**
         * โหลด GCMS
         */
        $this->inint();
        /**
         * save variable
         */
        setCookie('gcms_language', LANGUAGE, time() + 3600 * 24 * 365);
        /**
         * create Router
         */
        self::createClass('Core\Router');
        /**
         * return current instance
         */
        return self::$instance;
    }
    /**
     * โหลด GCMS เพื่อประมวลผล
     */
    public function inint() {
        /**
         * โหลด config
         */
        $config = array();
        $config['hour'] = 0;
        $config['languages'][0] = 'th';
        $config['skin'] = '';
        $config['datas_folder'] = 'datas/';
        $config['template_root'] = APP_PATH;
        self::$CFG['config'] = new \Core\ListItem($config);
        self::$CFG['config']->loadFromFile(APP_PATH.'settings/config.php');
        /**
         * display error
         */
        if (self::get('config', 'debug', true)) {
            // ขณะออกแบบ แสดง error และ warning ของ PHP
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(-1);
        } else {
            // ขณะใช้งานจริง
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
        }
        /**
         * โฟลเดอร์สำหรับเก็บข้อมูลต่างๆ นับจาก root ของ server
         */
        define('DATA_FOLDER', self::get('config', 'datas_folder'));
        define('DATA_PATH', APP_PATH.DATA_FOLDER);
        define('DATA_URL', WEB_URL.'/'.DATA_FOLDER);
        /**
         * ภาษาที่เลือก
         */
        $languages = self::get('config', 'languages');
        $language = self::get('GET,SESSION,COOKIE', 'lang,gcms_language,gcms_language', $languages[0]);
        $language = is_file(DATA_PATH."language/$language.php") ? $language : 'th';
        define('LANGUAGE', $language);
        /**
         * skin
         */
        $skin = \Core\Gcms::get('config', 'skin');
        $template_root = \Core\Gcms::get('config', 'template_root');
        $skin = is_file($template_root.'skin/'.$skin.'/style.css') ? $skin : 'default';
        define('SKIN', "skin/$skin/");
        /**
         * return current instance
         */
        return self::$instance;
    }
    /**
     *  เชื่อมต่อ Database
     *
     * @return \Core\Database
     */
    public static function database() {
        if (!isset(self::$DB)) {
            $conn = self::get('config', 'db_driver').'://';
            $conn .= self::get('config', 'db_username').':'.self::get('config', 'db_password').'@'.self::get('config', 'db_server');
            $port = self::get('config', 'db_port');
            if (!empty($port)) {
                $conn .= ':'.$port;
            }
            $conn .= '/'.self::get('config', 'db_name');
            self::$DB = self::createClass('Core\Database')->connect($conn);
        }
        return self::$DB;
    }
    /**
     * ฟังก์ชั่นโหลด Class มาใช้งาน
     *
     * @param string $className ชื่อ class ที่ต้องการ carmelCase
     *
     * @return \Core\className
     */
    public static function createClass($className) {
        // create new class
        $object = new $className();
        return $object;
    }
    /**
     * ฟังก์ชั่น ตรวจสอบการ login
     *
     * @return boolean คืนค่า true ถ้า Login อยู่
     */
    public static function isMember() {
        return isset($_SESSION['login']);
    }
    /**
     * ฟังก์ชั่น ตรวจสอบสถานะแอดมิน (สูงสุด)
     *
     * @return boolean คืนค่า true ถ้าเป็นแอดมินระดับสูงสุด
     */
    public static function isAdmin() {
        return isset($_SESSION['login']) && $_SESSION['login']['status'] == 1;
    }
    /**
     * อ่านค่าจากตัวแปรของไซต์ เช่น config ต่างๆ และ $_GET $_POST $_SESSION $_COOKIE $_SERVER $_REQUEST
     *
     * @param string $className ชื่อ class ที่ต้องการอ่านตัวแปร หรือชื่อตัวแปร เช่น GET POST REQUEST SESSION COOKIE SERVER
     * @param string $name ชื่อตัวแปร ถ้าไม่ระบุ $name และเป็น Lisitem จะคืนค่าสมาชิกทั้งหมด ของ $className
     * @param mixed $default (option) ค่าเริ่มต้นหากไม่พบตัวแปร, ตัวเลข เช่น 0 จะแปลงเป็น int, ตัวเลขรวมจุดทศนิยม แปลงเป็น double
     *
     * @return mixed ค่าตัวแปร $className[$name] ถ้าไม่พบคืนค่า แปลงชนิดของตัวแปรตามที่กำหนดโดย $default
     */
    public static function get($className, $name = '', $default = '') {
        if (is_array($className)) {
            $result = isset($className[$name]) ? $className[$name] : $default;
        } else if (preg_match_all('/(GET|POST|REQUEST|SESSION|COOKIE|SERVER)/', $className, $keys)) {
            $result = self::_filter_vars($keys[0], explode(',', $name));
        } else {
            if (!isset(self::$CFG[$className])) {
                self::$CFG[$className] = new \Core\ListItem();
                self::$CFG[$className]->loadFromFile(APP_PATH.'settings/'.$className.'.php');
            }
            if ($name == '') {
                if (method_exists(self::$CFG[$className], 'items')) {
                    $result = self::$CFG[$className]->items();
                }
            } else {
                $result = self::$CFG[$className]->get($name, null);
            }
        }
        if (empty($result) && $name != '') {
            return $default;
        } else {
            if (is_float($default)) {
                // จำนวนเงิน เช่น 0.0
                $result = (double)$result;
            } elseif (is_int($default)) {
                // เลขจำนวนเต็ม เช่น 0
                $result = (int)$result;
            }
            return $result;
        }
    }
    /**
     * ฟังก์ชั่นอ่านค่าจากตัวแปร $_GET $_POST $_SESSION $_COOKIE $_SERVER $_REQUEST
     *
     * @param array $vars แอแเรย์ของ GET POST REQUEST SESSION COOKIE และ SERVER
     * @param array $keys ค่าคีย์ที่ต้องการ สัมพันธ์กับ $vars
     *
     * @return mixed คืนค่าตัวแปรจาก $vars[$keys] ตัวแรกที่พบ ถ้าไม่พบเลยคืนค่า null
     */
    private static function _filter_vars($vars, $keys) {
        $result = null;
        foreach ($vars as $i => $var) {
            $key = $keys[$i];
            if ($var == 'GET') {
                $result = isset($_GET[$key]) ? $_GET[$key] : null;
            } elseif ($var == 'POST') {
                $result = isset($_POST[$key]) ? $_POST[$key] : null;
            } elseif ($var == 'SESSION') {
                $result = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
            } elseif ($var == 'COOKIE') {
                $result = isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
            } elseif ($var == 'SERVER') {
                $result = isset($_SERVER[$key]) ? $_SERVER[$key] : null;
            } else {
                $result = isset($_POST[$key]) ? $_POST[$key] : isset($_GET[$key]) ? isset($_GET[$key]) : null;
            }
            if ($result !== null) {
                break;
            }
        }
        return $result;
    }
    /**
     * ฟังก์ชั่น preg_replace ของ gcms
     *
     * @param array $patt คีย์ใน template
     * @param array $replace ข้อความที่จะถูกแทนที่ลงในคีย์
     * @param string $skin template
     * @return string คืนค่า HTML template
     */
    public static function pregReplace($patt, $replace, $skin) {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            return preg_replace($patt, $replace, $skin);
        } else {
            if (!is_array($patt)) {
                $patt = array($patt);
            }
            if (!is_array($replace)) {
                $replace = array($replace);
            }
            foreach ($patt AS $i => $item) {
                if (preg_match('/(.*\/(.*?))[e](.*?)$/', $item, $patt) && preg_match('/^([a-zA-Z0-9_\\\\:]+).*/', $replace[$i], $func)) {
                    $skin = preg_replace_callback($patt[1].$patt[3], $func[1], $skin);
                } else {
                    $skin = preg_replace($item, $replace[$i], $skin);
                }
            }
            return $skin;
        }
    }
    /**
     * แสดงผล Widget
     *
     * @param array $matches
     */
    public static function getWidgets($matches) {

    }
    /**
     * กำหนด PHP header
     *
     * @param array $array ข้อมูล header ในรูปแอเรย์เช่น  array(header1=>val1, header2=>val2)
     */
    public static function header($array) {
        if (!isset(self::$CFG['header'])) {
            self::$CFG['header'] = new \Core\ListItem($array);
        } else {
            self::$CFG['header']->assign($array);
        }
    }
    /**
     * จัดการข้อความผิดพลาด.
     *
     * @param string $message ข้อความผิดพลาด
     */
    public static function debug($message) {
        $caller = debug_backtrace();
        $caller = next($caller);
        $error_msg = '<br>Error : <b>'.$caller['function'].'</b> : '.$message.' called from <b>'.$caller['file'].'</b> on line <b>'.$caller['line'].'</b>';
        echo $error_msg;
    }
}