<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2015 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

require_once(JPATH_ADMINISTRATOR.'/components/com_salespro/controllers/controller.php');

class salesProCSVController extends salesProControllerAdmin {
    
    private $dump_dir = '/dumps/';
    
    public function import() {
        if(!$this->auth()) return;
        if (!empty($_FILES['Filedata'])) {
            $error = $_FILES['Filedata']['error'];
            if($error === 0 && $_FILES['Filedata']['size'] >= $this->upload_limit) $error = 2;
            if((int)$error !== 0) {
                switch($error) {
                    case 1:
                    case 2:
                        $msg = JText::_('SPR_IMP_ERROR2');
                        break;
                    case 3: $msg = JText::_('SPR_IMP_ERROR3');
                        break;
                    case 4: $msg = JText::_('SPR_IMP_ERROR4');
                        break;
                    case 6: $msg = JText::_('SPR_IMP_ERROR6');
                        break;
                    case 7: $msg = JText::_('SPR_IMP_ERROR7');
                        break;
                    case 8: $msg = JText::_('SPR_IMP_ERROR8');
                        break;
                    default: $msg = JText::_('SPR_IMP_ERROR_DEFAULT');
                        break;
                }
                die($msg);
            }
            $fileParts = pathinfo($_FILES['Filedata']['name']);
            $ext = strtolower($fileParts['extension']);
            if($ext === 'csv') {
                $tempFile = $_FILES['Filedata']['tmp_name'];
                if(!$this->readCSV($tempFile,$fileParts['filename'])) echo JText::_('SPR_IMP_ERROR_NOFILE');
                echo '1';
        	} else {
        		echo JText::_('SPR_IMP_ERROR_FTYPE');
        	}
        } else {
            echo JText::_('SPR_IMP_ERROR_NOFILE');
        }
    }
    
    function readCSV($file='',$filename='') {
        if(!$this->auth()) return;
        if(strlen($file)<1 || !file_exists($file) || filesize($file)<1 || strlen($filename)<1) return FALSE;
        $file = fopen($file,"r");
        $i = 0;
        
        $query = "DELETE FROM ".$this->db->quoteName($filename);
        try {
            $this->db->setQuery($query);
            $this->db->query();
        } catch (Exception $e) {
            echo $this->db->getErrorMsg();
            return TRUE;
        }
        
        $query = "INSERT INTO ".$this->db->quoteName($filename);
        while(!feof($file)) {
            $data = fgetcsv($file);
            $j = 0;
            if($i++ === 0) {
                $query .= "(";
                foreach($data as $d) {
                    if($j++ > 0) $query .= ',';
                    $query .= $this->db->quoteName($d);
                }
                $query .= ") VALUES ";
            } else {
                if($data && count($data)>0) {
                    if($i > 2) $query .= ',';
                    $query .= '(';
                    foreach($data as $d) {
                        if($j++ > 0) $query .= ',';
                        $query .= $this->db->quote($d);
                    }
                    $query .= ')';
                }
            }
        }
        try {
            $this->db->setQuery($query);
            $this->db->query();
        } catch (Exception $e) {
            echo $this->db->getErrorMsg();
        }
        return TRUE;
    }
    
    function makeCSV() {
        if(!$this->auth()) return;
        $name = 'salespro-';
        do {
            $temp = $name.date("Y-m-d-H-i-s").'.zip';
            if(!file_exists($this->dump_dir.$temp)) {
                $name = $temp;
            }
        } while(strlen($name)<=10);
        $tables = $this->getTables();
        $csvzip = $this->dump_dir.$name;
        if(file_exists($csvzip)) unlink($csvzip);
        $archive = new salesProArchive($csvzip,$this->dump_dir,$this->dump_dir);
        if(count($tables)>0) foreach($tables as $table) {
            $this->tabletoCSV($table);
            $archive->add($table.'.csv');
            @unlink($this->dump_dir.$table.'.csv');
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".$name."\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($csvzip));
        ob_end_flush();
        @readfile($csvzip);
    }
    
    function tabletoCSV($table='') {
        if(!$this->auth()) return;
        $query = "SHOW COLUMNS FROM `{$table}`";
        $this->db->setQuery($query);
        $temp = $this->db->loadAssocList();
        $fields = array();
        if(count($temp)<1) return FALSE;
        
        $fp = fopen($this->dump_dir.$table.'.csv', 'w+');
        
        foreach($temp as $a=>$b) $fields[] = $b['Field'];
        fputcsv($fp,$fields);
        
        $query = "SELECT * FROM `{$table}`";
        $this->db->setQuery($query);
        $data = $this->db->loadAssocList();
        
        foreach($data as $d) {
            fputcsv($fp, $d);
        }
        
        fclose($fp);
    }
    
	function __construct( $default = array()) {
	   
		parent::__construct( $default );
        
        $this->dump_dir = $this->basePath.$this->dump_dir;
        if(!is_dir($this->dump_dir)) mkdir($this->dump_dir);
        
        //TIME DELAY MANAGEMENT
        $this->time_start = microtime(true);
                
        //CHECK UPLOAD LIMIT
        $this->max_upload = (int) ini_get('upload_max_filesize');
        $this->max_post = (int) ini_get('post_max_size');
        $this->memory_limit = (int) ini_get('memory_limit');
        $this->upload_limit = min($this->max_upload, $this->max_post, $this->memory_limit);
        $this->upload_limit = ($this->upload_limit * 1024 * 1024);
        
        //DATABASE UPLOADED FILE
        $this->dbFile = JPATH_ADMINISTRATOR.'/components/com_salespro/import.sql';

        //START THE DATABASE
        $this->db = JFactory::getDBO();
	}

    private function error($msg) {
        $this->json = array();
        $this->json['error'] = $msg;
        $this->saveJson();
    }
    
    private function saveJson() {
        $time_end = microtime(true);
        $diff = $time_end - ($this->time_start + ($this->delay * 1000000));
        if($diff < 0) usleep(abs($diff));
        die(json_encode($this->json));
    }
        
    private function getTables() {
        if(!$this->auth()) return;
		require_once(JPATH_CONFIGURATION.'/configuration.php');
		$CONFIG = new JConfig();
		$name = $CONFIG->db;
        $name = addslashes($name);
        //GET SPR TABLES
		$query = "SHOW TABLES FROM `$name` LIKE ".$this->db->quote($CONFIG->dbprefix.'spr_%');
		$this->db->setQuery($query);
		$array = $this->db->loadObjectList();
        if(count($array)>0) foreach($array as $a) {
            foreach($a as $b) {
                if(strpos($b,'templates') !== FALSE) continue;
                $tabarray[] = $b;
            }
        }
        return $tabarray;
    }
}