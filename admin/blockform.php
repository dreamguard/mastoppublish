<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Formulário para a Clonagem de Blocos
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id: blockform.php,v 1.1 2007/06/16 22:41:07 kleber Exp $
### =============================================================
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$form = new XoopsThemeForm($block['form_title'], 'blockform', 'blocksadmin.php', "post", true);
if (isset($block['name'])) {
    $form->addElement(new XoopsFormLabel(_AM_NAME, $block['name']));
}
$side_select = new XoopsFormSelect(_AM_BLKTYPE, "bside", $block['side']);
$side_select->addOptionArray(array(0 => _AM_SBLEFT, 1 => _AM_SBRIGHT, 3 => _AM_CBLEFT, 4 => _AM_CBRIGHT, 5 => _AM_CBCENTER, 7 => _AM_CBBOTTOMLEFT, 8 => _AM_CBBOTTOMRIGHT, 9 => _AM_CBBOTTOM, ));
$form->addElement($side_select);
$form->addElement(new XoopsFormText(_AM_WEIGHT, "bweight", 2, 5, $block['weight']));
$form->addElement(new XoopsFormRadioYN(_AM_VISIBLE, 'bvisible', $block['visible']));
$mod_select = new XoopsFormSelect(_AM_VISIBLEIN, "bmodule", $block['modules'], 5, true);
$module_handler =& xoops_gethandler('module');
$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
$criteria->add(new Criteria('isactive', 1));
$module_list =& $module_handler->getList($criteria);
$module_list[-1] = _AM_TOPPAGE;
$module_list[0] = _AM_ALLPAGES;
ksort($module_list);
$mod_select->addOptionArray($module_list);
$form->addElement($mod_select);
$form->addElement(new XoopsFormText(_AM_TITLE, 'btitle', 50, 255, $block['title']), false);
if ( $block['is_custom'] ) {
    $textarea = new XoopsFormDhtmlTextArea(_AM_CONTENT, 'bcontent', $block['content'], 15, 70);
    $textarea->setDescription('<span style="font-size:x-small;font-weight:bold;">'._AM_USEFULTAGS.'</span><br /><span style="font-size:x-small;font-weight:normal;">'.sprintf(_AM_BLOCKTAG1, '{X_SITEURL}', XOOPS_URL.'/').'</span>');
    $form->addElement($textarea, true);
    $ctype_select = new XoopsFormSelect(_AM_CTYPE, 'bctype', $block['ctype']);
    $ctype_select->addOptionArray(array('H' => _AM_HTML, 'P' => _AM_PHP, 'S' => _AM_AFWSMILE, 'T' => _AM_AFNOSMILE));
    $form->addElement($ctype_select);
} else {
    if ($block['template'] != '') {
        $tplfile_handler =& xoops_gethandler('tplfile');
        $btemplate =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $block['bid']);
        if (count($btemplate) > 0) {
            $form->addElement(new XoopsFormLabel(_AM_CONTENT, '<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id='.$btemplate[0]->getVar('tpl_id').'">'._AM_EDITTPL.'</a>'));
        } else {
            $btemplate2 =& $tplfile_handler->find('default', 'block', $block['bid']);
            if (count($btemplate2) > 0) {
                $form->addElement(new XoopsFormLabel(_AM_CONTENT, '<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id='.$btemplate2[0]->getVar('tpl_id').'" target="_blank">'._AM_EDITTPL.'</a>'));
            }
        }
    }
    if ($block['edit_form'] != false) {
        $form->addElement(new XoopsFormLabel(_AM_OPTIONS, $block['edit_form']));
    }
}
$cache_select = new XoopsFormSelect(_AM_BCACHETIME, 'bcachetime', $block['cachetime']);
$cache_select->addOptionArray(array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
$form->addElement($cache_select);
if (isset($block['bid'])) {
    $form->addElement(new XoopsFormHidden('bid', $block['bid']));
}
$form->addElement(new XoopsFormHidden('op', $block['op']));
$form->addElement(new XoopsFormHidden('fct', 'blocksadmin'));
$button_tray = new XoopsFormElementTray('', '&nbsp;');
if ($block['is_custom']) {
    $button_tray->addElement(new XoopsFormButton('', 'previewblock', _PREVIEW, "submit"));
}
$button_tray->addElement(new XoopsFormButton('', 'submitblock', _SUBMIT, "submit"));
$form->addElement($button_tray);