<?php
/*
 * projects/welcome/index.php.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @link http://gcms.in.th/
 *
 * @copyright 2015 Goragod.com
 * @license http://gcms.in.th/license/
 */
// ตัวแปรที่จำเป็นสำหรับ Framework ใช้ระบุ root folder
define('APP_PATH', dirname(__FILE__).'/');
// load GCMS
include APP_PATH.'../../core/load.php';
// inint GCMS Framework
\Core\Gcms::createWebApplication()->run();
