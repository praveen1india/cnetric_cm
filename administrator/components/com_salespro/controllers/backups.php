<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2015 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

require_once(JPATH_ADMINISTRATOR.'/components/com_salespro/controllers/controller.php');

class salesProBackupsController extends salesProControllerAdmin {

    function saveConfig() {
                
        if(!$this->auth()) return;
        if(isset($_POST['spr_backups_config'])) {
            $save = $_POST['spr_backups_config'];
            $config = sprBackups::_getConfig();
            foreach($save as $field=>$val) {
                if(isset($config->$field)) $config->$field = $val;
            }
            $save = sprConfig::_save('backups',$config);
        }
        if($save) $msg = JText::_('SPR_BAK_CONFIG_SAVED');
        else $msg = JText::_('SPR_BAK_CONFIG_NOT_SAVED');
        
        $redir = 'index.php?option=com_salespro&view=backups&tab=config';
		$this->setRedirect($redir, $msg);
    }
    
    function dlBackup() {
        if(!$this->auth()) return;
        
        $class = new salesProBackups;
        $dump_dir = $class->dump_dir;
        
        if(is_dir($dump_dir) && isset($_GET['dl'])) {
            $dl = $_GET['dl'];
            if(file_exists($dump_dir.$dl)) {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=\"".$dl."\"");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: ".filesize($dump_dir.$dl));
                ob_end_flush();
                @readfile($dump_dir.$dl);
                die();
            }
        }
        $redir = 'index.php?option=com_salespro&view=backups&tab=backups';
		$msg = JText::_('SPR_BAK_CANT_DL');
        $this->setRedirect($redir, $msg);
    }
    
    function delBackup() {
        if(!$this->auth()) return;
        $class = new salesProBackups;
        if($class->delBackup() === TRUE) {
            $msg = JText::_('SPR_BAK_DEL_OK');
        } else {
            $msg = JText::_('SPR_BAK_CANT_DL');
                    
        }                        
        $redir = 'index.php?option=com_salespro&view=backups&tab=backups';
        $this->setRedirect($redir, $msg);
    }
    
    function mkBackup() {
        $class = new salesProBackups;
        if($class->makeSql() === TRUE) {
            $msg = JText::_('SPR_BAK_BACKUP_CREATED');
        } else {
            $msg = JText::_('SPR_BAK_BACKUP_NOT_CREATED');
        }
        $redir = 'index.php?option=com_salespro&view=backups&tab=backups';
        $this->setRedirect($redir, $msg);
    }
    
    function restoreBackup() {
        
        
        $class = new salesProBackups;
        $dump_dir = $class->dump_dir;
        
        if(!$this->auth()) return;
        $speed = 1024*256; //256Kb chunks
        $mytabs = array();
        if(isset($_POST['spr_bkp_what']) && count($_POST['spr_bkp_what'])>0) {
            foreach($_POST['spr_bkp_what'] as $f) {
                switch($f) {
                    case 'cats': 
                        $mytabs[] = 'categories';
                        $mytabs[] = 'items';
                        $mytabs[] = 'item_dls';
                        $mytabs[] = 'item_dl_links';
                        $mytabs[] = 'item_faqs';
                        $mytabs[] = 'item_images';
                        $mytabs[] = 'item_optiongroups';
                        $mytabs[] = 'item_options';
                        $mytabs[] = 'item_videos';
                        break;
                    case 'sales': 
                        $mytabs[] = 'sales';
                        $mytabs[] = 'sales_items';
                        break;
                    case 'users': 
                        $mytabs[] = 'users';
                        break;
                    case 'shipping': 
                        $mytabs[] = 'shipping';
                        $mytabs[] = 'shippingrules';
                        break;
                    case 'payment': 
                        $mytabs[] = 'payment_methods';
                        $mytabs[] = 'payment_options';
                        break;
                    case 'regions': 
                        $mytabs[] = 'currencies';
                        $mytabs[] = 'regions';
                        break;
                    case 'configs': 
                        $mytabs[] = 'config';
                        $mytabs[] = 'prodtypes';
                        break;
                    case 'widgets': 
                        $mytabs[] = 'widgets';
                        break;
                    case 'emails': 
                        $mytabs[] = 'emails';
                        break;
                }
            }
        }
        if(count($mytabs)<1) {
            $redir = 'index.php?option=com_salespro&view=backups&tab=backups';
    		$msg = JText::_('SPR_BAK_SELECT_DATA');
            $this->setRedirect($redir, $msg);
            return;
        }
        if(is_dir($dump_dir) && isset($_POST['spr_bkp_backup'])) {
            $bk = $_POST['spr_bkp_backup'];
            if(file_exists($dump_dir.$bk)) {
                $bk = $dump_dir.$bk;
                $sep = NULL;
                $sql = '';
                if($handle = gzopen($bk, "r")) {
                    while (!gzeof($handle)) {
                        $sql .= gzread($handle, $speed);
                        if(is_null($sep)) {
                            foreach(array(";\r\n",";\n",";\r") as $a) {
                                if(strpos($sql,$a)!== FALSE) {
                                    $sep = $a;
                                    break;
                                }
                            }
                        }
                        if(!is_null($sep) && substr_count($sql, $sep) > 1) {
                            $queries = explode($sep, $sql);
                            if(count($queries)>0) {
                                if(!feof($handle)) $sql = array_pop($queries);
                                $sqlrestore = array();
                                foreach($queries as $q) {
                                    if(strlen(trim($q))<5) continue;
                                    $tabs = array();
                                    $tables = explode('`', $q);
                                    if(count($tables)<3) continue;
                                    $command = trim($tables[0]);
                                    $table = $tables[1];
                                    $sprtab = substr($table,strpos($table,'spr_')+4);
                                    if(!in_array($sprtab,$mytabs)) continue;
                                    switch($command) {
                                        case 'CREATE TABLE':
                                            $sqlrestore[] = "LOCK TABLES `$table`";
                                            $sqlrestore[] = "TRUNCATE `$table`";
                                            $sqlrestore[] = "UNLOCK TABLES";
                                            break;
                                        case 'INSERT INTO':
                                            $sqlrestore[] = "LOCK TABLES `$table`";
                                            $sqlrestore[] = $q;
                                            $sqlrestore[] = "UNLOCK TABLES";
                                            break;
                                        default: continue;
                                    }
                                }
                                if(count($sqlrestore)>0) {
                                    foreach($sqlrestore as $q) {
                                        try {
                                            $this->db->setQuery($q);
                                            $this->db->query();
                                        }
                                        catch (Exception $e) {
                                            //ADD LOGGING
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $redir = 'index.php?option=com_salespro&view=backups&tab=backups';
          		$msg = JText::_('SPR_BAK_DATA_OK');
                $this->setRedirect($redir, $msg);
                return;
            }
        }
        $redir = 'index.php?option=com_salespro&view=backups&tab=backups';
		$msg = JText::_('SPR_BAK_CANT_RESTORE');
        $this->setRedirect($redir, $msg);
    }
    
	function __construct( $default = array()) {
		parent::__construct( $default );
        $this->db = JFactory::getDBO();
	}
}