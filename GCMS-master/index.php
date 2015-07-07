<?php
/**
 * index.php.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @link http://gcms.in.th/
 *
 * @copyright 2015 Goragod.com
 * @license http://gcms.in.th/license/
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
// ตัวแปรที่จำเป็นสำหรับ Framework ใช้ระบุ root folder
define('APP_PATH', dirname(__FILE__).'/');
// load GCMS
include APP_PATH.'core/load.php';
// inint GCMS Framework
\Core\Gcms::createWebApplication()->run();
