<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Header com includes padrões para a Admin do Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id: admin_header.php,v 1.3 2007/05/15 09:17:05 topet05 Exp $
### =============================================================
$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';

global $xoopsModule;

$thisModuleDir = $GLOBALS['xoopsModule']->getVar('dirname');

//if common.php file exist
include_once(XOOPS_ROOT_PATH . '/modules/mastop_publish/include/common.php');

if ( file_exists("../language/".$xoopsConfig['language']."/modinfo.php") ) {
	include_once("../language/".$xoopsConfig['language']."/modinfo.php");
} else {
	include_once("../language/portuguesebr/modinfo.php");
}
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_mpb_mpublish.class.php";
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_med_media.class.php";
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_fil_files.class.php";
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_cfi_contentfiles.class.php";
$c['lang']['showhidemenu'] = MPU_ADM_SHOWHIDEMENU;

// Load language files
xoops_loadLanguage('admin', $thisModuleDir);
xoops_loadLanguage('modinfo', $thisModuleDir);
xoops_loadLanguage('main', $thisModuleDir);

$pathIcon16      = '../' . $xoopsModule->getInfo('icons16');
$pathIcon32      = '../' . $xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

$myts = MyTextSanitizer::getInstance();

include_once $GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php');

xoops_loadLanguage('user');
if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
    include_once $GLOBALS['xoops']->path('/class/template.php');
    $GLOBALS['xoopsTpl'] = new XoopsTpl();
}

?>