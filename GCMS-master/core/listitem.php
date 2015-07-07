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
 * คลาสสำหรับจัดการแอเรย์
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class ListItem
{
    /**
     * @var array ข้อมูล
     */
    private $datas;
    /**
     *
     * @var string ที่อยู่ไฟล์ที่โหลดมา
     */
    private $source;
    /**
     * เริ่มต้นใช้งานคลาสและกำหนดค่าเริ่มต้น
     *
     * @param array $config ค่าเริ่มต้น ถ้าไม่กำหนดหมายถึงแอเรย์ที่ไม่มีสมาชิก
     */
    public function __construct($config = array()) {
        $this->datas = is_array($config) ? $config : (array)$config;
    }
    /**
     * อ่านจำนวนสมาชิกทั้งหมด
     *
     * @return integer จำนวนสมาชิกทั้งหมด
     */
    public function count() {
        return sizeof($this->datas);
    }
    /**
     * อ่านจำนวนรายการทั้งหมด
     *
     * @return array คืนค่ารายการทั้งหมด
     */
    public function items() {
        return $this->datas;
    }
    /**
     * อ่านรายชื่อ keys
     *
     * @return array แอเรย์ของรายการ key ทั้งหมด
     */
    public function keys() {
        return array_keys($this->datas);
    }
    /**
     * อ่านรายการข้อมูลทั้งหมด
     *
     * @return array แอเรย์ของข้อมูลทั้งหมด
     */
    public function values() {
        return array_values($this->datas);
    }
    /**
     * อ่านข้อมูลที่ $index
     *
     * @param string $index
     *
     * @return mixed คืนค่ารายการที่ $index ถ้าไม่พบคืนค่า null
     */
    public function get($index) {
        if (isset($this->datas[$index])) {
            $result = $this->datas[$index];
        } else {
            $result = null;
        }
        return $result;
    }
    /**
     * เพิ่มรายการใหม่ที่ลำดับสุดท้าย ถ้ามี $index อยู่แล้วจะแทนที่รายการเดิม
     *
     * @param string $index
     * @param mixed $value
     */
    public function set($index, $value) {
        $this->datas[$index] = $value;
    }
    /**
     * อ่านข้อมูลรายการแรก
     *
     * @return mixed คืนค่าแอเรย์รายการแรก
     */
    public function firstItem() {
        return reset($this->datas);
    }
    /**
     * อ่านข้อมูลรายการสุดท้าย
     *
     * @return mixed คืนค่าแอเรย์รายการสุดท้าย
     */
    public function lastItem() {
        return end($this->datas);
    }
    /**
     * ลบรายการที่กำหนด
     *
     * @param string $index ของรายการที่ต้องการจะลบ
     *
     * @return boolean คืนค่า true ถ้าสำเร็จ, false ถ้าไม่พบ
     */
    public function delete($index) {
        if (isset($this->datas[$index])) {
            unset($this->datas[$index]);
            return true;
        }
        return false;
    }
    /**
     * นำเข้าข้อมูลครั้งละหลายรายการ
     *
     * @param array $array ข้อมูลที่ต้องการนำเข้า
     */
    public function assign($array) {
        $this->datas = array_merge($this->datas, $array);
    }
    /**
     * ลบข้อมูลทั้งหมด
     */
    public function clear() {
        unset($this->datas);
    }
    /**
     * เพิ่มรายการใหม่ต่อจากรายการที่ $index
     *
     * @param mixed $index
     * @param mixed $item รายการใหม่
     */
    public function insert($index, $item) {
        if (is_int($index) && $index == sizeof($this->datas)) {
            $this->datas[] = $item;
        } else {
            $temp = $this->datas;
            $this->datas = array();
            foreach ($temp AS $key => $value) {
                if ($key == $index) {
                    $this->datas[$key] = $value;
                    $this->datas[$index] = $item;
                } else {
                    $this->datas[$key] = $value;
                }
            }
        }
    }
    /**
     * เพิ่มรายการใหม่ก่อนรายการที่ $index
     *
     * @param mixed $index
     * @param mixed $item รายการใหม่
     */
    public function insertBefore($index, $item) {
        $temp = $this->datas;
        $this->datas = array();
        foreach ($temp AS $key => $value) {
            if ($key == $index) {
                $this->datas[$index] = $item;
                $this->datas[$key] = $value;
            } else {
                $this->datas[$key] = $value;
            }
        }
    }
    /**
     * ค้นหาข้อมูลในแอเรย์
     *
     * @param mixed $value รายการค้นหา
     *
     * @return mixed คืนค่า key ของรายการที่พบ ถ้าไม่พบคืนค่า false
     */
    public function indexOf($value) {
        return array_search($value, $this->datas);
    }
    /**
     * โหลดตัวแปรจากไฟล์ ค่าของตัวแปรจะอยู่ใน $config เท่านั้น
     *
     * @param string $file ชื่อไฟล์ที่ต้องการโหลด
     */
    public function loadFromFile($file) {
        $config = array();
        if (is_file($file)) {
            include $file;
            $this->source = $file;
        }
        $this->assign($config);
        return $this;
    }
    /**
     * บันทึกเป็นไฟล์
     *
     * @param string $file
     */
    public function saveToFile() {
        if (!isset($this->source) || empty($this->datas)) {
            return false;
        } else {
            $datas = array();
            $datas[] = '<'.'?php';
            $datas[] = '// '.$this->source;
            foreach ($this->datas AS $key => $value) {
                if (is_array($value)) {
                    foreach ($value AS $k => $v) {
                        if (is_array($v)) {
                            foreach ($v AS $k2 => $v2) {
                                $datas[] = '$config[\''.$key.'\'][\''.$k.'\'][\''.$k2.'\'] = \''.$v2.'\';';
                            }
                        } else {
                            $datas[] = '$config[\''.$key.'\'][\''.$k.'\'] = \''.$v.'\';';
                        }
                    }
                } elseif (is_int($value)) {
                    $datas[] = '$config[\''.$key.'\'] = '.$value.';';
                } else {
                    $datas[] = '$config[\''.$key.'\'] = \''.$value.'\';';
                }
            }
            $f = @fopen($this->source, 'wb');
            if (!$f) {
                return false;
            } else {
                fwrite($f, implode("\n", $datas));
                fclose($f);
                return true;
            }
        }
    }
}