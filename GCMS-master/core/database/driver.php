<?php
/**
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @link http://gcms.in.th/
 *
 * @copyright 2015 Goragod.com
 * @license http://gcms.in.th/license/
 */
defined('ROOT_PATH') OR exit('No direct script access allowed');
/**
 * Database Driver Class
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Driver
{
    public $dbdriver;
    public $hostname;
    public $username;
    public $password;
    public $dbname;
    public $char_set = 'utf8';
    public $time = 0;
    public $connection = null;
    public $port = '';
    public $error_message = '';
    public $prefix;
    /**
     * @param array $params
     */
    public function __construct($params) {
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                $this->$key = $val;
            }
        }
        $this->prefix = \Core\Gcms::get('database', 'prefix').'_';
    }
    /**
     * จบ class.
     */
    public function __destruct() {
        $this->close();
    }
    /**
     * ฟังก์ชั่น อ่านค่า resource ID ของการเชื่อมต่อปัจจุบัน.
     *
     * @return resource
     */
    public function connection() {
        return $this->connection;
    }
    /**
     * @param string $database ชื่อฐานข้อมูล
     *
     * @return bool คืนค่า true หากมีฐานข้อมูลนี้อยู่ ไม่พบคืนค่า false
     */
    public function databaseExists($database) {
        $search = $this->_customQuery("SELECT 1 FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'");

        return sizeof($search) == 1 ? true : false;
    }
    /**
     * ฟังก์ชั่น ตรวจสอบว่ามีตาราง $table หรือไม่.
     *
     * @param string $table ชื่อตาราง
     *
     * @return bool คืนค่า true หากมีตารางนี้อยู่ ไม่พบคืนค่า false
     */
    public function tableExists($table) {
        return $this->_query("SELECT 1 FROM `$table` LIMIT 1") === false ? false : true;
    }
    /**
     * ฟังก์ชั่น ตรวจสอบว่ามีฟิลด์ $field ในตาราง $table หรือไม่.
     *
     * @param string $table ชื่อตาราง
     * @param string $field ชื่อฟิลด์
     *
     * @return bool คืนค่า true หากมีฟิลด์นี้อยู่ ไม่พบคืนค่า false
     */
    public function fieldExists($table, $field) {
        if ($table != '' && $field != '') {
            $field = strtolower($field);
            // query table fields
            $result = $this->_customQuery("SHOW COLUMNS FROM `$table`");
            if ($result === false) {
                \Core\Gcms::debug("fieldExists($table, $field)", $this->error_message);
            } else {
                foreach ($result as $item) {
                    if (strtolower($item['Field']) == $field) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
    /**
     * สอบถามข้อมูลที่ $id เพียงรายการเดียว.
     *
     * @param string $table ชื่อตาราง
     * @param int $id id ที่ต้องการอ่าน
     *
     * @return array|bool พบคืนค่ารายการที่พบเพียงรายการเดียว ไม่พบคืนค่า false
     */
    public function getRec($table, $id) {
        $sql = "SELECT * FROM `$table` WHERE `id`=".(int)$id.' LIMIT 1';
        $result = $this->customQuery($sql);

        return sizeof($result) == 1 ? $result[0] : false;
    }
    /**
     * ฟังก์ชั่นลบข้อมูล.
     *
     * @param string $table ชื่อตาราง
     * @param int $id id ที่ต้องการลบ
     *
     * @return string  สำเร็จ คืนค่าว่าง ไม่สำเร็จคืนค่าข้อความผิดพลาด
     */
    public function delete($table, $id) {
        $sql = "DELETE FROM `$table` WHERE `id`=".(int)$id.' LIMIT 1';
        $result = $this->query($sql);

        return $result === false ? $this->error_message : '';
    }
    /**
     * ประมวลผลคำสั่ง SQL ที่ไม่ต้องการผลลัพท์ เช่น CREATE INSERT UPDATE.
     *
     * @param string $sql
     *
     * @return int|bool สำเร็จ คืนค่าจำนวนแถวที่ทำรายการ มีข้อผิดพลาดคืนค่า false
     */
    public function query($sql) {
        $result = $this->_query($sql);
        if ($result === false) {
            \Core\Gcms::debug($sql, $this->error_message);
        }

        return $result;
    }
    /**
     * ประมวลผลคำสั่ง SQL สำหรับสอบถามข้อมูล คืนค่าผลลัพท์เป็นแอเรย์ของข้อมูลที่ตรงตามเงื่อนไข.
     *
     * @param string $sql query string
     *
     * @return array คืนค่าผลการทำงานเป็น record ของข้อมูลทั้งหมดที่ตรงตามเงื่อนไข ไม่พบข้อมูลคืนค่าเป็น array ว่างๆ
     */
    public function customQuery($sql) {
        $result = $this->_customQuery($sql);
        if ($result === false) {
            \Core\Gcms::debug($sql, $this->error_message);
            return array();
        } else {
            return $result;
        }
    }
    /**
     * อ่าน ID ล่าสุดของตาราง สำหรับตารางที่มีการกำหนด Auto_increment ไว้.
     *
     * @param string $table ชื่อตาราง
     *
     * @return int คืนค่า id ล่าสุดของตาราง
     */
    public function lastId($table) {
        $sql = "SHOW TABLE STATUS LIKE '$table'";
        $result = $this->_customQuery($sql);

        return sizeof($result) == 1 ? (int)$result[0]['Auto_increment'] : 0;
    }
    /**
     * ยกเลิกการ Lock ตารางทั้งหมดที่ได้ปิดกันไว้.
     *
     * @return bool สำเร็จ คืนค่า true
     */
    public function unLock() {
        return $this->query('UNLOCK TABLES') === false ? false : true;
    }
    /**
     * Lock ตาราง.
     *
     * @param string $table ชื่อตาราง
     *
     * @return bool สำเร็จ คืนค่า true
     */
    private function _lock($table) {
        return $this->query("LOCK TABLES $table") === false ? false : true;
    }
    /**
     * Lock ตาราง สำหรับการอ่าน.
     *
     * @param string $table ชื่อตาราง
     *
     * @return bool คืนค่า true ถ้าสำเร็จ
     */
    public function setReadLock($table) {
        return $this->_lock("`$table` READ");
    }
    /**
     * Lock ตาราง สำหรับการเขียน.
     *
     * @param string $table ชื่อตาราง
     *
     * @return bool คืนค่า true ถ้าสำเร็จ
     */
    public function setWriteLock($table) {
        return $this->_lock("`$table` WRITE");
    }
    /**
     * กรองอักขระพิเศษ ที่รับมาจาก INPUT และแปลง \ เป็น &#92;.
     *
     * @param string $value ข้อความ
     *
     * @return string คืนค่าข้อความ
     */
    public function sql_clean($value) {
        if ((function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) || ini_get('magic_quotes_sybase')) {
            $value = stripslashes($value);
        }

        return $value;
    }
    /**
     * กรองอักขระพิเศษ ที่รับมาจาก INPUT และแปลง \ เป็น &#92;.
     *
     * @param string $value ข้อความ
     *
     * @return string คืนค่าข้อความ
     */
    public function sql_quote($value) {
        return $this->sql_clean(str_replace('\\\\', '&#92;', $value));
    }
    /**
     * ลบช่องว่างด้านหัวและท้ายของความ กรองอักขระพิเศษ ที่รับมาจาก INPUT และแปลง \ เป็น &#92;.
     *
     * @param mixed $array ตัวแปรเก็บข้อความ
     * @param string $key key ของ $array เช่น $array[$key]
     *
     * @return string คืนค่าข้อความ
     */
    public function sql_trim($array, $key = '') {
        if (is_array($array)) {
            if (!isset($array[$key])) {
                return '';
            } else {
                return $this->sql_quote(trim($array[$key]));
            }
        } else {
            return $this->sql_quote(trim($array));
        }
    }
    /**
     * ลบช่องว่างด้านหัวและท้ายของความ กรองอักขระพิเศษ ที่รับมาจาก INPUT และแปลงอักขระพิเศษต่างๆเป็นรหัส HTML เช่น & แปลงเป็น &amp;.
     *
     * @param mixed $array ตัวแปรเก็บข้อความ
     * @param string $key key ของ $array เช่น $array[$key]
     *
     * @return string คืนค่าข้อความ
     */
    public function sql_trim_str($array, $key = '') {
        if (is_array($array)) {
            if (!isset($array[$key])) {
                return '';
            } else {
                return $this->sql_quote(htmlspecialchars(trim($array[$key])));
            }
        } else {
            return $this->sql_quote(htmlspecialchars(trim($array)));
        }
    }
    /**
     * ฟังก์ชั่น อ่านจำนวน query ทั้งหมดที่ทำงาน.
     *
     * @return int
     */
    public function queryCount() {
        return $this->time;
    }
    /**
     * close database.
     */
    public function close() {
        $this->_close();
        $this->connection = null;
    }
}