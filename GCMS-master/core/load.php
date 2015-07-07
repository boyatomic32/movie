<?php
/**
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @link http://gcms.in.th/
 *
 * @copyright 2015 Goragod.com
 * @license http://gcms.in.th/license/
 */
defined('APP_PATH') or exit('No direct script access allowed');
/**
 * เวลาเริ่มต้นในการประมวลผลเว็บไซต์
 */
define('BEGIN_TIME', microtime(true));
/**
 * ตัวแปรสำหรับ site
 */
$root_path = str_replace('/core/load.php', '', str_replace('\\', '/', __FILE__));
$document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
if ($document_root == '') {
    // Windows Server
    $basepath = end(explode('/', $root_path));
} elseif (preg_match('/'.str_replace('/', '\/', $document_root).'\/(.*)\/$/', APP_PATH, $match)) {
    $basepath = $match[1];
}
$baseurl = empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
/**
 * root ของ server เช่น D:/htdocs/gcms/
 */
define('ROOT_PATH', "$root_path/");
/**
 * root ของ document เช่น cms/
 */
define('BASE_PATH', (empty($basepath) ? '' : "$basepath/"));
/**
 * url ของ server รวม path (ไม่มี / ปิดท้าย) เช่น http://domain.tld/gcms
 */
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
    define('WEB_URL', 'https://'.$baseurl.(empty($basepath) ? '' : "/$basepath"));
} else {
    define('WEB_URL', 'http://'.$baseurl.(empty($basepath) ? '' : "/$basepath"));
}
mb_internal_encoding('utf-8');
date_default_timezone_set('Asia/Bangkok');
/**
 * เวอร์ชั่นของ PHP
 */
define('OLD_PHP', version_compare(PHP_VERSION, '5.3.0', '<'));
/**
 * auto load class.
 */
function _autoload($className) {
    $className = str_replace('\\', '/', strtolower($className));
    if (preg_match('/([a-z]+)\/([a-z]+)(\/([a-z_]+))?$/', $className, $match)) {
        if (is_file(APP_PATH.'modules/'.$match[2].'s/'.$match[1].$match[2].'.php')) {
            include APP_PATH.'modules/'.$match[2].'s/'.$match[1].$match[2].'.php';
        } elseif (isset($match[4]) && is_file(ROOT_PATH.$match[1].'/'.$match[2].'/'.$match[4].'.php')) {
            include ROOT_PATH.$match[1].'/'.$match[2].'/'.$match[4].'.php';
        } elseif (empty($match[4]) && is_file(ROOT_PATH.$match[1].'/'.$match[2].'.php')) {
            include ROOT_PATH.$match[1].'/'.$match[2].'.php';
        }
    }
}
spl_autoload_register('_autoload');
