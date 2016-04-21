<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

// THE SALESPRO AUTOLOADER
// LOADS CLASSES IN THE FOLLOWING ORDER: OVERRIDES - ADDONS - CORE
// THIS ALLOWS EASY CLASS OVERRIDING FOR INDIVIDUAL SETUPS
spl_autoload_register(function ($class) {
    foreach(array('spr','salesPro') as $name) {
        if (substr($class, 0, strlen($name)) === $name) {
            if($name === 'spr') $class = 'salesPro'.substr($class,strlen($name));
            $file = $class . '.class.php'; $dirs = array('overrides', 'addons', 'core');
                foreach ($dirs as $dir) {
                $xfile = JPATH_ADMINISTRATOR . '/components/com_salespro/classes/' . $dir . '/' . $file;
                if (file_exists($xfile)) {
                    require_once ($xfile);
                    break;
                }
            }
        }
    }
});

/* SALESPRO FACTORY METHOD
// THIS IS THE PREFERRED METHOD TO IMPLEMENT CLASSES */
interface salesProFactory {
    public static function _load();
    public static function _options();
}

class sprCk extends salesPro {
    
    private $ck = 0; 
    private $timeout = 60;

    public function __construct() { 
        parent::__construct();
        $this->timeout = $this->timeout * (60*60*24);
    }
    public function check($msg = 0) {
        if($this->ck === 1) return TRUE;
        $fdate = @filemtime(__FILE__);
        $vdate = @filemtime('components/com_salespro/salespro.php');
        $diff = abs($fdate - $vdate);
        $expiry = $vdate + $this->timeout;
        $now = time();
        if($msg === 0) return FALSE;
        $app = JFactory::getApplication();
        if ($app->isSite()) return FALSE;
        $query = "UPDATE `#__extensions` SET `enabled` = '0' WHERE `name` = 'plg_salespro' LIMIT 1";
        $db = JFactory::getDBO();
        $db->setQuery($query);
        $db->query();
        if($diff > 30 || $expiry < $now) {
            $view = 'update'; 
            $myview = JRequest::getCmd('view');
            $ok = array('update','dashboard');
            if(strlen($myview)>0 && !in_array($myview,$ok)) {
                $this->redirect('index.php?option=com_salespro&view='.$view);
            } else {
                JFactory::getApplication()->enqueueMessage(JText::_('This demo has expired').'. <a href="index.php?option=com_salespro&view=update">'.JText::_('Please click here to update').'</a>');
            }
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('This demo expires on ').' '.date("Y-m-d", $expiry).'. Please note automatic backups are disabled in the demo version. <a href="index.php?option=com_salespro&view=update">'.JText::_('Please click here to update').'</a>');
        }
        return FALSE;        
    }
}

$sprck = new sprCk;
$sprck->check(0);

//THE SALESPRO MASTER CLASS
abstract class salesPro {
    public $_adminpath = JPATH_ADMINISTRATOR;
    public $_sitepath = JPATH_SITE;
    public $_cdir = 'components/com_salespro/';
    public $_httpdir = '';
    public $_default_img = 'default/default.jpg';
    public $_dateTime = '';
    public $_searchRes = array();
    public $search = array();
    public $order = array('sort' => 'id', 'dir' => 'DESC', 'limit' => 20, 'page' => 0, 'total' => 0);
    public $actions = array();
    protected $_salt = 'Aba9122c!2$';
    //OPTIONS USED THROUGHOUT THE SYSTEM
    public $layoutOptions = array('0' => 'SPR_LAYOUT_OPTION1', '1' => 'SPR_LAYOUT_OPTION2');
    public $globalOptions = array('0' => 'SPR_GLOBAL', '1' => 'SPR_YES', '2' => 'SPR_NO');
    public $yesnoOptions = array('1' => 'SPR_YES', '2' => 'SPR_NO');
    public $noyesOptions = array('2' => 'SPR_NO', '1' => 'SPR_YES');
    public $stockOptions = array('1' => 'SPR_STOCK_OPTION1','2' => 'SPR_STOCK_OPTION2');
    public $pubOptions = array('1' => 'SPR_PUBLISHED', '2' => 'SPR_UNPUBLISHED');
    public $limitOptions = array('10' => '10', '20' => '20', '50' => '50', '100' => '100', '250' => '250');
    public $showbarOptions = array('1' => 'SPR_BAR_OPTION1', '2' => 'SPR_BAR_OPTION2');
    public $apiOptions = array('0' => 'SPR_API_OPTION1', '1' => 'SPR_API_OPTION2');
    public $certFile = 'salespro.crt';
    
    function __construct() {
        $this->_basedir = JURI::root();
        $this->_filebasepath = JPATH_SITE;
        $this->_httpdir = $this->_basedir . $this->_cdir;
        $this->_adminpath .= '/' . $this->_cdir;
        $this->_sitepath .= '/' . $this->_cdir;
        $this->_dateTime = date('Y-m-j H:i:s');
        $this->db = new salesProDb;
        $this->translateOptions();
    }
    
    function updateOrder() {
        $mytable = str_replace('#__spr_','',$this->_table);
        if(isset($_POST['spr_table'])) $table = $_POST['spr_table'];
        else $table = $mytable;

        //DIFFERENT SORTING FOR ADMIN AREA
        $admin = $this->checkAdminStatus();
        if($admin === TRUE) {
            $table = 'a'.$table;
            $mytable = 'a'.$mytable;
        }

        foreach($this->order as $field=>$var) {

            $cvar = FALSE;
            $cvar = sprCookies::_getVar($table.'.'.$field);

            if($table === $mytable && isset($_POST['spr_'.$field])) {
                $var = $_POST['spr_'.$field];
                sprCookies::_setVar($table.'.'.$field,$var);
            } else if($cvar !== FALSE) {
                $var = $cvar;
            }
            $this->order[$field] = $var;
        }
    }
    
    function checkAdminStatus() {
        $app = JFactory::getApplication();
        if ($app->isAdmin()) return TRUE;
        return FALSE;
    }
    
    function translateOptions() {
        $array = array('layout', 'yesno', 'noyes', 'stock', 'pub', 'limit', 'showbar', 'api');
        foreach($array as $a) {
            if(isset($this->$a)) {
                foreach($this->$a as $field=>$name) {
                    $name = JText::_($name);
                }
            }
        }
    }
    
    function getActiveUrl() {
        $url = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $url .= $_SERVER['SERVER_NAME'];
        $url .= $_SERVER['SCRIPT_NAME'];
        $x = 0;
        foreach ($_REQUEST as $a => $b) {
            if(is_array($a) || is_array($b)) continue;
            $a = htmlspecialchars(urlencode($a));
            $b = htmlspecialchars(urlencode($b));
            $url .= ($x++ > 0) ? '&' : '?';
            $url .= "{$a}={$b}";
        }
        return $url;
    }
    function getParams($params = array(),$masterparams = array()) {
        if(is_string($params)) $params = json_decode($params);
        if(count($masterparams)<1) $masterparams = $this->params;
        if(count($params)<1) $params = $masterparams;
        else {
            $params = (array)$params;
            foreach($masterparams as $type=>$val) {
                if(!isset($params[$type])) $params[$type] = $val;
            }
        }
        return (object) $params;
    }
    function sanitize($string, $type = 'string') {
        switch ($type) {
            case 'alphanum':
                $string = preg_replace('/[^a-zA-Z0-9]/', '', $string);
                break;
            case 'int':
                $string = preg_replace('/[^0-9]/', '', $string);
                break;
            case 'float':
                $string = preg_replace('/[^0-9\.]/', '', $string);
                break;
            case 'string':
            default:
                return $string;
                break;
        }
        return $string;
    }
    
    function sanitizeAll($inputs,$prefix='') {
        if(!is_array($inputs)) return $inputs;
        if(count($inputs)>0) foreach($inputs as $field=>$val) {
            $tempfield = str_replace($prefix, '', $field);
            if(!array_key_exists($tempfield,$this->_vars)) continue;
            if ($temp = $this->_vars[$tempfield]) {
                $val = $this->sanitize($val,$temp[0]);
            }
            $inputs[$field] = $val;
        }
        return $inputs;
    }
    function getDefaultMenuItemID() {
        $app = JFactory::getApplication(); 
        $menu = $app->getMenu();
        $menuItem = $menu->getItems('link', 'index.php?option=com_salespro&view=home', true);
        if(!empty($menuItem)) return $menuItem->id;
        return FALSE;
    }
    function escape($string) {
        $string = htmlentities($string);
        $string = $this->db->real_escape_string($string);
        return $string;
    }
    function error($msg) {
        $this->json = array();
        $this->json['error'] = $msg;
        $this->saveJson();
    }
    function saveJson() {
        die(json_encode($this->json));
    }
    function action() {
        $action = $_GET['action'];
        if (!in_array($action, $this->actions)) return false;
        //ALLOW OVERRIDES AND ADD-ONS
        $newaction = 'ajax' . ucfirst($action);
        if (method_exists($this, $newaction)) $this->$newaction($this->_table, $this->getVars());
        //STANDARD AJAX RESPONSE
        else {
            $ajax = new salesProAjax;
            if(method_exists($ajax, $action)) $ajax->$action($this->_table, $this->getVars());
        }
    }
    function getTable() {
        return $this->_table;
    }
    function uniqId($class = '', $length = 5) {
        //USAGE: leave $class blank to use active class, or enter a specific class to build hash for that class. If class doesn't exist, temporary uniques table will be used.
        $db = JFactory::getDBO();
        $dbx = 1;
        //REMOVE OLD TEMPORARY RESULTS
        $time = time();
        $oldtime = $time - (60 * 60); //1 hour
        $this->db->deleteData('#__spr_uniques', array('time' => "<" . $oldtime));
        //CHECK THE CLASS EXISTS - AND GET TABLE NAME
        if (class_exists($class)) {
            $table = $class::getTable();
        }
        else {
            $table = '#__spr_uniques';
        }
        //POTENTIAL CHARACTERS
        $chars = ('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
        $len = strlen($chars) - 1;
        //GENERATE THE HASH
        do {
            $ret = "";
            for ($x = 0; $x < $length; $x++) {
                $y = rand(0, $len);
                $ret .= $chars[$y];
            }
            if (!$res = $this->db->getResult($table, 'id', array('hash' => $ret))) $dbx = 0;
        } while ($dbx === 1);
        return $ret;
    }
    function redirect($url = '', $msg = '') {
        if ($url === '') {
            $uri = JFactory::getURI();
            $query = $uri->getQuery();
            $url = $uri->toString();
        }
        if ($url === '404') {
            header("HTTP/1.0 404 Not Found");
            header("location:404.html");
        }
        else {
            $app = JFactory::getApplication();
            $app->redirect($url, $msg);
        }
        die();
    }
    function getChildren($array, $parent = 0) {
        $ret = array();
        foreach ($array as $a) {
            if ($a->parent == $parent) {
                $a->children = isset($a->sub) ? $a->children : $this->getChildren($array, $a->
                    id);
                $ret[] = $a;
            }
        }
        return $ret;
    }
    function selectOptions($selected = '', $options = '', $text = 0, $disabled = array()) {
        if (!is_array($options) && $options !== '') {
            $temp = $options . 'Options';
            if (isset($this->$temp)) $options = $this->$temp;
        }
        if (!is_array($options)) return false;
        $ret = "";
        if(is_array($selected)) {
            $sel_array = $selected;
        } else {
            $sel_array = json_decode($selected);
            if(!is_array($sel_array) && strlen($sel_array)>0) $sel_array = array($sel_array);
            else $sel_array = array($selected);
        }
        foreach ($options as $o => $t) {
            $t = JText::_($t);
            if ($text !== 0) $o = $t;
            $sel = (in_array($o,$sel_array)) ? 'selected="selected"' : '';
            $dis = (in_array($o,$disabled)) ? 'disabled="disabled"' : '';
            $ret .= "<option value='{$o}' {$sel} {$dis}>{$t}</option>";
        }
        return $ret;
    }
    function getSearch($table = '', $where = array(), $joins = array(), $order = array()) {
        if ($table === '') $table = $this->_table;
        if (sizeof($order) < 1) $order = $this->order;
        $res = $this->db->getSearch($table, $where, $joins, $order);
        if (count($res) < 1) $res = array();
        return $res;
    }
    function getCount($table = '', $where = array(), $joins = array()) {
        if ($table == '') $table = $this->_table;
        $res = $this->db->getCount($table, $where, $joins);
        return (int)$res;
    }
    function getOrder() {
        foreach ($this->order as $var => $val) {
            if (isset($_POST['spr_' . $var])) $this->order[$var] = $_POST['spr_' . $var];
        }
    }
    function pageControls() {
        $next = $this->order['page'] + 1;
        $prev = $this->order['page'] - 1;
        $ret = "<div class='spr_controls'><input type='hidden' name='spr_sort' id='spr_sort' value='{$this->order['sort']}' /><input type='hidden' name='spr_dir' id='spr_dir' value='{$this->order['dir']}' /><span class='spr_control_next' onclick='page(\"{$next}\");'>".JText::_('SPR_NEXT')."</span><span class='spr_control_prev' onclick='page(\"{$prev}\");'>".JText::_('SPR_PREVIOUS')."</span><label for='spr_page'>".JText::_('SPR_PAGE').": </label><select name='spr_page' id='spr_page' onchange='page(this.value)'>";
        $pagecount = ceil($this->order['total'] / $this->order['limit']);
        $pages = array();
        for ($i = 1; $i <= $pagecount; $i++) {
            $pages[] = $i;
        }
        $ret .= $this->selectOptions($this->order['page'], $pages);
        $ret .= "</select><label for='spr_limit'>".JText::_('SPR_SHOW').": </label><select name='spr_limit' id='spr_limit' onchange='limit(this.value)'>";
        $ret .= $this->selectOptions($this->order['limit'], 'limit');
        $ret .= "</select></div>";
        return $ret;
    }
    function saveData($id = '', $array = array()) {
        $table = str_replace('#__', '', $this->_table);
        if (count($array) < 1) {
            if (count($_POST) > 0) {
                $tlen = strlen($table) + 1;
                foreach ($_POST as $field => $val) {
                    if (strpos($field, $table) !== 0) continue;
                    $field = substr($field, ($tlen));
                    $array[$field] = $val;
                }
            }
        }
        if ($id === '' && isset($_POST['spr_id'])) $id = (int)$_POST['spr_id'];
        else $id = (int)$id;
        //LOOP POST DATA INTO DATA ARRAY
        $data = array();
        foreach ($array as $field => $value) {

            if (!array_key_exists($field, $this->_vars)) continue;
            if ($field === 'alias') {
                $alternative = '';
                if (array_key_exists('name', $data)) {
                    $alternative = $data['name'];
                }
                $value = $this->checkAlias($value, $id, $this->_table, $alternative);
            }
            $datatype = $this->_vars[$field];
            $type = $datatype[0];
            $len = (isset($datatype[1])) ? $datatype[1] : 0;
            $default = (isset($datatype[2])) ? $datatype[2] : '';
            //SET THE DEFAULT VALUE
            if(is_object($value)) $value = (array)$value;
            if (!is_array($value)) {
                if (strlen($value) < 1) $value = $default;
            }
            //SET THE TYPE
            $value = $this->getDataType($value, $type);
            //TRUNCATE IF NECESSARY
            if (strlen($value) > $len) {
                if ($len > 0) $value = substr($value, 0, $len);
            }
            $data[$field] = $value;
        }
        if (count($data) < 1) return false;
        if ($id > 0) $this->db->updateData($this->_table, $data, array('id' => $id));
        else  $id = $this->db->insertData($this->_table, $data);
        return $id;
    }
    function cancelSave() {
        $id = (isset($_POST['spr_id'])) ? (int)$_POST['spr_id'] : 0;
        return $id;
    }
    function getDataType($value = '', $type = '') {
        switch ($type) {
            case 'int':
                $value = (int)$value;
                break;
            case 'json':
                $value = json_encode($value);
                break;
            case 'float':
                $value = (float)$value;
                break;
            case 'datetype':
                if(strlen($value)<1 || $value === '0000-00-00 00:00:00') $value = '';
                else $value = date("Y-m-j H:i:s", strtotime($value));
                break;
            case 'date':
                if(strlen($value)<1 || $value === '0000-00-00') $value = '';
                else $value = date("Y-m-j", strtotime($value));
                break;
            case 'time':
                $value = (int)$value;
                break;
            default:
            case 'string':
                $value = (string) $value;
                break;
        }
        return $value;
    }
    function deleteData($id = 0) {
        if($id === 0) $id = (int)$_POST['spr_id'];
        if ($id > 0) {
            return $this->db->deleteData($this->_table, array('id' => $id));
        }
    }
    function checkAlias($value = '', $id = 0, $table = '', $alternative = '') {
        $alias = strtolower($value);
        $alias = preg_replace('/[^a-z0-9\-]/i','', $alias);
        if(strlen($alias)<3) $alias = preg_replace('/[^a-z0-9\-]/i','-', $alternative);
        $alias = preg_replace('!\-+!', '-', $alias);
        $i = 0;
        do {
            $temp = $alias;
            if ($i > 0) $temp .= '-' . $i;
            $i++;
            $where = array('id' => "!={$id}", 'alias' => $temp);
            $count = $this->db->getCount($table, $where);
        } while ($count > 0);
        return $temp;
    }
    function getDefaultObject() {
        $vars = $this->_vars;
        $object = new stdClass;
        foreach ($vars as $name => $data) {
            if (isset($data[2])) $value = $data[2];
            else  $value = '';
            $type = $data[0];
            $value = $this->getDataType($value, $type);
            $object->$name = $value;
        }
        return $object;
    }
    function getVar($var='',$id=0) {
        $id = (int)$id;
        if(in_array($var,$this->getVars())) {
            $object = $this->db->getAssoc($this->_table, $var, array('id' => $id));
            if(isset($object[$var])) return $object[$var];
        }
        return FALSE;
    }
    function getVars() {
        $array = array();
        if (count($this->_vars) > 0)
            foreach ($this->_vars as $field => $type) {
                $array[] = $field;
            }
        return $array;
    }
    function setVar($id=0,$vars=array()) {
        $id = (int)$id;
        $okvars = array();
        if(count($vars)>0) foreach($vars as $field=>$val) {
            if(in_array($field,$this->getVars())) {
                $okvars[$field] = $val;
            }
        }
        return $this->db->updateData($this->_table,$okvars,array('id'=>$id));
    }
    function crypt($data) {
        $res = md5($data . $this->_salt);
        return $res;
    }
    function osort(&$array, $prop) {
        usort($array, function ($a, $b)use ($prop) {
            return $a->$prop > $b->$prop ? 1 : -1; }
        );
        return $array;
    }
    function showMsg($msg='') {
        if(strlen($msg)>0) JFactory::getApplication()->enqueueMessage($msg);
    }
}
