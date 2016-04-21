<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2015 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

class salesProBackups extends salesPro {
    
    public $dump_dir = 'dumps/';
    private $config = array(
        'auto' => '0',    
        'keep' => '0',
        'gzip' => '1',
        'optimize' => '1',
        'repair' => '1',
        'email' => '0',
        'address' => 'your@email.com'
    );

	function __construct() {
        parent::__construct();
        $this->dump_dir = $this->_adminpath.$this->dump_dir;
	}        
    
    function getConfig() {
        $config = sprConfig::_load('backups',$this->config);
        return $config;
    }
    
    function getBackups() {
        $res = array();
        if(!is_dir($this->dump_dir)) return $res;
        $files = scandir($this->dump_dir);
        if(count($files)>0) foreach($files as $file) {
            $path = pathinfo($file);
            if(isset($path['extension'])) {
                $ext = $path['extension'];
                if($ext === 'gz' || $ext === 'sql') {
                    $file = $this->dump_dir.$file;
                    $res[] = (object) array(
                        'name' => $path['filename'].'.'.$ext,
                        'date' => date("d F Y H:i:s", filemtime($file)),
                        'size' => $this->formatBytes(filesize($file))
                    );
                }
            }
        }
        rsort($res);
        return $res;
    }
    
    function autoTasks($now,$timer) {

        $processed = 0;
        
        $newhr = date('G',$now);
        $oldhr = date('G',$timer);
        
        $newday = date('z',$now);
        $oldday = date('z',$timer);
        
        $newwk = date('W',$now);
        $oldwk = date('W',$timer);
        
        $newmth = date('n',$now);
        $oldmth = date('n',$timer);        
        
        $config = $this->getConfig();

        switch($config->auto) {
            case '1': if($newhr !== $oldhr) {
                    $processed = 1;
                    $this->makeSql();
                }
                break;
            case '2': if($newday !== $oldday) {
                    $processed = 1;
                    $this->makeSql();
                }
                break;
            case '3': if($newwk !== $oldwk) {
                    $processed = 1;
                    $this->makeSql();
                }
                break;
            case '4': if($newmth !== $oldmth) {
                    $processed = 1;
                    $this->makeSql();
                }
                break;
        }
        switch($config->keep) {
            case '1': if($newhr !== $oldhr) {
                    $processed = 1;
                    $this->purgeBackups($config->keep);
                }
                break;
            case '2': if($newday !== $oldday) {
                    $processed = 1;
                    $this->purgeBackups($config->keep);
                }
                break;
            case '3': if($newwk !== $oldwk) {
                    $processed = 1;
                    $this->purgeBackups($config->keep);
                }
                break;
            case '4': if($newmth !== $oldmth) {
                    $processed = 1;
                    $this->purgeBackups($config->keep);
                }
                break;
        }
        if($processed === 1) return TRUE;
        return FALSE;
    }
    
    function purgeBackups($keep=0) {
        if((int)$keep === 0) return;
        $now = time();
        $backups = $this->getBackups();
        if(count($backups)>0) foreach($backups as $b) {
            $time = strtotime($b->date);
            switch($keep) {
                case '1': $max = 60*60*24;
                    break;
                case '2': $max = 60*60*24*7;
                    break;
                case '3': $max = 60*60*31;
                    break;
                case '4': $max = 60*60*31*2;
                    break;
                default: return false;
                    break;
            }
            if(($now - $time)>$max) {
                $this->delBackup($b->name);
            }
        }
    }
    
    function delBackup($dl = '') {
        if($dl === '') {
            if(isset($_GET['dl'])) $dl = trim($_GET['dl']);
        }
        if(is_dir($this->dump_dir) && $dl !== '') {
            if(file_exists($this->dump_dir.$dl)) {
                @unlink($this->dump_dir.$dl);
                return TRUE;                                
            }
        }
        return FALSE;                
    }
    
    function formatBytes($size, $precision = 2) {
        $base = log($size, 1024);
        $suffixes = array('b', 'Kb', 'Mb', 'Gb', 'Tb');
        if($size <= 1) return '0b';
        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }

    function makeSql() {
        
        $db = JFactory::getDBO();
        
        $config = $this->getConfig();
        $ret = '';
        $name = 'salespro-';
        do {
            $temp = $name.date("Y-m-d-H-i-s").'.sql';
            if($config->gzip === '1') $temp .= '.gz';
            if(!file_exists($this->dump_dir.$temp)) {
                $name = $temp;
            }
        } while(strlen($name)<=10);
        $bkfile = $this->dump_dir.$name;
        if(file_exists($bkfile)) unlink($bkfile);
        $handle = fopen($bkfile,'w+');

        $tables = $this->getTables();
        if(count($tables)>0) foreach($tables as $table) {
            if($config->optimize === '1') {
                $query = "OPTIMIZE TABLE `{$table}`";
                try {
                    $db->setQuery($query);
                    $db->query();
                } catch (Exception $e) {
                    //silently ignore
                }
            }
            if($config->repair === '1') {
                $query = "REPAIR TABLE `{$table}`";
                try {
                    $db->setQuery($query);
                    $db->query();
                } catch (Exception $e) {
                    //silently ignore
                }
            }            
            $query = "SHOW CREATE TABLE ".$db->quoteName($table);
            $db->setQuery($query);
            $res = $db->loadRow();
            if(!isset($res[1])) continue;
            $res[1] = str_replace('TYPE=', 'ENGINE=', $res[1]);
            $ret .= "DROP TABLE IF EXISTS ".$db->quoteName($table).";\r\n";
            $ret .= "{$res[1]};\r\n";
            $ret .= "LOCK TABLES ".$db->quoteName($table)." WRITE;\r\n";
            $query = "SELECT * FROM ".$db->quoteName($table)." LIMIT 0, 1";
            $db->setQuery($query);
            $fields = $db->loadAssoc();
            for($x=0; $x>-1; $x+=50) {
                $query = "SELECT * FROM ".$db->quoteName($table)." LIMIT $x, 50";
                $db->setQuery($query);
                $res = $db->loadAssocList();
                $rows = count($res);
                if($rows < 1) break;
                $ret .= "INSERT INTO ".$db->quoteName($table)." (";
                $i=0;
                $fcount = count($fields);
                foreach($fields as $f=>$d) {
                    $ret .= $db->quoteName($f);
                    if($i<$fcount-1) $ret .= ",";
                    $i++;
                }
                $ret .= ") VALUES ";
                foreach($res as $n=>$r) {
                    $ret .= "(";
                    $i=0;
                    foreach($r as $s) {
                        $ret .= $db->quote(stripcslashes($s));
                        if($i <$fcount-1) $ret .= ",";
                        $i++;
                    }
                    $ret .= ")";
                    if($n<$rows-1) {
                        $ret .= ",";
                    }
                }
                $ret .= ";\r\n";
                if($config->gzip === '1') $ret = gzencode($ret,1);
                if($handle) {
                    $ok = fwrite($handle,$ret);
                    $ret = "";
                }
                if($rows < 50) break;
            }
            $ret .= "UNLOCK TABLES;\r\n";                                    
            if($config->gzip === '1') $ret = gzencode($ret,1);
            if($handle) {
                $ok = fwrite($handle,$ret);
                $ret = "";                                
            }
        }
        if($handle) fclose($handle);
        
        if($config->email === '1') {
    		$subject = JText::_('SPR_BAK_BACKUP_ATTD');
    		$img = $this->_httpdir.'administrator/components/com_salespro/resources/images/salespro.png';
            $message = "";
            $message = '<html><head><style type="text/css">body { font-family: Verdana, Arial; color: #666; font-size: 12px; }
    p { font-size: 12px; }</style></head><body><table width="98%" align="center" cellpadding="20" style="height:100%"><tr><td bgcolor="#eeeeee" valign="top"><table width="480" align="center" cellpadding="20"><tr><td bgcolor="#ffffff"><p><img src='.$img.' alt="SalesPro" height="70" /><br style="clear:both" /></p><p>This is an automated message sent directly from your server.</p><p>Your database backup is attached.</p></td></tr></table><table width="600" align="center" cellpadding="5"><tr><td bgcolor="#eeeeee" align="center"><p style="font-size:  10px;">SalesPro Automatic Backups</p></td></tr></table></td></tr></table></body></html>';
            $mail = new salesProMailer;
                $mail->setFrom($config->address);
                $mail->setTo($config->address);
                $mail->setSubject($subject);
                $mail->setBody($message);
                $mail->setAttachment($bkfile);
            try {
                $res = $mail->send();
            }
            catch (Exception $e) {
                $msg = JText::_('SPR_BAK_SPR_EMAIL_CANTSEND');
                sprLog::_log($msg.$order_id);
            }
        }
        return TRUE;
    }

    private function getTables() {
		require_once(JPATH_CONFIGURATION.'/configuration.php');
		$CONFIG = new JConfig();
		$name = $CONFIG->db;
        $name = addslashes($name);
        //GET SPR TABLES
        $db = JFactory::getDBO();
		$query = "SHOW TABLES FROM `$name` LIKE ".$db->quote($CONFIG->dbprefix.'spr_%');
		$db->setQuery($query);
		$array = $db->loadObjectList();
        if(count($array)>0) foreach($array as $a) {
            foreach($a as $b) {
                if(strpos($b,'templates') !== FALSE) continue;
                $tabarray[] = $b;
            }
        }
        return $tabarray;
    }
}

class sprBackups {
    
    public static function _load() {
        $class = new salesProBackups;
        return $class->getBackups();        
            
    }    
            
    public static function _getConfig() {
        $class = new salesProBackups;
        return $class->getConfig();        
            
    }
    
}