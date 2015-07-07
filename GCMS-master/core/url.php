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
 * Class ศำหรับจัดการ URL ของ GCMS
 * สร้างลิงค์จากโมดูลสำหรับใช้บน GCMS
 * และใช้ในการแยกองค์ประกอบของ URL
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Url
{
    private $curr_url;
    private $curr_parse;
    /**
     * Class constructor
     *
     * @param string $url URL ที่ต้องการแยกองค์ประกอบ
     */
    public function __construct($url = '') {
        $this->curr_url = $url;
        $this->curr_parse = false;
    }
    /**
     * คืนค่า URL
     *
     * @return string URL
     */
    /**
     * ฟังก์ชั่นสร้าง URL จากโมดูล
     *
     * @param string $module URL ชื่อโมดูล
     * @param string $document (option)
     * @param int $catid (option) id ของหมวดหมู่ (default 0)
     * @param int $id (option) id ของข้อมูล (default 0)
     * @param string $query (option) query string อื่นๆ (default ค่าว่าง)
     * @param boolean $encode (option) true=เข้ารหัสด้วย rawurlencode ด้วย (default true)
     *
     * @return string URL ที่สร้าง
     */
    public static function createUrl($module, $document = '', $catid = 0, $id = 0, $query = '', $encode = true) {
        /**
         * กฏของ URL ใช้สำหรับการสร้างลิงค์
         */
        $config = array(
            0 => 'index.php?module={module}-{document}&amp;cat={catid}&amp;id={id}',
            1 => '{module}/{catid}/{id}/{document}.html'
        );
        $urls = \Core\Gcms::get('url', 'urls', $config);
        $patt = array();
        $replace = array();
        if (empty($document)) {
            $patt[] = '/[\/-]{document}/';
            $replace[] = '';
        } else {
            $patt[] = '/{document}/';
            $replace[] = $encode ? rawurlencode($document) : $document;
        }
        $patt[] = '/{module}/';
        $replace[] = $encode ? rawurlencode($module) : $module;
        if (empty($catid)) {
            $patt[] = '/((cat={catid}&amp;)|([\/-]{catid}))/';
            $replace[] = '';
        } else {
            $patt[] = '/{catid}/';
            $replace[] = (int)$catid;
        }
        if (empty($id)) {
            $patt[] = '/(((&amp;|\?)id={id})|([\/-]{id}))/';
            $replace[] = '';
        } else {
            $patt[] = '/{id}/';
            $replace[] = (int)$id;
        }
        $link = preg_replace($patt, $replace, $urls[\Core\Gcms::get('config', 'module_url', 0)]);
        if (!empty($query)) {
            $link = preg_match('/[\?]/u', $link) ? $link.'&amp;'.$query : $link.'?'.$query;
        }
        $obj = new static();
        $obj->curr_parse = false;
        $obj->curr_url = WEB_URL.'/'.$link;
        return $obj->curr_url;
    }
    /**
     * อ่านค่า ตัวแปรของ url ที่กำหนด
     *
     * @param string $name
     *
     * @return string ค่าที่อ่านได้ ถ้าไม่พบคืนค่าว่าง
     */
    public function get($name) {
        $result = $this->parse_url();
        return $result === false || empty($result[$name]) ? '' : $result[$name];
    }
    /**
     * อ่านค่า ตัวแปรของ query string ที่กำหนด
     *
     * @param string $name query string
     *
     * @return string ค่าที่อ่านได้ ถ้าไม่พบคืนค่าว่าง
     */
    public function query($name) {
        $result = $this->parse_url();
        return $result === false || empty($result['query'][$name]) ? '' : $result['query'][$name];
    }
    /**
     * parse url
     *
     * @return array|boolean คืนค่า array ของค่าที่ parse ได้ทั้งหมด ไม่สำเร็จคืนค่า false
     */
    public function parse_url() {
        if ($this->curr_parse === false) {
            $this->curr_parse = parse_url($this->url);
            $query_str = str_replace('&amp;', '&', $this->curr_parse['query']);
            $this->curr_parse['query'] = array();
            if ($query_str != '') {
                foreach (explode('&', $query_str) AS $item) {
                    if (preg_match('/(.*)=(.*)/', $item, $match)) {
                        $this->curr_parse['query'][$match[1]] = $match[2];
                    } else {
                        $this->curr_parse['query'][$item] = '';
                    }
                }
            }
        }
        return $this->curr_parse;
    }
}