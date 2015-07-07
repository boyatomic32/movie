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
 * คลาสสำหรับจัดการไฟล์และไดเร็คทอรี่
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class File
{
    /**
     * อ่านรายชื่อไฟล์ภายใต้ไดเร็คทอรี่รวมไดเร็คทอรี่ย่อย
     *
     * @param string $dir ไดเร็คทอรี่ มี / ปิดท้ายด้วย
     * @param string $result
     * @param array $filter (option) ไฟล์ฟิลเตอร์ ตัวพิมพ์เล็ก เช่น array('jpg','gif') แอเรย์ว่างหมายถึงทุกนามสกุล
     */
    public static function findFiles($dir, &$result, $filter = array()) {
        $f = opendir($dir);
        while (false !== ($text = readdir($f))) {
            if (!in_array($text, array('.', '..'))) {
                if (is_dir($dir.$text)) {
                    self::findFiles($dir.$text.'/', $result, $filter);
                } elseif (empty($filter) || in_array(self::getExtension($text), $filter)) {
                    $result[] = $dir.$text;
                }
            }
        }
        closedir($f);
    }
    /**
     * สำเนาไดเร็คทอรี่
     *
     * @param string $dir ไดเร็คทอรี่ต้นทาง มี / ปิดท้ายด้วย
     * @param string $todir ไดเร็คทอรี่ปลายทาง มี / ปิดท้ายด้วย
     */
    public static function copyDir($dir, $todir) {
        $f = opendir($dir);
        while (false !== ($text = readdir($f))) {
            if (!in_array($text, array('.', '..'))) {
                if (is_dir($dir.$text)) {
                    self::copyDir($dir.$text.'/', $todir.$text.'/');
                } else {
                    copy($dir.$text, $todir.$text);
                }
            }
        }
        closedir($f);
    }
    /**
     * อ่านนามสกุลของไฟล์เช่น config.php คืนค่า php
     *
     * @param string $path ไฟล์
     * @return string คืนค่า ext ของไฟล์ ตัวอักษรตัวพิมพ์เล็ก
     */
    public static function getExtension($path) {
        $exts = explode('.', strtolower($path));
        return end($exts);
    }
    /**
     * ฟังก์ชั่น อ่าน mimetype จาก file type แบบ ออนไลน์
     *
     * @param array $typies ชนิดของไฟล์ที่ต้องการอ่าน mimetype เช่น jpg gif png
     * @return array คืนค่า mimetype ที่พบ เช่น 'php'=>'text/html'
     */
    public static function getMimeTypies($typies) {
        $s = array();
        $es = array();
        if (is_array($config['mimeTypes'])) {
            foreach ($typies AS $ext) {
                if (!empty($config['mimeTypes'][$ext])) {
                    $s[$ext] = $config['mimeTypes'][$ext];
                } else {
                    $es[] = $ext;
                }
            }
        } else {
            $es = $typies;
        }
        if (sizeof($es) > 0) {
            $content = '';
            if (is_file(DATA_PATH.'cache/mime.types')) {
                $content = trim(@file_get_contents(DATA_PATH.'cache/mime.types'));
            }
            if ($content == '') {
                // ตรวจสอบ mimetype ออนไลน์
                $content = trim(@file_get_contents('http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types'));
                if ($content != '') {
                    // cache
                    $f = @fopen(DATA_PATH.'cache/mime.types', 'wb');
                    if ($f) {
                        fwrite($f, $content);
                        fclose($f);
                    }
                }
            }
            if ($content != '') {
                foreach (explode("\n", $content) AS $x) {
                    if (isset($x[0]) && $x[0] !== '#' && preg_match_all('#([^\s]+)#', $x, $out) && isset($out[1]) && ($c = sizeof($out[1])) > 1) {
                        for ($i = 1; $i < $c; $i++) {
                            if (in_array($out[1][$i], $typies)) {
                                $s[$out[1][$i]] = $out[1][0];
                            }
                        }
                    }
                }
            }
        }
        return $s;
    }
    /**
     * ฟังก์ชั่น ตรวจสอบ mimetype ที่ต้องการ
     *
     * @param array $typies ชนิดของไฟล์ที่ยอมรับ เช่น jpg gif png
     * @param string $mime ชนิดของไฟล์ที่ต้องการตรวจสอบ เช่น image/png ซึ่งปกติจะได้จากการอัปโหลด เช่น $file[mime]
     * @return boolean  คืนค่า true ถ้าพบ $mime ใน $typies
     */
    public static function checkMIMEType($typies, $mime) {
        foreach ($typies AS $t) {
            if ($mime == $config['mimeTypes'][$t]) {
                return true;
            }
        }
        return false;
    }
    /**
     * ฟังก์ชั่น อ่าน mimetype ของไฟล์ สำหรับส่งให้ input ชนิด file
     *
     * @param array $typies ชนิดของไฟล์ เช่น jpg gif png
     * @return string คืนค่า mimetype ของไฟล์ คั่นแต่ละรายการด้วย , เช่น image/jpeg,image/png,image/gif
     */
    public static function getEccept($typies) {
        global $config;
        $accept = array();
        foreach ($typies AS $ext) {
            if (isset($config['mimeTypes'][$ext])) {
                $accept[] = $config['mimeTypes'][$ext];
            }
        }
        return implode(',', $accept);
    }
}