<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProItems extends salesPro {
    public $_table = '#__spr_items';
    public $catid = 0;
    public $itemid = 0;
    public $_vars = array(
        'id' => array('int', 11),
        'sort' => array('int', 6),
        'category' => array('int', 6),
        'status' => array('int', 4),
        'name' => array('string', 100),
        'tagline' => array('string', 255),
        'alias' => array('string', 100),
        'featured' => array('int', 1),
        'taxes' => array('json', 100),
        'price' => array('float'),
        'type' => array('int', 4),
        'sku' => array('string', 20),
        'weight' => array('float'),        
        'stock' => array('int', 6),
        'sale' => array('float'),
        'onsale' => array('int',1),
        'mini_desc' => array('string'),
        'full_desc' => array('string'),
        'height' => array('float'),
        'width' => array('float'),
        'depth' => array('float'),
        'manufacturer' => array('string', 50),
        'origin' => array('string', 50),
        'specification' => array('string'),
        'tab1_active' => array('int', 4, 1),
        'tab1_name' => array('string', 50),
        'tab2_active' => array('int', 4, 2),
        'tab2_name' => array('string', 50),
        'tab3_active' => array('int', 4, 2),
        'tab3_name' => array('string', 50),
        'tab4_active' => array('int', 4, 2),
        'tab4_name' => array('string', 50),
        'tab5_active' => array('int', 4, 2),
        'tab5_name' => array('string', 50),
        'meta_title' => array('string', 255),
        'meta_keys' => array('string', 255),
        'meta_desc' => array('string'), 
        'added' => array('int',11)
    );
    public $_searchTerms = array(
        'name',
        'status',
        'featured',
        'max_price',
        'min_price',
        'category',
        'sort_by'
    );
    public $order = array(
        'sort' => 'z.sort',
        'dir' => 'ASC',
        'limit' => 20,
        'page' => 0,
        'total' => 0
    );
    public $sort_by = array(
        '1' => array('z.name', 'ASC'), 
        '2' => array('z.name', 'DESC')
    );
    public $actions = array(
        'status',
        'featured',
        'delete',
        'resort'
    );
    public $attributes = array(
        'sku' => 'SPR_ITEM_SKU',
        'weight' => 'SPR_ITEM_WEIGHT',
        'height' => 'SPR_ITEM_HEIGHT',
        'width' => 'SPR_ITEM_WIDTH',
        'depth' => 'SPR_ITEM_DEPTH',
        'manufacturer' => 'SPR_ITEM_MANUFACTURER',
        'origin' => 'SPR_ITEM_ORIGIN'
    );
    function __construct($catid = 0,$limit = 0) {
        parent::__construct();
        foreach($this->attributes as $n=>$o) {
            $this->attributes[$n] = JText::_($o);
        }
        $limit = (int) $limit;
        if($limit > 0) $this->order['limit'] = $limit;
        if ($catid > 0) $this->catid = (int)$catid;
    }
    function getSearch($table = '', $where = array(), $joins = array(), $order = array()) {

        //SET UP THE SEARCH TERMS
        foreach ($this->_searchTerms as $s) {
            $this->search[$s] = '';
        }
        //RESTRICT BY THE SELECTED CATEGORY
        if ($this->catid > 0) {
            $where['m.category'] = $this->catid;
        }
        foreach ($this->_searchTerms as $field) {
            if (isset($_REQUEST['spr_search_clear'])) continue;
            if (isset($_REQUEST['spr_search_' . $field])) {
                $val = $_REQUEST['spr_search_'.$field];
                if (is_array($val)) continue;
                if (strlen($val) === 0) continue;
                $val = htmlspecialchars(urlencode($val));
                if ($field === 'name') {
                    $val = $this->sanitize($val, 'string');
                    $where['z.name'] = 'LIKE%' . $val . '%';
                } elseif ($field === 'status') {
                    $val = $this->sanitize($val, 'int');
                    $where['z.status'] = $val;
                } elseif ($field === 'featured') {
                    $val = $this->sanitize($val, 'int');
                    $where['z.featured'] = $val;
                } elseif ($field === 'min_price') {
                    $val = $this->sanitize($val, 'float');
                    $where['z.price']['from'] = $val;
                } elseif ($field === 'max_price') {
                    $val = $this->sanitize($val, 'float');
                    $where['z.price']['to'] = $val;
                } elseif ($field === 'category') {
                    $val = $this->sanitize($val, 'int');
                    $where[] = array('m.category' => $catid, 'z.category' => $catid);
                }
                $this->search[$field] = $val;
            }
        }
        //IF WE ARE IN THE MAIN SITE HIDE UNPUBLISHED
        $app = JFactory::getApplication();
        if ($app->isSite())  $where['z.status'] = '1';
        $joins[] = array('LEFT', '#__spr_categories_map', 'm', 'z.id', 'm.item');

        //ADD THE ORDER VARIABLES
        $order = array_merge($this->order,$order);
        foreach ($this->order as $field => $val) {
            if (isset($_REQUEST['spr_' . $field])) {
                $val = $_REQUEST['spr_' . $field];
                if (is_array($val)) continue;
                if (strlen($val) === 0) continue;
                $val = htmlspecialchars(urlencode($val));
                if ($field === 'sort') {
                    $string = explode('-', $val);
                    $val = $string[0];
                    if (isset($string[1])) {
                        $order['dir'] = $string[1];
                    }
                }
                $order[$field] = $val;
            }
        }
        $this->order = $order;
        
        
        //GET THE SEARCH RESULTS
        $this->_searchRes = parent::getSearch($table, $where, $joins, $order);
        //GET COUNT OF ALL POTENTIAL RESULTS
        $this->order['total'] = parent::getCount($table, $where, $joins);
        return $this->_searchRes;
    }

    function getItems($ids=array()) {
        $res = array();
        if($ids === array() && count($this->_searchRes) < 1) $ids = $this->getSearch();
        if (count($ids) > 0 && $ids !== false) foreach ($ids as $id) {
            $res[] = $this->getItem($id);
        }
        return $res;
    }
    
    function _getItem($id=0) {
        if ($id === 0) $id = $this->itemid;
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if(isset($object->id)) return $object;
        return FALSE;
    }

    function _getItemBasics($id=0) {
        if ($id === 0) $id = $this->itemid;
        $fields = array(
            'id',
            'sort',
            'category',
            'status',
            'name',
            'tagline',
            'alias',
            'taxes',
            'price',
            'type',
            'sku',
            'weight',
            'stock',
            'sale',
            'onsale',
            'height',
            'width',
            'depth',
            'manufacturer',
            'origin'
        );
        $object = $this->db->getObj($this->_table, $fields, array('id' => $id));
        if(isset($object->id)) return $object;
        return FALSE;
    }
    
    function getItem($id = 0) {
        if ($id === 0) $id = $this->itemid;
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (isset($object->id)) {
            //ITEM TABS
            $object->tab_html = "<ul class='spr_tabs'>";
            $class = 'class="spr_active_tab"';
            for ($i = 1; $i <= 10; $i++) {
                $active = 'tab' . $i . '_active';
                $name = 'tab' . $i . '_name';
                if (!isset($object->$active) || $object->$active != '1') continue;
                if($object->$name == '') $object->$name = JText::_('SPR_ITEM_TAB'.$i);
                $object->tab_html .= "<li rel='spr_tab_{$i}' {$class}>{$object->$name}</li>";
                $class = '';
            }
            $object->tab_html .= "</ul>";
            //GENERATE EMPTY OBJECT
        }
        else {
            $object = $this->getDefaultObject();
        }

        //GET PRODUCT TYPE
        $object->prodtype = sprProdTypes::_load($object->type);
        if(!isset($object->prodtype->id)) $object->prodtype = sprProdTypes::_getDefault();
        
        //GET CATEGORY NAME
        $object->category_name = sprCategories::_getName($object->category);
        $object->category_alias = sprCategories::_getAlias($object->category);
        
        //GET ADDITIONAL CATEGORIES
        $object->categories = sprCategoriesMap::_getCats($object->id);

        //CHECK T&C
        if($object->prodtype->params->tc === '0') {
            $object->prodtype->params->tc = sprConfig::_load('core')->tc;
        }
        $object->tc = $object->prodtype->params->tc;

        //GET DOWNLOADS
        $object->dls = array();
        if(class_exists('salesProItemDls')) {
            $i = new salesProItemDls($object->id);
            $object->dls = $i->getDls();
        }
        
        //EMPTY VARIANTS
        $object->valid_attr = array();
        $object->valid_attrs = array();
        $object->attributes = array();
        $object->variants = array();
        $object->variant_key = array();
        
        //BUILD VARIANT DATA
        if($object->prodtype->params->var === '1') {
            
            $object->price = 0;
            $object->stock = -1;

            //LOAD VARIANTS
            $object->variants = sprItemVariants::_load($object->id);
            
            //LOAD POTENTIAL ATTRIBUTES
            $attributes = sprItemAttributesMap::_loadMap($object->id);
            foreach($attributes as $a) {
                $object->attributes[] = sprAttributes::_load($a);
            }
            
            //CALCULATE VALID ATTRIBUTES
            foreach($object->variants as $n=>$v) {
                if($v->status !== '1') {
                    if($this->checkAdminStatus() !== TRUE) {
                        unset($object->variants[$n]);
                        continue;
                    }
                }
                $variant_key = array();
                foreach($v->attributes as $a) {
                    $variant_key['attr_'.$a->id] = $a->val_id;
                    $object->valid_attr[$a->id][] = $a->val_id;
                }
                $object->variant_key[$v->id] = json_encode($variant_key);
    
                //CALCULATE & FORMAT THE REGULAR XE PRICE FOR THIS VARIANT
                $v->xe_price = sprCurrencies::_toXe($v->price);
                $v->f_price = sprCurrencies::_format($v->xe_price);
                
                //CALCULATE & FORMAT THE ON SALE XE PRICE FOR THIS VARIANT
                if($v->onsale === '1') {
                    $v->xe_sale = sprCurrencies::_toXe($v->sale);
                    $v->f_sale = sprCurrencies::_format($v->xe_sale);
                } else {
                    $v->xe_sale = '';
                    $v->f_sale = '';
                }
                
                //ASSIGN CHEAPEST ATTRIBUTE PRICE AS THE ITEM PRICE
                $price = ($v->onsale === '1') ? $v->sale : $v->price;
                if($object->price === 0 || $price < $object->price) $object->price = $price;
                
                //FORMAT THE STOCK TEXT
                $v->stocktxt = '';
                if($object->prodtype->params->sm === '1') {
                    $v->stocktxt = sprItems::stockLevel($v->stock);
                }
                $v->button = $this->makeSaleButton($object->prodtype->params->sm,$v->stock);
            }
            
            //CALCULATE VALID ATTRIBUTE COMBINATIONS
            $object->valid_attrs = array();
            foreach($object->variants as $v) {
                $valids = array();
                foreach($v->attributes as $a) {
                    $valids[] = $a->val_id;
                }
                $object->valid_attrs[] = $valids;
            }
        }
        
        //GET IMAGES
        $i = new salesProItemImages($object->id);
        $object->images = $i->getImages();
        $object->mainimage = $i->getMainImage();
        //GET VIDEOS
        $i = new salesProItemVideos($object->id);
        $object->videos = $i->getVideos();

        //GET FAQs
        $i = new salesProItemFaqs($object->id);
        $object->faqs = $i->getFaqs();

        //CALCULATE THE XE PRICES
        $object->xe_price = sprCurrencies::_toXe($object->price);
        $object->xe_sale = sprCurrencies::_toXe($object->sale);

        //FORMAT THE ITEM PRICE
        $object->f_price = sprCurrencies::_format($object->xe_price);
        $object->f_sale = sprCurrencies::_format($object->xe_sale);

        //FORMAT THE STOCK LEVEL
        $object->stocktxt = '';
        if($object->prodtype->params->sm === '1') {
            $object->stocktxt = sprItems::stockLevel($object->stock);
        }

        //CREATE THE SALE BUTTON
        $object->button = $this->makeSaleButton($object->prodtype->params->sm,$object->stock);
        return $object;
    }
    function makeSaleButton($stockman,$stock) {
        //STOCK MANAGEMENT OPTIONS:
        //1 = stock management enabled
        //2 = stock management disabled
        
        //ITEM OUT OF STOCK
        //1 = display out of stock notice
        //2 = allow purchase
        
        //N.B. OUT OF STOCK IS DISPLAYED IF STOCK LEVEL IS -1 (i.e. NO ATTRIBUTES ARE DEFINED)
        if(((int)$stockman === 1 && (int)$stock === 0) || $stock < 0) {
            $allowcheckout = (int)sprConfig::_load('core')->stock_empty;
            if($allowcheckout === 1  || $stock < 0) return '<div class="spr_item_oos">'.JText::_('SPR_OUTOFSTOCK').'</div>';
        }
        return '<input class="spr_item_submit" type="submit" name="spr_submit" value="'.JText::_('SPR_BUYNOW').'" />';
    }
    
    function saveData($id = '', $array = array()) {
        
        //CONFORM POSTED DATA TO CORRECT TYPES
        $_POST = $this->sanitizeAll($_POST,'spr_items_');

        //FIX THE ALIAS
        if(isset($_POST['spr_id'])) {
            if(!empty($_POST['spr_items_name'])) {
                if(!isset($_POST['spr_items_alias']) || strlen(trim($_POST['spr_items_alias']))<1) {
                    $_POST['spr_items_alias'] = JFilterOutput::stringURLSafe($_POST['spr_items_name']);
                } 
            }
        }

        if($id === '') {
            //FIX SORTING
            $id = (int)$_POST['spr_id'];
            $db = JFactory::getDBO();
            $query = 'UPDATE '.$db->quoteName($this->_table).' SET '.$db->quoteName('sort').' = '.$db->quoteName('sort').' + 1';
            $db->setQuery($query);
            $db->query();
            //ADD TIMESTAMP TO NEW ITEMS
            if($id === 0) $_POST['spr_items_added'] = time();
        }
        $id = parent::saveData($id, $array);

        //ITEM SAVE -- UPDATE THE CATEGORY MAP
        $categories = (isset($_POST['spr_categories'])) ? $_POST['spr_categories'] : array();
        if(!in_array($_POST['spr_items_category'], $categories)) $categories[] = $_POST['spr_items_category'];
        sprCategoriesMap::_saveCats($id,$categories);
        
        //ITEM SAVE -- UPDATE THE VARIANTS MAP
        sprItemVariants::_assignNewItemAttributes($id);
        
        //ITEM SAVE -- UPDATE THE IMAGES & OPTIONS ETC
        foreach (array('videos', 'faqs', 'dls', 'images', 'variants') as $a) {
            $atable = '#__spr_item_' . $a;
            $this->db->updateData($atable, array('item_id' => $id), array('item_id' => 0));
        }
        foreach (array('attributes_map') as $a) {
            $atable = '#__spr_item_' . $a;
            $this->db->updateData($atable, array('item' => $id), array('item' => 0));
        }
                
        //ERROR CHECKING (N.B. AFTER INTIAL SAVE - EASY WAY TO REPOST DATA)
        if(isset($_POST['spr_id'])) {
            $save = 1;
            if(empty($_POST['spr_items_name'])) {
                $this->showMsg(JText::_('SPR_ITEM_ERROR_NAME'));
                $save = 0;
            }
            if(!empty($_POST['spr_items_sku'])) {
                if(!$this->checkSKU($_POST['spr_items_sku'],$id)) {
                    $this->showMsg(JText::_('SPR_ITEM_ERROR_SKU'));
                    $save = 0;
                }
            }
            if($save !== 1) {
                $this->redirect('index.php?option=com_salespro&view=items&layout=edit&id='.$id);
                return FALSE;
            }
        }
        
        //REDIRECT TO SAME PAGE ON APPLY
        $task = JRequest::getVar('task');
        if($task === 'apply') {
            $this->redirect('index.php?option=com_salespro&view=items&layout=edit&id='.$id,JText::_('SPR_ADMIN_DATA_SAVED'));
        }
        
        return $id;
    }
    
    function checkSKU($sku='',$id=0) {
        $sku = trim($sku);
        if(strlen($sku) === 0) return TRUE;
        $id = (int)$id;
        $res = $this->db->getObjList($this->_table,'id',array('sku'=>$sku));
        if(count($res) === 0) return TRUE;
        elseif(count($res) === 1 && (int)$res[0]->id === $id) return TRUE;
        return FALSE;
    }
    
    function editTask() {
        //DELETE UNUSED ITEM ELEMENTS
        foreach (array('videos', 'faqs', 'dls', 'images', 'variants') as $a) {
            $atable = '#__spr_item_' . $a;
            $this->db->deleteData($atable, array('item_id' => 0));
        }
        foreach (array('attributes_map') as $a) {
            $atable = '#__spr_item_' . $a;
            $this->db->deleteData($atable, array('item' => 0));
        }
        //DELETE UNUSED ITEM VARIANTS
        $variants = sprItemVariants::_load(0);
        if(count($variants)>0) foreach($variants as $v) {
            sprItemVariants::_deleteVariant($v->id);
        }
    }
    
    public function getItemBasics($id) {
        $id = (int)$id;
        if ($id === 0) $id = $this->itemid;
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        return $object;
    }

    function addItem($item, $quantity) {
        $item = (int)$item;
        $quantity = (int)$quantity;
        $data = $this->getItemBasics($item);
        if ($data->stock === '1') { //STOCK MANAGEMENT IS ENABLED
            $newquantity = $data->stock_level + $quantity;
            $this->db->updateData($this->_table, array('stock_level' => $newquantity), array('id' => $item));
            return $newquantity;
        }
    }
}

class sprItems implements salesProFactory {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($id=0) {
        static $items = NULL;
        $class = new salesProItems;
        if($id > 0) return $class->getItem($id);
        else {
            if(NULL === $items) {
                $items = $class->getItems();
            }
            return $items;
        }
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProItems;
        return $class->selectOptions($selected,$options,$text);
    }
    
    /* FACTORY METHOD TO GET ALL FEATURED ITEMS */
    public static function getFeatured($catid = 0) {
        $class = new salesProItems;
        $ids = sprItems::getFeaturedIds($catid);
        $featured = $class->getItems($ids);
        return $featured;
    }
    
    /* FACTORY METHOD TO GET ALL FEATURED ITEM IDS */
    public static function getFeaturedIds($catid = 0, $page = 0, $limit = 20) {
        $class = new salesProItems;
        $where = array();
        $where[] = array('z.featured'=>'1');
        $where[] = array('z.status'=>'1');
        //if ($catid > 0) $where[] = array('z.category_id' => '"'.$catid.'"');
        $order = array('sort' => 'z.sort', 'dir' => 'ASC', 'limit' => $limit, 'page' => $page);
        $ids = $class->getSearch($class->_table, $where, array(),$order);
        return $ids;
    }
    
    /* FACTORY METHOD TO GET RANDOM ITEM IDS */
    public static function getRandomIds($limit = 20) {
        $class = new salesProItems;
        $where = array('z.status'=>'1');
        //if ($catid > 0) $where[] = array('z.category_id' => '"'.$catid.'"');
        $order = array('sort' => 'RAND()', 'dir' => 'ASC', 'limit' => $limit, 'page' => 0);
        $ids = $class->getSearch($class->_table, $where, array(),$order);
        return $ids;
    }
    
    /* FACTORY METHOD TO GET NEW ITEM IDS */
    public static function getNewIds($catid = 0, $page = 0, $limit = 2) {
        $class = new salesProItems;
        $where = array();
        $where[] = array('z.status'=>'1');
        //if ($catid > 0) $where[] = array('z.category_id' => '"'.$catid.'"');
        $order = array('sort' => 'z.added', 'dir' => 'DESC', 'limit' => $limit, 'page' => $page);
        $ids = $class->getSearch($class->_table, $where, array(),$order,1);
        return $ids;
    }

    /* FACTORY METHOD TO COUNT ITEMS IN A CATEGORY */
    public static function _count($catid = 0, $associated = 0) {
        $class = new salesProItems;
        //IF WE ARE IN THE MAIN SITE HIDE UNPUBLISHED
        $app = JFactory::getApplication();
        $where = array();
        $joins = array();
        if ($app->isSite()) $where['z.status'] = '1';
        
        if((int) $associated === 0 && (int)$catid !== 0) {
            $where[] = array('z.category' => $catid);
            $joins = array();
        } elseif ((int) $catid !== 0) {
            $where[] = array('m.category' => $catid, 'z.category' => $catid);
            $joins[] = array('LEFT', '#__spr_categories_map', 'm', 'z.id', 'm.item');
        }
        return $class->getCount($class->_table, $where, $joins);
    }
    
    /* FACTORY METHOD TO GET NEW ITEM IDS */
    public static function getByCatID($catid = 0, $order = array()) {
        $class = new salesProItems;
        $where = array();
        $where['z.status'] = 1;
        if($catid > 0) $where[] = array('m.category' => $catid, 'z.category' => $catid);
        $ids = $class->getSearch($class->_table, $where, array(),$order);
        return $ids;
    }
    
    /* FACTORY METHOD TO GET COMPLEX CATEGORY ITEMS */
    public static function _getCatItems($categories = array(),$order = array()) {

        $class = new salesProItems;
        $where = array('z.status' => 1);
        if(count($categories)>0) $where['m.category'] = $categories;
        
        $ids = $class->getSearch($class->_table, $where, array(),$order);
        $total = $class->order['total'];
        
        return array('total' => $total,'ids' => $ids);
    }
    
    /* FACTORY METHOD TO RETURN BASIC ITEM INFORMATION */
    public static function _loadBasics($id, $withimg = 1) {
        $class = new salesProItems;
        $object = $class->_getItemBasics($id);
        if($withimg !== 0) {
            $object->mainimage = sprItemImages::_getMainImage($id);
        }
        if(isset($object->id)) return $object;
        return FALSE;
    }
    
    public static function _directLink($id=0,$name='',$alias='',$catid='',$cat_name='',$cat_alias='',$jroute = TRUE) {
        
        $name = JFilterOutput::stringURLSafe($name);
        $alias = JFilterOutput::stringURLSafe($alias);
        $cat_name = JFilterOutput::stringURLSafe($cat_name);
        $cat_alias = JFilterOutput::stringURLSafe($cat_alias);

        $direct_link = 'index.php?option=com_salespro&view=item&id=' . $id;
        if(!empty($cat_alias)) $direct_link .= '&category='.$cat_alias;
        else $direct_link .= '&category='.$cat_name;
        if(!empty($alias)) $direct_link .= '&name='.$alias;
        else $direct_link .= '&name='.$name;
        if(!empty($catid)) $direct_link .= '&catid='.$catid;
        if($jroute === TRUE) $direct_link = JRoute::_($direct_link);
        return $direct_link;
    }
    
    /* FACTORY METHOD TO FORMAT THE STOCK LEVEL TEXT FOR ITEM PAGE */
    public static function stockLevel($stock=0) {
        $text = '';
        if ($stock < 1) {
            $text = JText::_('SPR_OUTOFSTOCK');
        } else {
            foreach (array('10', '100', '1000') as $amt) {
                if ($stock >= $amt) $text = $amt . '+';
            }
            $text .= JText::_('SPR_INSTOCK');
        }
        return $text;
    }

    /* FACTORY METHOD TO SET STOCK FOR A SPECIFIC ITEM */
    public static function setStock($item_id,$stock) {
        $class = new salesProItems;
        if($stock < 0) $stock = 0;
        $class->setVar($item_id, array('stock'=>$stock));
    }
    
    /* /// FACTORY FUNCTION TO ADD STOCK FOR A SPECIFIC ITEM E.G. AFTER A REFUND /// */
    public static function addStock($item_id, $quantity, $variant_id=0) {
        $item = sprItems::_loadBasics($item_id);
        if((int)$variant_id > 0) {
            if(!$variant = sprItemVariants::_getVar($variant_id)) return FALSE;
            if($variant->item_id != $item_id) return FALSE;
            $newquantity = $variant->stock + $quantity;
            sprItemVariants::setStock($variant->id,$newquantity);
        } else {
            $newquantity = $item->stock + $quantity;
            sprItems::setStock($item->id,$newquantity);
        }
        return $newquantity;
    }
    
    /* /// FACTORY FUNCTION TO REMOVE STOCK FOR A SPECIFIC ITEM E.G. DURING CHECKOUT /// */
    public static function removeStock($item_id,$quantity,$variant_id=0) {

        $item = sprItems::_loadBasics($item_id);
        if((int)$variant_id > 0) {
            if(!$variant = sprItemVariants::_getVar($variant_id)) return FALSE;
            if($variant->item_id != $item_id) return FALSE;
            $newquantity = $variant->stock - $quantity;
            if($newquantity < 0) $newquantity = 0;
            sprItemVariants::setStock($variant->id,$newquantity);
        } else {
            $newquantity = $item->stock - $quantity;
            if($newquantity < 0) $newquantity = 0;
            sprItems::setStock($item->id,$newquantity);
        }
        return $newquantity;
        
        
        //MAKE SURE THE QUANTITY IS REMOVED FOR ITEM AND/OR VARIANT!
        //ALSO MAKE SURE THAT THE CART CHECKS FOR STOCK LEVEL BEFORE COMPLETING CHECKOUT & UPDATES CART AS NEEDED
        //ALSO MAKE SURE THE BELOW CORRECTLY USES PRODTYPE PARAMETERS
        //ALSO REPEAT FOR ADD ITEM FUNCTIONALITY BELOW
    }
}