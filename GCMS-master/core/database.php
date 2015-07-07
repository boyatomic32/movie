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
 * Database base class.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Database
{
    private static $instance;
    /**
     * Class constructor
     */
    public function __construct() {
        self::$instance = &$this;
    }
    /**
     * Class สำหรับเชื่อมต่อกับ database.
     *
     * @param type $param
     *
     * @return resource Database resource
     */
    public function connect($param) {
        // parse the URL from the DSN string
        if (($dns = @parse_url($param)) === false) {
            \Core\Gcms::debug('Invalid DB connection string.');
        }
        $params = array(
            'dbdriver' => strtolower($dns['scheme']),
            'hostname' => (isset($dns['host'])) ? rawurldecode($dns['host']) : '',
            'port' => isset($dns['port']) ? $dns['port'] : '',
            'username' => (isset($dns['user'])) ? rawurldecode($dns['user']) : '',
            'password' => (isset($dns['pass'])) ? rawurldecode($dns['pass']) : '',
            'dbname' => (isset($dns['path'])) ? rawurldecode(substr($dns['path'], 1)) : '',
        );
        if (isset($dsn['query'])) {
            parse_str($dsn['query'], $extra);
            foreach ($extra as $key => $val) {
                if (is_string($val) && in_array(strtoupper($val), array('TRUE', 'FALSE', 'NULL'))) {
                    $val = var_export($val, true);
                }
                $params[$key] = $val;
            }
        }
        // inint database class
        require_once ROOT_PATH.'core/database/driver.php';
        if (!in_array($params['dbdriver'], array('mysqli')) && defined('PDO::ATTR_DRIVER_NAME')) {
            require_once ROOT_PATH.'core/database/pdo_driver.php';
            // driver string
            $driver = 'PDO_driver';
        } elseif (is_file(ROOT_PATH.'core/database/'.$params['dbdriver'].'_driver.php')) {
            // โหลดจาก driver ที่กำหนด
            require_once ROOT_PATH.'core/database/'.$params['dbdriver'].'_driver.php';
            // driver string
            $driver = strtoupper($params['dbdriver']).'_driver';
        } else {
            if (!empty($driver)) {
                \Core\Gcms::debug('You have not selected a database type to connect to.');
            }
            $driver = 'Driver';
        }
        // inint class
        $db = new $driver($params);
        // return class
        return $db;
    }
}