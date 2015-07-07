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
 * MySQLI Database Adapter Class.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class MYSQLI_driver extends GcmsDriver
{
    /**
     * @param string $params
     *
     * @return bool
     */
    public function __construct($params) {
        parent::__construct($params);
        // mysqli connect
        if (empty($this->port)) {
            $this->connection = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
        } else {
            $this->connection = new mysqli($this->hostname, $this->username, $this->password, $this->dbname, $this->port);
        }
        if ($this->connection->connect_error) {
            Gcms::debug($this->connection->connect_error);
        } else {
            $this->connection->set_charset($this->char_set);
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
        $db = $this->connection->select_db($database);
        $this->connection->set_charset($this->char_set);

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
        $query = $this->connection->query($sql);
        if ($query == false) {
            Gcms::debug($this->connection->error);

            return false;
        } else {
            ++$this->time;
            if ($query->num_rows == 1) {
                $result = $query->fetch_assoc();
                $query->free();

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
        if ($result = $this->connection->query($sql)) {
            $id = $this->connection->insert_id;
            ++$this->time;

            return $id;
        } else {
            Gcms::debug($this->connection->error);

            return false;
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
            $query = $this->connection->query($sql);
            if ($query == false) {
                Gcms::debug($this->connection->error);

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
        $query = $this->connection->query($sql);
        if ($query == false) {
            $this->error_message = $this->connection->error;

            return false;
        } else {
            ++$this->time;

            return $this->connection->affected_rows;
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
        $query = $this->connection->query($sql);
        if ($query == false) {
            $this->error_message = $this->connection->error;

            return false;
        } else {
            ++$this->time;
            while ($row = $query->fetch_assoc()) {
                $result[] = $row;
            }
            $query->free();
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

        return $this->connection->real_escape_string($value);
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
            $this->connection->close();
        }
    }
}