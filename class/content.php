<?php
/**
 * Extended User Profile
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       (c) 2000-2016 XOOPS Project (www.xoops.org)
 * @license             GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package             profile
 * @since               2.3.0
 * @author              Jan Pedersen
 * @author              Taiwen Jiang <phppp@users.sourceforge.net>
 */

// defined('XOOPS_ROOT_PATH') || exit("XOOPS root path not defined");

/**
 * @package             kernel
 * @copyright       (c) 2000-2016 XOOPS Project (www.xoops.org)
 */
class MastoppublishContent extends XoopsObject
{
    /**
     *
     */
    public function __construct()
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->tabela = $this->db->prefix(MPU_MOD_TABELA1);
        //$this->id = "mpb_10_id";
        $this->initVar('id', XOBJ_DTYPE_INT, null);
        $this->initVar("mpb_10_id", XOBJ_DTYPE_INT, null, false);
        $this->initVar("mpb_10_idpai", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("usr_10_uid", XOBJ_DTYPE_INT, null, false);
        $this->initVar("mpb_30_menu", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("mpb_30_titulo", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("mpb_35_conteudo", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("mpb_12_semlink", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_30_arquivo", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("mpb_11_visivel", XOBJ_DTYPE_INT, 2, false);
        $this->initVar("mpb_11_abrir", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_12_comentarios", XOBJ_DTYPE_INT, null, false);
        $this->initVar("mpb_12_exibesub", XOBJ_DTYPE_INT, 1, false);
        $this->initVar("mpb_12_recomendar", XOBJ_DTYPE_INT, 1, false);
        $this->initVar("mpb_12_imprimir", XOBJ_DTYPE_INT, 1, false);
        $this->initVar("mpb_22_criado", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_22_atualizado", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_10_ordem", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_10_contador", XOBJ_DTYPE_INT, 0, false);
        /*if ( !empty($id) ) {
            if ( is_array($id) ) {
                $this->assignVars($id);
            }elseif (is_numeric($id)){
                $this->load(intval($id));
            }else{
                $this->loadByMenu($id);
            }
        } */       
    }

    /**
     * Extra treatment dealing with non latin encoding
     * Tricky solution
     * @param string $key
     * @param mixed  $value
     * @param bool   $not_gpc
     */
    public function setVar($key, $value, $not_gpc = false)
    {
        if ($key === 'field_options' && is_array($value)) {
            foreach (array_keys($value) as $idx) {
                $value[$idx] = base64_encode($value[$idx]);
            }
        }
        parent::setVar($key, $value, $not_gpc);
    }

    /**
     * @param string $key
     * @param string $format
     *
     * @return mixed
     */
    public function getVar($key, $format = 's')
    {
        $value = parent::getVar($key, $format);
        if ($key === 'field_options' && !empty($value)) {
            foreach (array_keys($value) as $idx) {
                $value[$idx] = base64_decode($value[$idx]);
            }
        }

        return $value;
    }

    /**
     * Returns a {@link XoopsFormElement} for editing the value of this field
     *
     * @param XoopsUser      $user    {@link XoopsUser} object to edit the value of
     * @param ProfileProfile $profile {@link ProfileProfile} object to edit the value of
     *
     * @return XoopsFormElement
     **/
    public function getEditElement($user, $profile)
    {
        $value = in_array($this->getVar('field_name'), $this->getUserVars()) ? $user->getVar($this->getVar('field_name'), 'e') : $profile->getVar($this->getVar('field_name'), 'e');

        $caption = $this->getVar('field_title');
        $caption = defined($caption) ? constant($caption) : $caption;
        $name    = $this->getVar('field_name', 'e');
        $options = $this->getVar('field_options');
        if (is_array($options)) {
            //asort($options);

            foreach (array_keys($options) as $key) {
                $optval = defined($options[$key]) ? constant($options[$key]) : $options[$key];
                $optkey = defined($key) ? constant($key) : $key;
                unset($options[$key]);
                $options[$optkey] = $optval;
            }
        }
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        switch ($this->getVar('field_type')) {
            default:
            case 'autotext':
                //autotext is not for editing
                $element = new XoopsFormLabel($caption, $this->getOutputValue($user, $profile));
                break;

            case 'textbox':
                $element = new XoopsFormText($caption, $name, 35, $this->getVar('field_maxlength'), $value);
                break;

            case 'textarea':
                $element = new XoopsFormTextArea($caption, $name, $value, 4, 30);
                break;

            case 'dhtml':
                $element = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 30);
                break;

            case 'select':
                $element = new XoopsFormSelect($caption, $name, $value);
                // If options do not include an empty element, then add a blank option to prevent any default selection
//                if (!in_array('', array_keys($options))) {
                if (!array_key_exists('', $options)) {
                    $element->addOption('', _NONE);

                    $eltmsg                          = empty($caption) ? sprintf(_FORM_ENTER, $name) : sprintf(_FORM_ENTER, $caption);
                    $eltmsg                          = str_replace('"', '\"', stripslashes($eltmsg));
                    $element->customValidationCode[] = "\nvar hasSelected = false; var selectBox = myform.{$name};" . "for (i = 0; i < selectBox.options.length; i++) { if (selectBox.options[i].selected == true && selectBox.options[i].value != '') { hasSelected = true; break; } }" . "if (!hasSelected) { window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
                }
                $element->addOptionArray($options);
                break;

            case 'select_multi':
                $element = new XoopsFormSelect($caption, $name, $value, 5, true);
                $element->addOptionArray($options);
                break;

            case 'radio':
                $element = new XoopsFormRadio($caption, $name, $value);
                $element->addOptionArray($options);
                break;

            case 'checkbox':
                $element = new XoopsFormCheckBox($caption, $name, $value);
                $element->addOptionArray($options);
                break;

            case 'yesno':
                $element = new XoopsFormRadioYN($caption, $name, $value);
                break;

            case 'group':
                $element = new XoopsFormSelectGroup($caption, $name, true, $value);
                break;

            case 'group_multi':
                $element = new XoopsFormSelectGroup($caption, $name, true, $value, 5, true);
                break;

            case 'language':
                $element = new XoopsFormSelectLang($caption, $name, $value);
                break;

            case 'date':
                $element = new XoopsFormTextDateSelect($caption, $name, 15, $value);
                break;

            case 'longdate':
                $element = new XoopsFormTextDateSelect($caption, $name, 15, str_replace('-', '/', $value));
                break;

            case 'datetime':
                $element = new XoopsFormDatetime($caption, $name, 15, $value);
                break;

            case 'list':
                $element = new XoopsFormSelectList($caption, $name, $value, 1, $options[0]);
                break;

            case 'timezone':
                $element = new XoopsFormSelectTimezone($caption, $name, $value);
                $element->setExtra("style='width: 280px;'");
                break;

            case 'rank':
                $element = new XoopsFormSelect($caption, $name, $value);

                include_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $ranks = XoopsLists::getUserRankList();
                $element->addOption(0, '--------------');
                $element->addOptionArray($ranks);
                break;

            case 'theme':
                $element = new XoopsFormSelect($caption, $name, $value);
                $element->addOption('0', _PROFILE_MA_SITEDEFAULT);
                $handle  = opendir(XOOPS_THEME_PATH . '/');
                $dirlist = array();
                while (false !== ($file = readdir($handle))) {
                    if (is_dir(XOOPS_THEME_PATH . '/' . $file) && !preg_match("/^[.]{1,2}$/", $file) && strtolower($file) !== 'cvs') {
                        if (file_exists(XOOPS_THEME_PATH . '/' . $file . '/theme.html') && in_array($file, $GLOBALS['xoopsConfig']['theme_set_allowed'])) {
                            $dirlist[$file] = $file;
                        }
                    }
                }
                closedir($handle);
                if (!empty($dirlist)) {
                    asort($dirlist);
                    $element->addOptionArray($dirlist);
                }
                break;
        }
        if ($this->getVar('field_description') != '') {
            $element->setDescription($this->getVar('field_description'));
        }

        return $element;
    }

    /**
     * Returns a value for output of this field
     *
     * @param XoopsUser      $user    {@link XoopsUser} object to get the value of
     * @param profileProfile $profile object to get the value of
     *
     * @return mixed
     **/
    public function getOutputValue(&$user, $profile)
    {
        if (file_exists($file = $GLOBALS['xoops']->path('modules/profile/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php'))) {
            include_once $file;
        } else {
            include_once $GLOBALS['xoops']->path('modules/profile/language/english/modinfo.php');
        }

        $value = in_array($this->getVar('field_name'), $this->getUserVars()) ? $user->getVar($this->getVar('field_name')) : $profile->getVar($this->getVar('field_name'));

        switch ($this->getVar('field_type')) {
            default:
            case 'textbox':
                if ($this->getVar('field_name') === 'url' && $value !== '') {
                    return '<a href="' . formatURL($value) . '" rel="external">' . $value . '</a>';
                } else {
                    return $value;
                }
                break;
            case 'textarea':
            case 'dhtml':
            case 'theme':
            case 'language':
            case 'list':
                return $value;
                break;

            case 'select':
            case 'radio':
                $options = $this->getVar('field_options');
                if (isset($options[$value])) {
                    $value = htmlspecialchars(defined($options[$value]) ? constant($options[$value]) : $options[$value]);
                } else {
                    $value = '';
                }

                return $value;
                break;

            case 'select_multi':
            case 'checkbox':
                $options = $this->getVar('field_options');
                $ret     = array();
                if (count($options) > 0) {
                    foreach (array_keys($options) as $key) {
                        if (in_array($key, $value)) {
                            $ret[$key] = htmlspecialchars(defined($options[$key]) ? constant($options[$key]) : $options[$key]);
                        }
                    }
                }

                return $ret;
                break;

            case 'group':
                $member_handler = xoops_getHandler('member');
                $options        = $member_handler->getGroupList();
                $ret            = isset($options[$value]) ? $options[$value] : '';

                return $ret;
                break;

            case 'group_multi':
                $member_handler = xoops_getHandler('member');
                $options        = $member_handler->getGroupList();
                $ret            = array();
                foreach (array_keys($options) as $key) {
                    if (in_array($key, $value)) {
                        $ret[$key] = htmlspecialchars($options[$key]);
                    }
                }

                return $ret;
                break;

            case 'longdate':
                //return YYYY/MM/DD format - not optimal as it is not using local date format, but how do we do that
                //when we cannot convert it to a UNIX timestamp?
                return str_replace('-', '/', $value);

            case 'date':
                return formatTimestamp($value, 's');
                break;

            case 'datetime':
                if (!empty($value)) {
                    return formatTimestamp($value, 'm');
                } else {
                    return $value = _PROFILE_MI_NEVER_LOGGED_IN;
                }
                break;

            case 'autotext':
                $value = $user->getVar($this->getVar('field_name'), 'n'); //autotext can have HTML in it
                $value = str_replace('{X_UID}', $user->getVar('uid'), $value);
                $value = str_replace('{X_URL}', XOOPS_URL, $value);
                $value = str_replace('{X_UNAME}', $user->getVar('uname'), $value);

                return $value;
                break;

            case 'rank':
                $userrank       = $user->rank();
                $user_rankimage = '';
                if (isset($userrank['image']) && $userrank['image'] !== '') {
                    $user_rankimage = '<img src="' . XOOPS_UPLOAD_URL . '/' . $userrank['image'] . '" alt="' . $userrank['title'] . '" /><br />';
                }

                return $user_rankimage . $userrank['title'];
                break;

            case 'yesno':
                return $value ? _YES : _NO;
                break;

            case 'timezone':
                include_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $timezones = XoopsLists::getTimeZoneList();
                $value     = empty($value) ? '0' : (string)$value;

                return $timezones[str_replace('.0', '', $value)];
                break;
        }
    }

    /**
     * Returns a value ready to be saved in the database
     *
     * @param mixed $value Value to format
     *
     * @return mixed
     */
    public function getValueForSave($value)
    {
        switch ($this->getVar('field_type')) {
            default:
            case 'textbox':
            case 'textarea':
            case 'dhtml':
            case 'yesno':
            case 'timezone':
            case 'theme':
            case 'language':
            case 'list':
            case 'select':
            case 'radio':
            case 'select_multi':
            case 'group':
            case 'group_multi':
            case 'longdate':
                return $value;

            case 'checkbox':
                return (array)$value;

            case 'date':
                if ($value !== '') {
                    return strtotime($value);
                }

                return $value;
                break;

            case 'datetime':
                if (!empty($value)) {
                    return strtotime($value['date']) + (int)$value['time'];
                }

                return $value;
                break;
        }
    }

    /**
     * Get names of user variables
     *
     * @return array
     */
    public function getUserVars()
    {
        $profile_handler = xoops_getModuleHandler('profile', 'profile');

        return $profile_handler->getUserVars();
    }
    
    
    /**
     * Funciones originales de Mastop Publisher
     * Copiadas de mpu_mpb_mpublish.class.php
     */
    
    public function geraMenuSelect($mpb_10_idpai=0, $modules = true) {
        global $xoopsUser;
        $retorna[0] = MPU_ADM_MENUP;
        if ($mpb_10_idpai == 0 && $modules == true){
            $module_handler = xoops_gethandler('module');
            $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
            $criteria->add(new Criteria('isactive', 1));
            $criteria->add(new Criteria('weight', 0, '>'));
            $modules = $module_handler->getObjects($criteria, true);
            $moduleperm_handler = xoops_gethandler('groupperm');
            $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
            $read_allowed = $moduleperm_handler->getItemIds('module_read', $groups);
            foreach (array_keys($modules) as $i) {
                if (in_array($i, $read_allowed)) {
                    $subpgs = $this->tem_subcategorias($modules[$i]->getVar("mid")*-1);
                    $retorna[($modules[$i]->getVar("mid"))*-1] = "-".$modules[$i]->getVar('name');
                    if ($subpgs) {
                        $retornaS = $this->geraMenuSelect(($modules[$i]->getVar("mid"))*-1);
                        foreach ($retornaS as $k => $v) {
                            $retorna[$k] = "&nbsp;&nbsp;&nbsp;  ".$v;
                        }
                    }
                }
            }
        }
        $tem_subs = 0;
        $cat_principal = 0;
        $categorias_query_catmenu = $this->db->query("select mpb_10_id, mpb_10_idpai, mpb_30_menu from ".$this->tabela." where mpb_10_idpai=" . (int)$mpb_10_idpai . " order by mpb_10_ordem, mpb_30_menu");
        while ($categorias = $this->db->fetchArray($categorias_query_catmenu))  {
            if($this->getVar($this->id) > 0 && $this->getVar($this->id) == $categorias['mpb_10_id']){
                continue;
            }
            $tem_subs= $this->tem_subcategorias($categorias['mpb_10_id']);
            $cat_principal = $this->cat_principal($categorias['mpb_10_id']);
            if ($cat_principal) {
                $categorias['mpb_30_menu'] = "-".$categorias['mpb_30_menu'];
            }
            if ($tem_subs) {
                $retorna[$categorias['mpb_10_id']] = $categorias['mpb_30_menu'];
                $retorna2 = $this->geraMenuSelect($categorias['mpb_10_id']);
                foreach ($retorna2 as $k => $v) {
                    $retorna[$k] = "&nbsp;&nbsp;&nbsp;  ".$v;
                }
            } else {
                $retorna[$categorias['mpb_10_id']] = $categorias['mpb_30_menu'];
            }
        }
        return $retorna;
    }
 
    public function tem_subcategorias($mpb_10_id=null, $menu = false) {
        global $xoopsUser;
        $id = (empty($mpb_10_id)) ? $this->getVar($this->id) : $mpb_10_id;
        if(!$menu){
            $cat_filha_query = $this->db->query("select count(*) as count from " . $this->tabela . " where mpb_10_idpai =" . $id);
            $cat_filha = $this->db->fetchArray($cat_filha_query);
            if ($cat_filha['count'] > 0) {
                return true;
            } else {
                return false;
            }
        }else{
            $pages = $this->getPages();
            $cat_filha_query = $this->db->query("SELECT ".$this->id." FROM " . $this->tabela . " where mpb_10_idpai =" . $id . " AND mpb_11_visivel < 3");
            $total =  $this->db->getRowsNum($cat_filha_query);
            if ($total > 0){
                while ( $myrow = $this->db->fetchArray($cat_filha_query) ) {
                    if (in_array($myrow[$this->id], $pages)) {
                        return true;
                    }
                }
            }
            return false;
        }
    }

    public function cat_principal($mpb_10_id) {
        $cat_principal_query = $this->db->query("select count(*) as count from " . $this->tabela . " where mpb_10_id = '" . (int)$mpb_10_id . "' and mpb_10_idpai=0");
        $cat_principal = $this->db->fetchArray($cat_principal_query);
        if ($cat_principal['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function PegaSmileys($campo = 'mpb_35_conteudo')
    {

        $myts = MyTextSanitizer::getInstance();
        $smiles = $myts->getSmileys();
        $ret = '';
        if (empty($smileys)) {
            $db = XoopsDatabaseFactory::getDatabaseConnection();
            if ($result = $db->query('SELECT * FROM '.$db->prefix('smiles').' WHERE display=1')) {
                while ($smiles = $db->fetchArray($result)) {
                    $ret .= "<img onclick=\"tinyMCE.execInstanceCommand('$campo', 'mceInsertContent', false, '<img src=\'".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles['smile_url'], ENT_QUOTES)."\' />');\" onmouseover='style.cursor=\"hand\"' src='".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles['smile_url'], ENT_QUOTES)."' alt='".$smiles['emotion']."' />";
                }
            }
        } else {
            $count = count($smiles);
            for ($i = 0; $i < $count; $i++) {
                if ($smiles[$i]['display'] == 1) {
                    $ret .= "<img onclick=\"tinyMCE.execInstanceCommand('$campo', 'mceInsertContent', false, '<img src=\'".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles[$i]['smile_url'], ENT_QUOTES)."\' />');\" onmouseover='style.cursor=\"hand\"' src='".XOOPS_UPLOAD_URL."/".$myts->oopsHtmlSpecialChars($smiles[$i]['smile_url'])."' border='0' alt='".$smiles[$i]['emotion']."' />";
                }
            }
        }
        return $ret;
    }
    
    
}

/**
 * @package             kernel
 * @copyright       (c) 2000-2016 XOOPS Project (www.xoops.org)
 */
class MastoppublishContentHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'mpu_mpb_mpublish', 'mastop_publishcontent', 'mpb_10_id', 'mpb_30_titulo');
    }

    /**
     * Read field information from cached storage
     *
     * @param bool $force_update read fields from database and not cached storage
     *
     * @return array
     */
    public function loadFields($force_update = false)
    {
        static $fields = array();
        if (!empty($force_update) || count($fields) == 0) {
            $this->table_link = $this->db->prefix('profile_category');
            $criteria         = new Criteria('o.field_id', 0, '!=');
            $criteria->setSort('l.cat_weight ASC, o.field_weight');
            $field_objs =& $this->getByLink($criteria, array('o.*'), true, 'cat_id', 'cat_id');
            foreach (array_keys($field_objs) as $i) {
                $fields[$field_objs[$i]->getVar('field_name')] = $field_objs[$i];
            }
        }

        return $fields;
    }

    /**
     * save a profile field in the database
     *
     * @param XoopsObject|ProfileField $obj   reference to the object
     * @param bool                     $force whether to force the query execution despite security settings
     *
     * @internal param bool $checkObject check if the object is dirty and clean the attributes
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(XoopsObject $obj, $force = false)
    {
        if (!($obj instanceof $this->className)) {
            return false;
        }
        $profile_handler = xoops_getModuleHandler('profile', 'profile');
        $obj->setVar('field_name', str_replace(' ', '_', $obj->getVar('field_name')));
        $obj->cleanVars();
        $defaultstring = '';
        switch ($obj->getVar('field_type')) {
            case 'datetime':
            case 'date':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_INT);
                $obj->setVar('field_maxlength', 10);
                break;

            case 'longdate':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_MTIME);
                break;

            case 'yesno':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_INT);
                $obj->setVar('field_maxlength', 1);
                break;

            case 'textbox':
                if ($obj->getVar('field_valuetype') != XOBJ_DTYPE_INT) {
                    $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                }
                break;

            case 'autotext':
                if ($obj->getVar('field_valuetype') != XOBJ_DTYPE_INT) {
                    $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTAREA);
                }
                break;

            case 'group_multi':
            case 'select_multi':
            case 'checkbox':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_ARRAY);
                break;

            case 'language':
            case 'timezone':
            case 'theme':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                break;

            case 'dhtml':
            case 'textarea':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTAREA);
                break;
        }

        if ($obj->getVar('field_valuetype') === '') {
            $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
        }

        if ((!in_array($obj->getVar('field_name'), $this->getUserVars())) && isset($_REQUEST['field_required'])) {
            if ($obj->isNew()) {
                //add column to table
                $changetype = 'ADD';
            } else {
                //update column information
                $changetype = 'CHANGE `' . $obj->getVar('field_name', 'n') . '`';
            }
            $maxlengthstring = $obj->getVar('field_maxlength') > 0 ? '(' . $obj->getVar('field_maxlength') . ')' : '';

            //set type
            switch ($obj->getVar('field_valuetype')) {
                default:
                case XOBJ_DTYPE_ARRAY:
                case XOBJ_DTYPE_UNICODE_ARRAY:
                    $type = 'mediumtext';
                    break;
                case XOBJ_DTYPE_UNICODE_EMAIL:
                case XOBJ_DTYPE_UNICODE_TXTBOX:
                case XOBJ_DTYPE_UNICODE_URL:
                case XOBJ_DTYPE_EMAIL:
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_URL:
                    $type = 'varchar';
                    // varchars must have a maxlength
                    if (!$maxlengthstring) {
                        //so set it to max if maxlength is not set - or should it fail?
                        $maxlengthstring = '(255)';
                        $obj->setVar('field_maxlength', 255);
                    }
                    break;

                case XOBJ_DTYPE_INT:
                    $type = 'int';
                    break;

                case XOBJ_DTYPE_DECIMAL:
                    $type = 'decimal(14,6)';
                    break;

                case XOBJ_DTYPE_FLOAT:
                    $type = 'float(15,9)';
                    break;

                case XOBJ_DTYPE_OTHER:
                case XOBJ_DTYPE_UNICODE_TXTAREA:
                case XOBJ_DTYPE_TXTAREA:
                    $type            = 'text';
                    $maxlengthstring = '';
                    break;

                case XOBJ_DTYPE_MTIME:
                    $type            = 'date';
                    $maxlengthstring = '';
                    break;
            }

            $sql = 'ALTER TABLE `' . $profile_handler->table . '` ' . $changetype . ' `' . $obj->cleanVars['field_name'] . '` ' . $type . $maxlengthstring . ' NULL';
            $result = $force ? $this->db->queryF($sql) : $this->db->query($sql);
            if (!$result) {
                return false;
            }
        }

        //change this to also update the cached field information storage
        $obj->setDirty();
        if (!parent::insert($obj, $force)) {
            return false;
        }

        return $obj->getVar('field_id');
    }

    /**
     * delete a profile field from the database
     *
     * @param XoopsObject|ProfileField $obj reference to the object to delete
     * @param bool   $force
     * @return bool FALSE if failed.
     **/
    public function delete(XoopsObject $obj, $force = false)
    {
        if (!($obj instanceof $this->className)) {
            return false;
        }
        $profile_handler = xoops_getModuleHandler('profile', 'profile');
        // remove column from table
        $sql = 'ALTER TABLE ' . $profile_handler->table . ' DROP `' . $obj->getVar('field_name', 'n') . '`';
        if ($this->db->query($sql)) {
            //change this to update the cached field information storage
            if (!parent::delete($obj, $force)) {
                return false;
            }

            if ($obj->getVar('field_show') || $obj->getVar('field_edit')) {
                $module_handler = xoops_getHandler('module');
                $profile_module = $module_handler->getByDirname('profile');
                if (is_object($profile_module)) {
                    // Remove group permissions
                    $groupperm_handler = xoops_getHandler('groupperm');
                    $criteria          = new CriteriaCompo(new Criteria('gperm_modid', $profile_module->getVar('mid')));
                    $criteria->add(new Criteria('gperm_itemid', $obj->getVar('field_id')));

                    return $groupperm_handler->deleteAll($criteria);
                }
            }
        }

        return false;
    }

    /**
     * Get array of standard variable names (user table)
     *
     * @return array
     */
    public function getUserVars()
    {
        return array(
            'uid',
            'uname',
            'name',
            'email',
            'url',
            'user_avatar',
            'user_regdate',
            'user_icq',
            'user_from',
            'user_sig',
            'user_viewemail',
            'actkey',
            'user_aim',
            'user_yim',
            'user_msnm',
            'pass',
            'posts',
            'attachsig',
            'rank',
            'level',
            'theme',
            'timezone_offset',
            'last_login',
            'umode',
            'uorder',
            'notify_method',
            'notify_mode',
            'user_occ',
            'bio',
            'user_intrest',
            'user_mailok');
    }
}
