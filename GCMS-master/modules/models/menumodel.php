<?php

namespace Menu;

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
 * คลาสสำหรับโหลดรายการเมนูจากฐานข้อมูลของ GCMS
 *
 * @author Goragod Wiriya <admin@goragod.com>
 */
class Model extends GcmsModel
{
    /**
     * @param mixed $param
     */
    public function inint($param = null) {
        $db = \Core\Gcms::Database();
        // โหลดเมนูทั้งหมดเรียงตามลำดับเมนู (รายการแรกคือหน้า Home)
        $sql = "SELECT M.`id` AS `module_id`,M.`module`,M.`owner`,M.`config`,U.`index_id`,U.`parent`,U.`level`,U.`menu_text`,U.`menu_tooltip`,U.`accesskey`,U.`menu_url`,U.`menu_target`,U.`alias`,U.`published`";
        $sql .= ",(CASE U.`parent` WHEN 'MAINMENU' THEN 0 WHEN 'BOTTOMMENU' THEN 1 WHEN 'SIDEMENU' THEN 2 ELSE 3 END ) AS `pos`";
        $sql .= " FROM `".$db->prefix.\Core\Gcms::get('database', 'menus')."` AS U";
        $sql .= " LEFT JOIN `".$db->prefix.\Core\Gcms::get('database', 'index')."` AS I ON I.`id`=U.`index_id` AND I.`index`='1' AND I.`language` IN ('".LANGUAGE."','')";
        $sql .= " LEFT JOIN `".$db->prefix.\Core\Gcms::get('database', 'modules')."` AS M ON M.`id`=I.`module_id`";
        $sql .= " WHERE U.`language` IN ('".LANGUAGE."','')";
        $sql .= " ORDER BY `pos` ASC,U.`parent` ASC ,U.`menu_order` ASC";
        return $db->customQuery($sql);
    }
}