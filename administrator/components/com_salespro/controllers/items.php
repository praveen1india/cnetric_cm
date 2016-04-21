<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2015 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

require_once(JPATH_ADMINISTRATOR.'/components/com_salespro/controllers/controller.php');

class salesProItemsController extends salesProControllerAdmin {

	function __construct( $default = array()) {	   
		parent::__construct( $default );
	}
    
    function copy() {
        JRequest::checkToken() or jexit(JText::_('SPR_BADTOKEN'));

        //GET THE TABLE NAME
        $table = JRequest::getCmd('spr_table');
        $tab = explode('_',$table);
        $table = '';
        foreach($tab as $t) {
            $table .= ucfirst($t);
        }
        
        //START THE CLASS
        $class = 'salesPro'.$table;
        if(class_exists($class) && $x = new $class()) {
            $y = $x->cancelSave();
        }
        
        //DUPLICATE IMAGES, FAQs and DLs
        $id = JRequest::getVar('spr_id');
        var_dump($id);
        
        $images = new salesProItemImages;
        $imgs = $images->getImages($id);
        if(count($imgs)>0) foreach($imgs as $img) {
            $img->id = 0;
            $img->item_id = 0;
            $x = $images->saveData(0,(array)$img);
            var_dump($x);
        }
                        
        //REDIRECT URL
        $redir = 'index.php?option=com_salespro&view=items&layout=copy';
        $id = JRequest::getVar('spr_id');
        $redir .= '&id='.$id;
        $this->setRedirect($redir);
    }
}