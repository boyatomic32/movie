<?php

namespace Site;

use Core\Controller as GcmsController;
use Core\Gcms;

/*
 * SiteController.php.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @link http://gcms.in.th/
 *
 * @copyright 2015 Goragod.com
 * @license http://gcms.in.th/license/
 */
defined('ROOT_PATH') or exit('No direct script access allowed');
/*
 * Description of SiteController.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 */
class Controller extends GcmsController
{
    public function inint($modules = null) {
        echo 'Welcome to GCMS++ PHP Framework';
    }
}