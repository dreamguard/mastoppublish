<?php

/**
 *
 * Module: SmartRental
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

global $xoopsConfig;

/**
 * Mastop Publish library path
 */
if (!defined('MPU_URL')) {
    define('MPU_URL', XOOPS_URL . '/modules/mastoppublish/');
}
if (!defined('MPU_ROOT_PATH')) {
    define('MPU_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/mastoppublish/');
}
if (!defined('MPU_IMAGES_URL')) {
    define('MPU_IMAGES_URL', MPU_URL . 'assets/images/');
}
if (!defined('MPU_IMAGES_ROOT_PATH')) {
    define('MPU_IMAGES_ROOT_PATH', MPU_ROOT_PATH . 'assets/images/');
}
if (!defined('MPU_IMAGES_ACTIONS_URL')) {
    define('MPU_IMAGES_ACTIONS_URL', MPU_URL . 'assets/images/actions/');
}
if (!defined('MPU_IMAGES_ACTIONS_ROOT_PATH')) {
    define('MPU_IMAGES_ACTIONS_ROOT_PATH', MPU_ROOT_PATH . 'assets/images/actions/');
}

/**
 * Version of the Mastop Publish Framework
 */
include_once(MPU_ROOT_PATH . 'include/version.php');
include_once(MPU_ROOT_PATH . 'include/funcoes.inc.php');
include_once(MPU_ROOT_PATH . 'include/xoops_core_common_functions.php');

/**
 * Some constants used by the Mastop Publishs
 */
//define('_SMART_GETVAR', 1);
//define('_SMART_OBJECT_METHOD', 2);

// get current page
//$mastop_current_page = mastop_getCurrentPage();

// get previous page
//$mastop_previous_page = mastop_getenv('HTTP_REFERER');

//include_once(MPU_ROOT_PATH . 'class/smartloader.php');
