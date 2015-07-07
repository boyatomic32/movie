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
 * MySQL Database Adapter Class.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class MYSQL_driver extends GcmsDriver
{
    /**
     * @param string $params
     *
     * @return bool
     */
    public function __construct($params) {
        parent::__construct($params);
        // mysql connect
        $conn = @mysql_connect($this->hostname, $this->username, $this->password, true);
        if ($conn != false) {
            $db = mysql_select_db($this->dbname, $conn);
            @mysql_query('SET NAMES '.$this->char_set, $conn);
        }
        if ($conn == false) {
            Gcms::debug(mysql_error($conn));

            return false;
        } else {
            $this->connection = $conn;

            return true;
        }
    }
    /**
     * เลือกฐานข้อมูล.
     *
     * @param string $database
     *
     * @return bool false หากไม่สำเร็จ
     */
    public function selectDB($database) {
        $this->dbname = $database;
        $db = mysql_select_db($this->dbname, $this->connection);
        @mysql_query('SET NAMES '.$this->char_set, $this->connection);

        return $db;
    }
    /**
     * ค้นหาข้อมูลที่กำหนดเองเพียงรายการเดียว.
     *
     * @param string $table ชื่อตาราง
     * @param array|string $fields ชื่อฟิลด์
     * @param array|string $values ข้อความค้นหาในฟิลด์ที่กำหนด ประเภทเดียวกันกับ $fields
     *
     * @return array|bool พบคืนค่ารายการที่พบเพียงรายการเดียว ไม่พบหรือมีข้อผิดพลาดคืนค่า false
     */
    public function basicSearch($table, $fields, $values) {
        $search = array();
        if (is_array($fields)) {
            foreach ($fields as $i => $field) {
                if (is_array($values)) {
                    $search[] = "`$field`='$values[$i]'";
                } else {
                    $search[] = "`$field`='$values'";
                }
            }
        } else {
            if (is_array($values)) {
                $search[] = "`$fields` IN ('".implode("','", $values)."')";
            } else {
                $search[] = "`$fields`='$values'";
            }
        }
        $sql = "SELECT * FROM `$table` WHERE ".implode(' OR ', $search).' LIMIT 1';
        $query = @mysql_query($sql, $this->connection);
        if ($query == false) {
            Gcms::debug(mysql_error($this->connection));

            return false;
        } else {
            ++$this->time;
            if (mysql_num_rows($query) == 1) {
                $result = mysql_fetch_array($query, MYSQL_ASSOC);
                mysql_free_result($query);

                return $result;
            } else {
                return false;
            }
        }
    }
    /**
     * ฟังก์ชั่นเพิ่มข้อมูลใหม่ลงในตาราง.
     *
     * @param string $table ชื่อตาราง
     * @param array $recArr ข้อมูลที่ต้องการบันทึก
     *
     * @return int|bool สำเร็จ คืนค่า id ที่เพิ่ม ผิดพลาด คืนค่า false
     */
    public function add($table, $recArr) {
        $keys = array();
        $values = array();
        foreach ($recArr as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }
        $sql = 'INSERT INTO `'.$table.'` (`'.implode('`,`', $keys);
        $sql .= "`) VALUES ('".implode("','", $values);
        $sql .= "');";
        $query = @mysql_query($sql, $this->connection);
        if ($query == false) {
            Gcms::debug(mysql_error($this->connection));

            return false;
        } else {
            ++$this->time;

            return mysql_insert_id($this->connection);
        }
    }
    /**
     * แก้ไขข้อมูล.
     *
     * @param string $table ชื่อตาราง
     * @param array|string $idArr id ที่ต้องการแก้ไข หรือข้อความค้นหารูปแอเรย์ [filed=>value]
     * @param array $recArr ข้อมูลที่ต้องการบันทึก
     *
     * @return bool สำเร็จ คืนค่า true
     */
    public function edit($table, $idArr, $recArr) {
        if (is_array($idArr)) {
            $datas = array();
            foreach ($idArr as $key => $value) {
                $datas[] = "`$key`='$value'";
            }
            $id = implode(' AND ', $datas);
        } else {
            $id = (int)$idArr;
            $id = $id == 0 ? '' : "`id`='$id'";
        }
        if ($id == '') {
            return false;
        } else {
            $datas = array();
            foreach ($recArr as $key => $value) {
                $datas[] = "`$key`='$value'";
            }
            $sql = "UPDATE `$table` SET ".implode(',', $datas)." WHERE $id LIMIT 1";
            $query = @mysql_query($sql, $this->connection);
            if ($query == false) {
                Gcms::debug(mysql_error($this->connection));

                return false;
            } else {
                ++$this->time;

                return true;
            }
        }
    }
    /**
     * ประมวลผลคำสั่ง SQL ที่ไม่ต้องการผลลัพท์ เช่น CREATE INSERT UPDATE.
     *
     * @param string $sql
     *
     * @return int|bool สำเร็จ คืนค่าจำนวนแถวที่ทำรายการ มีข้อผิดพลาดคืนค่า false
     */
    protected function _query($sql) {
        $query = @mysql_query($sql, $this->connection);
        if ($query == false) {
            $this->error_message = mysql_error($this->connection);

            return false;
        } else {
            ++$this->time;

            return mysql_affected_rows();
        }
    }
    /**
     * ประมวลผลคำสั่ง SQL สำหรับสอบถามข้อมูล คืนค่าผลลัพท์เป็นแอเรย์ของข้อมูลที่ตรงตามเงื่อนไข.
     *
     * @param string $sql query string
     *
     * @return array|bool คืนค่าผลการทำงานเป็น record ของข้อมูลทั้งหมดที่ตรงตามเงื่อนไข ไม่พบข้อมูลคืนค่าเป็น array ว่างๆ ผิดพลาดคืนค่า false
     */
    protected function _customQuery($sql) {
        $result = array();
        $query = @mysql_query($sql, $this->connection);
        if ($query == false) {
            $this->error_message = mysql_error($this->connection);

            return false;
        } else {
            ++$this->time;
            while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
                $result[] = $row;
            }
            mysql_free_result($query);
        }

        return $result;
    }
    /**
     * กรองอักขระพิเศษ ที่รับมาจาก INPUT.
     *
     * @param string $value ข้อความ
     *
     * @return string คืนค่าข้อความ
     */
    public function sql_clean($value) {
        if ((function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) || ini_get('magic_quotes_sybase')) {
            $value = stripslashes($value);
        }
        if (function_exists('mysql_real_escape_string')) {
            $value = mysql_real_escape_string($value);
        } else {
            // PHP version < 4.3.0 use addslashes
            $value = addslashes($value);
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
        return str_replace('\\\\', '&#92;', $this->sql_clean($value));
    }
    /**
     * close database.
     */
    protected function _close() {
        if ($this->connection) {
            @mysql_close($this->connection);
        }
    }
}