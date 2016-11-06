<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Admin do Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id: index.php,v 1.6 2007/06/16 22:42:58 kleber Exp $
### =============================================================
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$indexAdmin = new ModuleAdmin();

echo $indexAdmin->addNavigation(basename(__FILE__));
echo $indexAdmin->renderIndex();


$op = (isset($_GET['op'])) ? $_GET['op'] : 'listar';
if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        $$k = $v;
    }
}

if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        $$k = $v;
    }
}
switch ($op){
    case "limpacont":
        $mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
        $mpu_classe = new mpu_mpb_mpublish($mpb_10_id);
        if (empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '') {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO2);
        }
        xoops_cp_header();
        xoops_confirm(array('op' => 'limpacont_ok', 'mpb_10_id' => $mpb_10_id), 'index.php', sprintf(MPU_ADM_CONFIRMA_LIMPACONT, $mpb_10_id, $mpu_classe->getVar("mpb_30_menu")));
        break;

    case "limpacont_ok":
        $mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
        $mpu_classe = new mpu_mpb_mpublish($mpb_10_id);
        if (empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '') {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO2);
        }
        $mpu_classe->setVar("mpb_10_contador", 0);
        $mpu_classe->setVar("mpb_22_atualizado", time());
        if (!$mpu_classe->store()) {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO1);
        }else{
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php?op=listar_editar&mpb_10_id=".$mpb_10_id, 3, MPU_ADM_SUCESS1);
        }
        break;

    default:


}

include_once __DIR__ . '/admin_footer.php';
?>
