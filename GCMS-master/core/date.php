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
 * คลาสจัดการเกี่ยวกับวันที่และเวลา
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Date
{
        private static $mktime;
        /**
         * ฟังก์ชั่นอ่านค่าเวลาในรูป mktime
         *
         * @param int $mktime (optional)
         * @return int คืนค่าเวลาในรูป mktime ถ้าระบุ $mktime ฟังก์ชั่นจะคืนค่านี้ ถ้าไม่ระบุมา จะคืนค่าเวลาปัจจุบัน
         */
        protected static function _mktime($mktime = 0) {
                if (!isset(self::$mktime)) {
                        self::$mktime = mktime(date("H") + \Core\Gcms::get('config', 'hour', 0));
                }
                return empty($mktime) ? self::$mktime : $mktime;
        }
        /**
         * เวลาปัจจุบันรูปแบบ mktime
         */
        public static function mktime() {
                return self::_mktime();
        }
        /**
         * เวลาปัจจุบันรูปแบบ mktime
         */
        public static function today() {
                return (int)date('d', self::_mktime());
        }
        /**
         * เวลาปัจจุบันรูปแบบ mktime
         */
        public static function month() {
                return (int)date('m', self::_mktime());
        }
        /**
         * เวลาปัจจุบันรูปแบบ mktime
         */
        public static function year() {
                return (int)date('Y', self::_mktime());
        }
        /**
         * ฟังก์ชั่น แปลงเวลา (mktime) เป็นวันที่ตามรูปแบบที่กำหนด
         *
         * @param string $format (optional) รูปแบบของวันที่ที่ต้องการ (default DATE_FORMAT)
         * @param int $mktime (optional) เวลาในรูป mktime ถ้าไม่ระบุจะใช้เวลาปัจจุบัน
         *
         * @return string วันที่และเวลาตามรูปแบบที่กำหนดโดย $format
         */
        public static function format_date($format = '', $mktime = 0) {
                $format = $format === '' ? \Core\Language::get('DATE_FORMAT') : $format;
                $date_short = \Core\Language::get('DATE_SHORT');
                $date_long = \Core\Language::get('DATE_LONG');
                $month_short = \Core\Language::get('MONTH_SHORT');
                $month_long = \Core\Language::get('MONTH_LONG');
                $mktime = self::_mktime($mktime);
                if (preg_match_all('/(.)/u', $format, $match)) {
                        $ret = '';
                        foreach ($match[0] AS $item) {
                                switch ($item) {
                                        case ' ':
                                        case ':':
                                        case '/':
                                        case '-':
                                                $ret .= $item;
                                                break;
                                        case 'l':

                                                $ret .= $date_short[date('w', $mktime)];
                                                break;
                                        case 'L':
                                                $ret .=$date_long[date('w', $mktime)];
                                                break;
                                        case 'M':
                                                $ret .= $month_short[date('n', $mktime) - 1];
                                                break;
                                        case 'F':
                                                $ret .= $month_long[date('n', $mktime) - 1];
                                                break;
                                        case 'Y':
                                                $ret .= date('Y', $mktime) + (int)\Core\Language::get('YEAR_OFFSET');
                                                break;
                                        default:
                                                $ret .= date($item, $mktime);
                                }
                        }
                } else {
                        $ret = date($format, $mktime);
                }
                return $ret;
        }
        /**
         * ฟังก์ชั่น คำนวนความแตกต่างของวัน (อายุ)
         *
         * @param int $start_date วันที่เริ่มต้นหรือวันเกิด (mktime)
         * @param int $end_date วันที่สิ้นสุดหรือวันนี้ (mktime)
         * @return array คืนค่า ปี เดือน วัน [year, month, day] ที่แตกต่าง
         */
        public static function compare_date($start_date, $end_date) {
                $Year1 = (int)date("Y", $start_date);
                $Month1 = (int)date("m", $start_date);
                $Day1 = (int)date("d", $start_date);
                $Year2 = (int)date("Y", $end_date);
                $Month2 = (int)date("m", $end_date);
                $Day2 = (int)date("d", $end_date);
                // วันแต่ละเดือน
                $months = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
                // ปีอธิกสุรทิน
                if (($Year2 % 4) == 0) {
                        $months[2] = 29;
                }
                // ปีอธิกสุรทิน
                if ((($Year2 % 100) == 0) & (($Year2 % 400) != 0)) {
                        $months[2] = 28;
                }
                // คำนวนจำนวนวันแตกต่าง
                $YearDiff = $Year2 - $Year1;
                if ($Month2 >= $Month1) {
                        $MonthDiff = $Month2 - $Month1;
                } else {
                        $YearDiff--;
                        $MonthDiff = 12 + $Month2 - $Month1;
                }
                if ($Day1 > $months[$Month2]) {
                        $Day1 = 0;
                } elseif ($Day1 > $Day2) {
                        $Month2 = $Month2 == 1 ? 13 : $Month2;
                        $Day2 += $months[$Month2 - 1];
                        $MonthDiff--;
                }
                $ret['year'] = $YearDiff;
                $ret['month'] = $MonthDiff;
                $ret['day'] = $Day2 - $Day1;
                return $ret;
        }
        /**
         * แปลงวันที่ จาก mktime เป็น Y-m-d สามารถบันทึกลงฐานข้อมูลได้ทันที
         *
         * @param int $mktime (optional) วันที่ในรูป mktime ถ้าไม่ระบุใช้วันที่ปัจจุบัน
         *
         * @return string คืนค่าวันที่รูป Y-m-d
         */
        public static function mktime_to_sql_date($mktime = 0) {
                return date('Y-m-d', self::_mktime($mktime));
        }
        /**
         * แปลงวันที่ จาก mktime เป็น Y-m-d H:i:s สามารถบันทึกลงฐานข้อมูลได้ทันที
         *
         * @param int $mktime (optional) วันที่ในรูป mktime ถ้าไม่ระบุใช้วันที่ปัจจุบัน
         *
         * @return string คืนค่า วันที่และเวลาของ mysql เช่น Y-m-d H:i:s
         */
        public static function mktime_to_sql_datetime($mktime = 0) {
                return date('Y-m-d H:i:s', self::_mktime($mktime));
        }
        /**
         * แปลงวันที่ในรูป Y-m-d เป็นวันที่และเวลา เช่น 1 มค. 2555 12:00:00.
         *
         * @global array $lng ตัวแปรภาษา
         *
         * @param string $date วันที่ในรูป Y-m-d หรือ Y-m-d h:i:s
         * @param bool $short (optional) true=เดือนแบบสั้น, false=เดือนแบบยาว (default true)
         * @param bool $time (optional) true=คืนค่าเวลาด้วยถ้ามี, false=ไม่ต้องคืนค่าเวลา (default true)
         *
         * @return string คืนค่า วันที่และเวลา
         */
        public static function sql_date_to_date($date, $short = true, $time = true) {
                global $lng;
                if (preg_match('/([0-9]+){0,4}-([0-9]+){0,2}-([0-9]+){0,2}(\s([0-9]+){0,2}:([0-9]+){0,2}:([0-9]+){0,2})?/', $date, $match)) {
                        $match[1] = (int)$match[1];
                        $match[2] = (int)$match[2];
                        if ($match[1] == 0 || $match[2] == 0) {
                                return '';
                        } else {
                                $month = $short ? $lng['MONTH_SHORT'] : $lng['MONTH_LONG'];

                                return $match[3].' '.$month[$match[2] - 1].' '.((int)$match[1] + $lng['YEAR_OFFSET']).($time && isset($match[4]) ? $match[4] : '');
                        }
                } else {
                        return '';
                }
        }
        /**
         * ฟังก์ชั่น แปลงวันที่และเวลาของ sql เป็น mktime
         *
         * @param string $date วันที่ในรูปแบบ Y-m-d H:i:s
         *
         * @return int คืนค่าเวลาในรูป mktime
         */
        public static function sql_datetime_to_mktime($date) {
                preg_match('/([0-9]+){0,4}-([0-9]+){0,2}-([0-9]+){0,2}\s([0-9]+){0,2}:([0-9]+){0,2}:([0-9]+){0,2}/', $date, $match);
                return mktime($match[4], $match[5], $match[6], $match[2], $match[3], $match[1]);
        }
}