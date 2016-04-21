<?php

defined('_JEXEC') or die('Restricted access');

ini_set('display_errors', 'On');
error_reporting(E_ALL);

class com_salesProInstallerScript {
    
    public $oldversion = 0;
    public $newversion = 0;
    public $release = 0.0;
    public $dev_level = 0;
    public $cdir = '/components/com_salespro';
    public $log = '';

	function preflight($type, $parent) {
        $sprxml = JPATH_ADMINISTRATOR.$this->cdir.'/salespro.xml';
        if(file_exists($sprxml)) {
            $xml = JFactory::getXML($sprxml);
            $this->oldversion = (string) $xml->version;
        }
		$this->newversion = (string) $parent->get('manifest')->version;        
	}
	function postflight($type, $parent) {
        fclose($this->log);
        if(filesize(JPATH_ADMINISTRATOR.$this->cdir.'/install.log')>0) JFactory::getApplication()->enqueueMessage('<a href="components/com_salespro/install.log" target="_blank">Please click here to view the installation log</a>');
	}
    function uninstall_sql($parent) {
        $dir = JPATH_ADMINISTRATOR.$this->cdir.'/sql/';
        $file = $dir.'uninstall.sql';
        $errors = $this->updateDb($file);
    }
	function install($parent) {
        $this->log = fopen(JPATH_ADMINISTRATOR.$this->cdir.'/install.log','w+');
        fwrite($this->log, date("Y-m-d H:i:s")." Beginning SalesPro installation \r\n\r\n");
		$dir = JPATH_ADMINISTRATOR.$this->cdir.'/sql/';
        $file = $dir.'install.sql';
        $errors = $this->updateDb($file);
        if(count($errors)>0) foreach($errors as $e) {
            if(strlen($e)>2) fwrite($this->log,"Unable to run this query automatically: {$e};\r\n");
        }
        fwrite($this->log,"\r\nInstall complete");
	}
	function update($parent) {

        $this->log = fopen(JPATH_ADMINISTRATOR.$this->cdir.'/install.log','w+');
        fwrite($this->log, date("Y-m-d H:i:s")." Beginning SalesPro update \r\n\r\n");

        $dir =  JPATH_ADMINISTRATOR.$this->cdir.'/sql/updates/';
        $files = glob($dir . '*.sql', GLOB_MARK);
        natsort($files);
        $count = count($files);
        $errors = array();
        if(count($files)) foreach($files as $file) {
            $path = pathinfo($file);
            $version = $path['filename'];
            $size = filesize($file);
            if(($path['extension'] !== 'sql')
                || ($size < 1)
                || version_compare($version,$this->oldversion)<1)
                {
                    continue;
            }
            fwrite($this->log, date("Y-m-d H:i:s")." Updating SalesPro database to version {$version}\r\n\r\n");
            $errors = $this->updateDb($file);
            if(count($errors)>0) foreach($errors as $e) {
                if(strlen($e)>2) fwrite($this->log,"Unable to run this query automatically: {$e};\r\n");
            }
            fwrite($this->log,"\r\n");
        }
        if(version_compare('1.1.0',$this->oldversion) === 1) {
            $this->runMajorUpdate('1.1.0');
        }
        
        fwrite($this->log,"\r\nUpdate complete");
	}
    private function updateDb($file = '') {
        $size = filesize($file);
        $handle = fopen($file, 'r');
        $contents = fread($handle, $size);
        fclose($handle);
        if(strpos($contents,";\r\n") !== FALSE) $sep = ";\r\n";
        elseif(strpos($contents,";\n")!== FALSE) $sep = ";\n";
        else $sep = ";";
        $queries = explode($sep, $contents);
        $ret = array();
        $db = JFactory::getDBO();
        if(count($queries)>0) foreach($queries as $q) {
            if(strlen($q) < 2) continue;
            try {
                $db->setQuery($q);
                $db->query();
            }
            catch (Exception $e) {
                $ret[] = $q;
            }
        }
        return $ret;
    }
    
    private function runMajorUpdate($version=0,$old=0) {
        
        
        if($version === '1.1.0') {
            $db = JFactory::getDBO();
            
            //UPDATE CATEGORY IDS
            $query = "SELECT `id`, `category_id`, `added` FROM `#__spr_items`";
            $db->setQuery($query);
            $res = $db->loadAssocList();
            $time = time();
            if(count($res)>0) foreach($res as $r) {
                $cat = $r['category_id'];
                $cats = json_decode($cat);
                if(is_array($cats)) $cat = $cats[0];
                $added = (int) $r['added'];
                if($added < 1) {
                    $added = $time + $r['id'];
                }
                $query = "UPDATE `#__spr_items` SET `category` = '{$cat}', `added` = '{$added}' WHERE `id` = '{$r['id']}'";
                $db->setQuery($query);
                $db->query();
                if(count($cats)>0) foreach($cats as $cat) {
                    $query = "REPLACE INTO `#__spr_categories_map` SET `item` = '{$r['id']}', `category` = '{$cat}'";
                    $db->setQuery($query);
                    $db->query();
                }
            }
            $query = "ALTER TABLE `#__spr_items` DROP `category_id`;";
            try {
                $db->setQuery($query);
                $db->query();
            }
            catch(Exception $e) { }
            
            //UPDATE CONFIGURATION
            $query = "SELECT * FROM `#__spr_settings`";
            $db->setQuery($query);
            $config = $db->loadAssoc();
            $params = (array)json_decode($config['params']);
            
            $save = array(
                'core' => $params,
                'thankyou' => array(
                    'title' => @$config['ty_title'],
                    'content' => @$config['ty_content']
                ),
                'files' => array(
                    'valid' => @$params['validfiles'],
                    'loc' => @$params['fileloc']
                ),
                'images' => array(
                    'crop' => '2',
                    'bg' => '',
                    'valid' => @$params['validimgs'],
                    'loc' => @$params['imgloc']
                ),
                'units' => array(
                    'size' => 'mm',
                    'weight' => 'grams'
                )
            );
            foreach($save as $name=>$data) {
                $data = json_encode($data);
                $query = "REPLACE INTO `#__spr_config` SET `name` = '{$name}', `params` = '{$data}'";
                $db->setQuery($query);
                $db->query();
            }
        }
    }
}