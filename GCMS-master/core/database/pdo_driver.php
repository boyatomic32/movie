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
 * PDO Database Adapter Class.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class PDO_driver extends Driver
{
    /**
     * @param string $params
     *
     * @return bool
     */
    public function __construct($params) {
        parent::__construct($params);
        // pdo options
        $options = array();
        $options[PDO::ATTR_PERSISTENT] = true;
        $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        if ($this->dbdriver == 'mysql') {
            $options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES '.$this->char_set;
        }
        // pdo connect
        try {
            // connection string
            $sql = $this->dbdriver.':host='.$this->hostname;
            $sql .= empty($this->port) ? '' : ';port='.$this->port;
            $sql .= empty($this->dbname) ? '' : ';dbname='.$this->dbname;
            // connect to database
            $this->connection = new PDO($sql, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            Gcms::debug($e->getMessage());
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
        $result = $this->connection->_query("USE $database");

        return $result === false ? false : true;
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
        $keys = array();
        $datas = array();
        if (is_array($fields)) {
            foreach ($fields as $i => $field) {
                $keys[] = "`$field`=:$field";
                if (is_array($values)) {
                    $datas[":$field"] = $values[$i];
                } else {
                    $datas[":$field"] = $values;
                }
            }
        } else {
            if (is_array($values)) {
                $ks = array();
                foreach ($values as $value) {
                    $ks[] = '?';
                    $datas[] = $value;
                }
                $keys[] = "`$fields` IN (".implode(',', $ks).')';
            } else {
                $keys[] = "`$fields`=:$fields";
                $datas[":$fields"] = $values;
            }
        }
        try {
            $sql = "SELECT * FROM `$table` WHERE ".implode(' OR ', $keys).' LIMIT 1';
            $query = $this->connection->prepare($sql);
            $query->execute($datas);
            $result = array();
            if ($query) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    return $row;
                }
            }

            return false;
        } catch (PDOException $e) {
            Gcms::debug($e->getMessage());

            return false;
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
        try {
            $keys = array();
            $values = array();
            foreach ($recArr as $key => $value) {
                $keys[] = $key;
                $values[":$key"] = $value;
            }
            $sql = 'INSERT INTO `'.$table.'` (`'.implode('`,`', $keys);
            $sql .= '`) VALUES (:'.implode(',:', $keys).');';
            $query = $this->connection->prepare($sql);
            $query->execute($values);
            ++$this->time;

            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            Gcms::debug($e->getMessage());

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
        try {
            $keys = array();
            $values = array();
            foreach ($recArr as $key => $value) {
                $keys[] = "`$key`=:$key";
                $values[":$key"] = $value;
            }
            if (is_array($idArr)) {
                $datas = array();
                foreach ($idArr as $key => $value) {
                    $datas[] = "`$key`=:$key";
                    $values[":$key"] = $value;
                }
                $where = sizeof($datas) == 0 ? '' : implode(' AND ', $datas);
            } else {
                $id = (int)$idArr;
                $where = $id == 0 ? '' : '`id`=:id';
                $values[':id'] = $id;
            }
            if ($where == '' || sizeof($keys) == 0) {
                return false;
            } else {
                $sql = "UPDATE `$table` SET ".implode(',', $keys)." WHERE $where LIMIT 1";
                $query = $this->connection->prepare($sql);
                $query->execute($values);
                ++$this->time;

                return true;
            }
        } catch (PDOException $e) {
            Gcms::debug($e->getMessage());

            return false;
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
        try {
            $query = $this->connection->query($sql);
            ++$this->time;

            return $query->rowCount();
        } catch (PDOException $e) {
            $this->error_message = $e->getMessage();

            return false;
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
        try {
            $query = $this->connection->query($sql);
            $result = array();
            if ($query) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $result[] = $row;
                }
            }
            ++$this->time;

            return $result;
        } catch (PDOException $e) {
            $this->error_message = $e->getMessage();

            return false;
        }
    }
    /**
     * close database.
     */
    protected function _close() {

    }
}