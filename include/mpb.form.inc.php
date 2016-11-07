<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Formulário de Conteúdo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id: mpb.form.inc.php,v 1.7 2007/06/16 22:47:46 kleber Exp $
### =============================================================
if (!defined('XOOPS_ROOT_PATH')) {
    die("Ooops!");
}
function mastoppublish_getContentForm(MastoppublishContent $obj, $action = false)
{
    global $xoopsUser;
    
    if ($action === false) {
        $action = $_SERVER['REQUEST_URI'];
    }
    
    $mpb_10_id = $obj->getVar("mpb_10_id");
    $title = $obj->isNew() ? sprintf(_PROFILE_AM_ADD, _PROFILE_AM_FIELD) : sprintf(_PROFILE_AM_EDIT, _PROFILE_AM_FIELD);
    
    //echo "<pre>".print_r($GLOBALS['xoopsModule']->getVar('mid'),1)."</pre>"; die();

    include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    
    //$form = new XoopsThemeForm($form['titulo'], "mpu_mpb_form", $_SERVER['PHP_SELF'], "post");
    $form = new XoopsThemeForm($title, 'mpu_mpb_form', $action, 'post', true);
    

    if($mpb_10_id > 0){
        $mpb_infos_tray = new XoopsFormElementTray(MPU_ADM_INFO, "<br />");
        $mpb_infos_tray->addElement(new XoopsFormLabel(MPU_ADM_BY, XoopsUser::getUnameFromId($obj->getVar('usr_10_uid'))));
        $mpb_infos_tray->addElement(new XoopsFormLabel(MPU_ADM_DTCRIADO, date(_DATESTRING, $obj->getVar('mpb_22_criado'))));
        $mpb_infos_tray->addElement(new XoopsFormLabel(MPU_ADM_DTATUALIZADO, date(_DATESTRING, $obj->getVar('mpb_22_atualizado'))));
        $mpb_infos_tray->addElement(new XoopsFormLabel(MPU_ADM_VIEWS, $obj->getVar('mpb_10_contador')));
        $mpb_botao_limpacont = new XoopsFormButton("", "limpacont", MPU_ADM_LIMPACONT);
        $mpb_botao_limpacont->setExtra("onclick=\"document.location= '".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php?op=limpacont&mpb_10_id=".$mpb_10_id."'\"");
        $mpb_infos_tray->addElement($mpb_botao_limpacont);
        $form->addElement($mpb_infos_tray);

    }

    $form->addElement(new XoopsFormSelectUser(MPU_ADM_USR_10_UID, "usr_10_uid", false, (($obj->getVar("usr_10_uid") != "") ? $obj->getVar("usr_10_uid") : $xoopsUser->getVar('uid'))));

    $mpb_exibir_tray = new XoopsFormElementTray(MPU_ADM_MPB_10_IDPAI, "&nbsp;&nbsp;&nbsp;");
    $exibir_select = new XoopsFormSelect("", "mpb_10_idpai", $obj->getVar("mpb_10_idpai"));
    $exibir_select->addOptionArray($obj->geraMenuSelect());
    $mpb_exibir_tray->addElement($exibir_select);
    $mpb_exibir_tray->addElement(new XoopsFormText(MPU_ADM_MPB_10_ORDEM, "mpb_10_ordem", 5, 6, $obj->getVar("mpb_10_ordem")));
    $form->addElement($mpb_exibir_tray);
    $form->addElement(new XoopsFormText(MPU_ADM_MPB_30_MENU, "mpb_30_menu", 50, 50, $obj->getVar("mpb_30_menu")), true);
    $mpb_tray_titulo_semlink = new XoopsFormElementTray(MPU_ADM_MPB_30_TITULO);
    $mpb_titulo = new XoopsFormText("", "mpb_30_titulo", 50,100, $obj->getVar("mpb_30_titulo"));
    $mpb_tray_titulo_semlink->addElement($mpb_titulo);
    $mpb_semlink = new XoopsFormCheckBox("", "mpb_12_semlink", $obj->getVar("mpb_12_semlink"));
    $mpb_semlink->setExtra("id='mpb_12_semlink' onclick='if(this.checked){ document.getElementById(\"mpb_external\").checked=false; document.getElementById(\"mpb_pagina\").checked=false; document.getElementById(\"mpb_frame\").checked=false; document.getElementById(\"mpb_external_span\").style.display=\"none\"; document.getElementById(\"mpb_35_conteudo_span\").style.display=\"none\"; document.getElementById(\"mpb_pagina_span\").style.display=\"none\"; document.getElementById(\"mpb_30_arquivo_span\").style.display=\"none\"; }else{ document.getElementById(\"mpb_35_conteudo_span\").style.display=\"\";".(($xoopsModuleConfig['mpu_conf_wysiwyg']) ? "tinyMCE.execCommand(\"mceResetDesignMode\");" : "" )."}'");
    $mpb_semlink->addOption(1, MPU_ADM_MPB_12_SEMLINK);
    $mpb_tray_titulo_semlink->addElement($mpb_semlink);

    $mpb_external_check = new XoopsFormCheckBox("", "mpb_external", ((substr($obj->getVar("mpb_30_arquivo"), 0, 4) == 'ext:') ? 1 : 0));
    $mpb_external_check->setExtra("id='mpb_external' onclick='if(this.checked) { document.getElementById(\"mpb_pagina\").checked=false; document.getElementById(\"mpb_12_semlink\").checked=false; document.getElementById(\"mpb_frame\").checked=false; document.getElementById(\"mpb_external_span\").style.display=\"\"; document.getElementById(\"mpb_30_arquivo_span\").style.display=\"none\"; document.getElementById(\"mpb_pagina_span\").style.display=\"none\"; document.getElementById(\"mpb_35_conteudo_span\").style.display=\"none\";}else{document.getElementById(\"mpb_external_span\").style.display=\"none\";document.getElementById(\"mpb_35_conteudo_span\").style.display=\"\";".(($xoopsModuleConfig['mpu_conf_wysiwyg']) ? "tinyMCE.execCommand(\"mceResetDesignMode\");" : "" )."}'");
    $mpb_external_check->addOption(1, MPU_ADM_MPB_EXTERNAL);
    $mpb_tray_titulo_semlink->addElement($mpb_external_check);

    $mpb_frame_check = new XoopsFormCheckBox("", "mpb_frame", ((substr($obj->getVar("mpb_30_arquivo"), 0, 4) == 'http') ? 1 : 0));
    $mpb_frame_check->setExtra("id='mpb_frame' onclick='if(this.checked) { document.getElementById(\"mpb_external\").checked=false; document.getElementById(\"mpb_pagina\").checked=false; document.getElementById(\"mpb_12_semlink\").checked=false; document.getElementById(\"mpb_external_span\").style.display=\"none\"; document.getElementById(\"mpb_30_arquivo_span\").style.display=\"\"; document.getElementById(\"mpb_pagina_span\").style.display=\"none\"; document.getElementById(\"mpb_35_conteudo_span\").style.display=\"none\";}else{document.getElementById(\"mpb_30_arquivo_span\").style.display=\"none\";document.getElementById(\"mpb_35_conteudo_span\").style.display=\"\";".(($xoopsModuleConfig['mpu_conf_wysiwyg']) ? "tinyMCE.execCommand(\"mceResetDesignMode\");" : "" )."}'");
    $mpb_frame_check->addOption(1, MPU_ADM_MPB_FRAME);
    $mpb_tray_titulo_semlink->addElement($mpb_frame_check);
    $mpb_pagina = new XoopsFormCheckBox("", "mpb_pagina", (($obj->getVar("mpb_30_arquivo") != "" && substr($obj->getVar("mpb_30_arquivo"), 0, 4) != 'http' && substr($obj->getVar("mpb_30_arquivo"), 0, 4) != 'ext:') ? 1 : 0));
    $mpb_pagina->setExtra("id='mpb_pagina' onclick='if(this.checked){ document.getElementById(\"mpb_external\").checked=false; document.getElementById(\"mpb_frame\").checked=false; document.getElementById(\"mpb_12_semlink\").checked=false; document.getElementById(\"mpb_external_span\").style.display=\"none\"; document.getElementById(\"mpb_35_conteudo_span\").style.display=\"none\";document.getElementById(\"mpb_30_arquivo_span\").style.display=\"none\";document.getElementById(\"mpb_pagina_span\").style.display=\"\";}else{ document.getElementById(\"mpb_35_conteudo_span\").style.display=\"\";document.getElementById(\"mpb_pagina_span\").style.display=\"none\";".(($xoopsModuleConfig['mpu_conf_wysiwyg']) ? "tinyMCE.execCommand(\"mceResetDesignMode\");" : "" )."}'");
    $mpb_pagina->addOption(1, MPU_ADM_MPB_FROMFILE);
    $mpb_tray_titulo_semlink->addElement($mpb_pagina);

    $form->addElement($mpb_tray_titulo_semlink);
    $mpb_tray_conteudo = new XoopsFormElementTray(MPU_ADM_MPB_35_CONTEUDO, "");
    $mpb_tray_conteudo->addElement(new XoopsFormLabel("", "<span id='mpb_35_conteudo_span' ".(($obj->getVar("mpb_30_arquivo") != '' || $obj->getVar("mpb_12_semlink") == 1) ? 'style="display:none"' : '').">"));

    include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/tinymce/formtinymce.php");
    $textarea = new XoopsFormTinymce(array('caption'=>'', 'name'=>'mpb_35_conteudo', 'value'=>$obj->getVar('mpb_35_conteudo', 'n'), 'width'=>'100%', 'height'=>'400px'));
    $mpb_tray_conteudo->addElement($textarea, false);

    $mpb_tray_conteudo->addElement(new XoopsFormLabel("", "</span><span id='mpb_30_arquivo_span' ".((substr($obj->getVar("mpb_30_arquivo"), 0, 4) != 'http') ? 'style="display:none"' : '').">"));
    $mpb_tray_conteudo->addElement(new XoopsFormText(MPU_ADM_MPB_FRAME_URL, 'mpb_30_arquivo_frame', 30, 255, ((substr($obj->getVar("mpb_30_arquivo"), 0, 4) == 'http') ? $obj->getVar("mpb_30_arquivo") : '')));
    $mpb_tray_conteudo->addElement(new XoopsFormLabel("", "</span>"));

    $mpb_tray_conteudo->addElement(new XoopsFormLabel("", "</span><span id='mpb_external_span' ".((substr($obj->getVar("mpb_30_arquivo"), 0, 4) != 'ext:') ? 'style="display:none"' : '').">"));
    $mpb_tray_conteudo->addElement(new XoopsFormText(MPU_ADM_MPB_EXTERNAL_URL, 'mpb_30_arquivo_external', 30, 255, ((substr($obj->getVar("mpb_30_arquivo"), 0, 4) == 'ext:') ? substr($obj->getVar("mpb_30_arquivo"), 4) : 'http://')));
    $mpb_tray_conteudo->addElement(new XoopsFormLabel("", "</span>"));

    $mpb_tray_conteudo->addElement(new XoopsFormLabel("", "<span id='mpb_pagina_span' ".(($obj->getVar("mpb_30_arquivo") == "" || substr($obj->getVar("mpb_30_arquivo"), 0, 4) == 'http' || substr($obj->getVar("mpb_30_arquivo"), 0, 4) == 'ext:') ? 'style="display:none"' : '').">"));
    $paginas_select = new XoopsFormSelect(MPU_ADM_SELECIONE, "pagina", $obj->getVar("mpb_30_arquivo"));
    //$paginas_select->addOptionArray($cfi_classe->listaPaginas());
    //$mpb_tray_conteudo->addElement($paginas_select);
    $mpb_tray_conteudo->addElement(new XoopsFormLabel("", "<a href='".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php?op=contentfiles_adicionar'>"._ADD."</a></span>"));
    $form->addElement($mpb_tray_conteudo);
    
    // Groups tray
    $groupperm_handler = xoops_getHandler('groupperm');
    $grupos_ids = array();
    $grupos_ids = ($mpb_10_id > 0) ? $groupperm_handler->getGroupIds("grupos_perm", $mpb_10_id, $GLOBALS['xoopsModule']->getVar('mid')) : $xoopsUser->getGroups();;
    /*if (!in_array(XOOPS_GROUP_ANONYMOUS, $grupos_ids) && $mpb_10_id == 0) {
        array_push($grupos_ids, XOOPS_GROUP_ANONYMOUS);
    }*/
    $form->addElement(new XoopsFormSelectGroup(MPU_ADM_GRUPOS, "grupos_perm", true, $grupos_ids, 5, true));

    //Options tray
    $mpb_opcoes_tray = new XoopsFormElementTray(_OPTIONS,'<br />');
    $mpb_visivel_select = new XoopsFormSelect(MPU_ADM_MPB_11_VISIVEL, "mpb_11_visivel", $obj->getVar("mpb_11_visivel"));
    $mpb_visivel_select->addOptionArray(array(1=>MPU_ADM_MPB_11_VISIVEL_1, 3=>MPU_ADM_MPB_11_VISIVEL_3, 2=>MPU_ADM_MPB_11_VISIVEL_2, 4=>MPU_ADM_MPB_11_VISIVEL_4));
    $mpb_opcoes_tray->addElement($mpb_visivel_select);
    $mpb_abrir_select = new XoopsFormSelect(MPU_ADM_MPB_11_ABRIR, "mpb_11_abrir", $obj->getVar("mpb_11_abrir"));
    $mpb_abrir_select->addOptionArray(array(0=>MPU_ADM_MPB_11_ABRIR_0, 1=>MPU_ADM_MPB_11_ABRIR_1));
    $mpb_opcoes_tray->addElement($mpb_abrir_select);
    $mpb_comentarios = new XoopsFormCheckBox("", "mpb_12_comentarios", $obj->getVar("mpb_12_comentarios"));
    $mpb_comentarios->addOption(1,MPU_ADM_MPB_12_COMENTARIOS);
    $mpb_opcoes_tray->addElement($mpb_comentarios);
    $mpb_exibesub = new XoopsFormCheckBox("", "mpb_12_exibesub", $obj->getVar("mpb_12_exibesub"));
    $mpb_exibesub->addOption(1,MPU_ADM_MPB_12_EXIBESUB);
    $mpb_opcoes_tray->addElement($mpb_exibesub);
    $mpb_recomendar = new XoopsFormCheckBox("", "mpb_12_recomendar", $obj->getVar("mpb_12_recomendar"));
    $mpb_recomendar->addOption(1,MPU_ADM_MPB_12_RECOMENDAR);
    $mpb_opcoes_tray->addElement($mpb_recomendar);
    $mpb_imprimir = new XoopsFormCheckBox("", "mpb_12_imprimir", $obj->getVar("mpb_12_imprimir"));
    $mpb_imprimir->addOption(1,MPU_ADM_MPB_12_IMPRIMIR);
    $mpb_opcoes_tray->addElement($mpb_imprimir);

    $form->addElement($mpb_opcoes_tray);

    
    $button_tray = new XoopsFormElementTray('', '');

    if (!$obj->isNew()) {
        $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit')); //orclone
    } else {
        $button_tray->addElement(new XoopsFormButton('', 'submit', _CO_PUBLISHER_CREATE, 'submit'));
        $button_tray->addElement(new XoopsFormButton('', '', _CO_PUBLISHER_CLEAR, 'reset'));
    }

    $butt_cancel = new XoopsFormButton('', '', _CO_PUBLISHER_CANCEL, 'button');
    $butt_cancel->setExtra("onclick=\"document.location= 'content.php'\"");
    $button_tray->addElement($butt_cancel);

    $form->addElement($button_tray);
    
    $form->addElement(new XoopsFormHidden('mpb_10_id', $mpb_10_id));
    $form->addElement(new XoopsFormHidden('op', 'salvar'));
    
    return $form;
}

?>