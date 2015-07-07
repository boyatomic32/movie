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
 * Controller base class.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
abstract class Controller
{
    private static $instance;
    /**
     * Class constructor.
     */
    public function __construct() {
        self::$instance = &$this;
    }
    /**
     * ฟังก์ชั่นเริ่มต้นการทำงานของ Controller.
     *
     * @param mixed $param
     */
    abstract public function inint($param = null);
}