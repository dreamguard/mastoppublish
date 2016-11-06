<?php
/**
 * Id: menu.php 2341 2008-05-21 16:34:21Z malanciault
 * Licence: GNU
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(__DIR__));
$moduleHandler   = xoops_getHandler('module');
$module          = $moduleHandler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu              = array();

$i                      = 0;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';

++$i;
$adminmenu[$i]['title'] = MPU_MOD_MENU_ADD;
//$adminmenu[$i]['link']  = 'admin/index.php?op=novo';
$adminmenu[$i]['link']  = 'admin/content.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/addlink.png';

++$i;
$adminmenu[$i]['title'] = MPU_MOD_MENU_LNK;
$adminmenu[$i]['link']  = 'admin/paginas.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/identity.png';

++$i;
$adminmenu[$i]['title'] = MPU_MOD_MENU_MED;
$adminmenu[$i]['link']  = 'admin/media.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/alert.png';
++$i;
$adminmenu[$i]['title'] = MPU_MOD_MENU_FIL;
$adminmenu[$i]['link']  = 'admin/files.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/stats.png';

++$i;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';


