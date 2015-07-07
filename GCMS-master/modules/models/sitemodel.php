<?php

namespace Site;

use Core\Model as GcmsModel;

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
 * คลาสสำหรับเชื่อมต่อกับฐานข้อมูลของ GCMS
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends GcmsModel
{
    /**
     * อ่านโมดูลทั้งหมดที่ติดตั้ง
     *
     * @param mixed $param
     */
    public function inint($param = null) {
        $db = \Core\Gcms::Database();
        $sql = "SELECT `id` AS `module_id`,`module`,`owner`,`config` FROM `".$db->prefix.\Core\Gcms::get('database', 'modules')."`";
        return $db->customQuery($sql);
    }
    /**
     * อ่านโมดูลที่ต้องการ
     *
     * @param string $module
     */
    public function getModule($module) {
        $db = \Core\Gcms::Database();
        // วันนี้
        $date = \Core\Date::mktime_to_sql_date();
        // อ่านโมดูล ตามภาษา
        $sql = "SELECT M.`module`,I.`id`,D.`topic`,D.`description`,D.`keywords`,D.`detail`,I.`visited`";
        if (is_int($module)) {
            $sql .= " FROM `".$db->prefix.\Core\Gcms::get('database', 'index')."` AS I";
            $sql .= " INNER JOIN `".$db->prefix.\Core\Gcms::get('database', 'modules')."` AS M ON M.`id`=I.`module_id`";
            $sql .= " INNER JOIN `".$db->prefix.\Core\Gcms::get('database', 'index_detail')."` AS D ON D.`id`=I.`id` AND D.`module_id`=I.`module_id` AND D.`language`=I.`language`";
            $sql .= " WHERE I.`id`=".(int)$module." AND I.`index`='1' AND I.`published`='1' AND I.`published_date`<='$date' LIMIT 1";
        } else {
            $sql .= " FROM `".$db->prefix.\Core\Gcms::get('database', 'index_detail')."` AS D ";
            $sql .= " INNER JOIN `".$db->prefix.\Core\Gcms::get('database', 'index')."` AS I ON I.`id`=D.`id` AND I.`index`='1' AND I.`published`='1' AND I.`published_date`<='$date' AND I.`language`=D.`language`";
            $sql .= " INNER JOIN `".$db->prefix.\Core\Gcms::get('database', 'modules')."` AS M ON M.`id`=D.`module_id` AND M.`module`='$module'";
            $sql .= " WHERE D.`language` IN ('".LANGUAGE."','') LIMIT 1";
        }
        $search = $db->customQuery($sql);
        return sizeof($search) == 1 ? $search[0] : false;
    }
}