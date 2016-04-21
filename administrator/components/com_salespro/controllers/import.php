<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2015 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

require_once(JPATH_ADMINISTRATOR.'/components/com_salespro/controllers/controller.php');

class salesProImportController extends salesProControllerAdmin {
    
    public $params = array();
    public $max_upload = 0;
    public $max_post = 0;
    public $memory_limit = 0;
    public $upload_limit = 0;
    public $speed = 30;
    public $start = 0;
    public $delay = 0; //MAKE THIS 2 FOR PRODUCTION
    public $dbFile = '';
    public $dbPrefix = 'sprtmp_';
    public $json = array('progress'=> 0, 'point' => '', 'text' => 'Please wait...', 'error' => '');
    public $calcTables = array(
        'vm_category_xref' => 'calc_catXref',
        'vm_order_user_info' => 'import_orderUserInfo',
        'vm_product_category_xref' => 'import_productCats',
        //'vm_product_price' => 'import_productPrices',
        'vm_shipping_rate' => 'import_shippingRates',
        'virtuemart_products_en_gb' => 'import_VM2productDetails',
        'virtuemart_product_categories' => 'import_VM2productCategories',
        'virtuemart_product_medias' => 'import_VM2productImages',
        'virtuemart_userinfos' => 'import_VM2orderUserInfo',
        'virtuemart_shipmentmethods' => 'import_VM2shippingRates',
        'virtuemart_calc_countries' => 'import_VM2TaxCountries',
        'virtuemart_calc_states' => 'import_VM2TaxStates',
    );
    private $_fileTypes = array('sql', 'gz');
    public $map = array(
        'vm_category' => array(
            'table' => 'spr_categories',
            'data' => array(
                'category_id' => '@id',
                'vendor_id' => 0,
                'category_name' => '@name',
                'category_description' => '@desc',
                'category_thumb_image' => 0,
                'category_full_image' => '@image',
                'category_publish' => '%YNstatus',
                'status' => '&category_publish',
                'cdate' => 0,
                'mdate' => 0,
                'category_browsepage' => 0,
                'products_per_row' => 0,
                'category_flypage' => 0,
                'list_order' => 0
            )
        ),
        'vm_orders' => array(
            'table' => 'spr_sales',
            'data' => array(
                'order_id' => '@id',
                'vendor_id' => 0,
                'order_number' => 0,
                'user_info_id' => 0,
                'order_total' => '@grandtotal', //'@grand_total',
                'order_subtotal' => '@price',
                'order_tax' => 0, //'@tax',
                'order_tax_details' => '%calc_taxDetails',
                'order_shipping' => 0, //'@shipping',
                'order_shipping_tax' => 0,
                'coupon_discount' => 0,
                'coupon_code' => 0,
                'order_discount' => 0,
                'order_currency' => '%calc_currencyCode',
                'order_status' => '%calc_orderStatus',
                'cdate' => '%calc_orderDate',
                'mdate' => 0,
                'ship_method_id' => '%calc_orderShipMethod',
                'customer_note' => '@note',
                'ip_address' => '@ip',
                'gross_price' => 0 //'^getGrossPrice',
            )
        ),
        'vm_order_item' => array(
            'table' => 'spr_sales_items',
            'data' => array(
                'order_item_id' => '@id',
                'order_id' => '@sales_id',
                'user_info_id' => 0,
                'vendor_id' => 0,
                'product_id' => '@item_id',
                'order_item_sku' => '@sku',
                'order_item_name' => '@name',
                'product_quantity' => '^setOrderQuantity',
                'product_item_price' => '@price',
                'product_final_price' => 0,
                'order_item_currency' => 0,
                'order_status' => 0,
                'cdate' => 0,
                'mdate' => 0,
                'product_attribute' => 0, //'%getOrderOption',
                'tax' => '^getOrderItemTax',
                'category' => '^getOrderItemCategory',
                'type' => '!1',
            )
        ),
        'users' => array(
            'table' => 'users',
            'data' => array(
                'usertype' => 0,
                'gid' => 0,
                'params'=> '%getJson'
            )
        ),
        'user_notes' => array(
            'table' => 'user_notes',
            'data' => array()
        ),
        'user_profiles' => array(
            'table' => 'user_profiles',
            'data' => array()
        ),
        'user_usergroup_map' => array(
            'table' => 'user_usergroup_map',
            'data' => array()
        ),
        'vm_product' => array(
            'table' => 'spr_items',
            'data' => array(
                'product_full_image' =>  '^add_prodImage',
                'product_id' => '@id',
                'vendor_id' => 0,
                'product_parent_id' => 0,
                'product_sku' => '@sku',
                'product_s_desc' => '@mini_desc',
                'product_desc' => '@full_desc',
                'product_thumb_image' => 0,
                'product_publish' => '%YNstatus',
                'status' => '&product_publish',
                'product_weight' => '@weight',
                'product_weight_uom' => 0,
                'product_length' => '@depth',
                'product_width' => '@width',
                'product_height' => '@height',
                'product_lwh_uom' => 0,
                'product_url' => 0,
                'product_in_stock' => '@stock',
                'product_available_date' => 0,
                'product_availability' => 0,
                'product_special' => 0, //IS THIS A DISCOUNTED ITEM - POSSIBLY ADD IN FUTURE
                'product_discount_id' => 0, //WHAT IS THE DISCOUNT - POSSIBLY ADD IN FUTURE
                'ship_code_id' => 0,
                'cdate' => '@added',
                'mdate' => 0,
                'product_name' => '@name',
                'product_sales' => 0, //HOW MANY SALES - POSSIBLY ADD IN FUTURE
                'attribute' => 0, //POTENTIALY ADD IN THE FUTURE TO THE ITEMOPTIONS TABLE
                'custom_attribute' => 0,
                'product_tax_id' => '%getProductTaxDetails', //INTEGRATE THIS AS ITEM GROUPS IN FUTURE
                'product_unit' => 0,
                'product_packaging' => 0,
                'child_options' => 0,
                'quantity_options' => 0,
                'child_option_ids' => 0,
                'product_order_levels' => 0,
                'tab1_active' => '!1',
                'tab1_name' => '!Description', //CHANGE THIS TO ALLOW LANGUAGE-SPECIFIC!
                'tab2_active' => '!1',
                'tab2_name' => '!Images',
                'tab3_active' => '!2',
                'tab3_name' => '!Videos',
                'tab4_active' => '!2',
                'tab4_name' => '!FAQs',
                'tab5_active' => '!2',
                'tab5_name' => '!Specifications',
                'featured' => '!2',
                'type' => '!1',
            )
        ),
        'vm_product_files' => array(
            'table' => 'spr_item_images',
            'data' => array(
                'file_id' => '@id',
                'file_product_id' => '@item_id',
                'file_name' => '%get_imageName',
                'file_title' => 0,
                'file_description' => 0,
                'file_extension' => 0,
                'file_mimetype' => 0,
                'file_url' => 0,
                'file_published' => '@status',
                'file_is_image' => '%checkImg',
                'file_image_height' => 0,
                'file_image_width' => 0,
                'file_image_thumb_height' => 0,
                'file_image_thumb_width' => 0
            )
        ),
        'vm_shipping_carrier' => array(
            'table' => 'spr_shipping',
            'data' => array(
                'shipping_carrier_id' => '@id',
                'shipping_carrier_name' => '@name',
                'shipping_carrier_list_order' => '@sort',
                'status' => '!1',
                'paymentoptions' => '^getShipPaymentOptions'
            )
        ),
        'vm_tax_rate' => array(
            'table' => 'spr_taxes',
            'data' => array(
                'tax_rate_id' => '@id',
                'vendor_id' => 0,
                'tax_state' => 0,
                'tax_country' => '%getTaxCountry',
                'mdate' => 0,
                'tax_rate' => '%getTaxRate',
                'status' => '!1',
                'name' => '!Tax',
                'type' => '!1'
            )
        ),
        'virtuemart_calcs' => array(
            'table' => 'spr_taxes',
            'data' => array(
                'virtuemart_calc_id' => '@id',
                'virtuemart_vendor_id' => 0,
                'calc_jplugin_id' => 0,
                'calc_name' => '@name',
                'calc_descr' => 0,
                'calc_kind' => 0,
                'calc_value_mathop' => '^getVM2TaxType',
                'calc_value' => 0,
                'calc_currency' => 0,
                'calc_shopper_published' => 0,
                'calc_vendor_published' => 0,
                'publish_up' => 0,
                'publish_down' => 0,
                'for_override' => 0,
                'calc_params' => 0,
                'ordering' => 0,
                'shared' => 0,
                'published' => '@status',
                'created_on' => 0,
                'created_by' => 0,
                'modified_on' => 0,
                'modified_by' => 0,
                'locked_on' => 0,
                'locked_by' => 0,
                'tax_state' => 0
            )
        ),

        'virtuemart_categories_en_gb' => array(
            'table' => 'spr_categories',
            'data' => array(
                'virtuemart_category_id' => '@id',
                'category_name' => '@name',
                'category_description' => '@desc',
                'metadesc' => '@meta_desc',
                'metakey' => '@meta_keys',
                'customtitle' => '@meta_title',
                'slug' => '@alias',
                'status' => '!1',
                'details' => '^getVM2CategoryDetails'
            
            )
        ),
            
        'virtuemart_orders' => array(
            'table' => 'spr_sales',
            'data' => array(
                'virtuemart_order_id' => '@id',
                'virtuemart_user_id' => '@user_id',
                'virtuemart_vendor_id' => 0,
                'order_number' => 0,
                'customer_number' => 0,
                'order_pass' => 0,
                'order_total' => '@grandtotal', //@grand_total',
                'order_salesPrice' => 0, //'@gross_price',
                'order_billTaxAmount' => 0,
                'order_billTax' => '%calc_VM2taxdetails',
                'order_billDiscountAmount' => 0,
                'order_discountAmount' => 0,
                'order_subtotal' => '@price',
                'order_tax' => 0, //'@tax',
                'order_shipment' => '^calcVM2OrderShip',
                'order_shipment_tax' => 0,
                'order_payment' => '^calcVM2OrderPay',
                'order_payment_tax' => 0,
                'coupon_discount' => 0,
                'coupon_code' => 0,
                'order_discount' => 0, //'@discount',
                'order_currency' => 0,
                'order_status' => '%calc_orderStatus',
                'user_currency_id' => '%calc_VM2currencyCode',
                'user_currency_rate' => 0,
                'virtuemart_paymentmethod_id' => 0, //'@payment_id',
                'virtuemart_shipmentmethod_id' => 0, //'@shipping_id',
                'customer_note' => '@note',
                'delivery_date' => 0,
                'order_language' => 0,
                'ip_address' => '@ip',
                'created_on' => '@date',
                'created_by' => 0,
                'modified_on' => 0,
                'modified_by' => 0,
                'locked_on' => 0,
                'locked_by' => 0,
                'quantity' => '!0',
                'weight' => '!0',
            )
        ),
        'virtuemart_order_items' => array(
            'table' => 'spr_sales_items',
            'data' => array(
                'virtuemart_order_item_id' => '@id',
                'virtuemart_order_id' => '@sales_id',
                'virtuemart_vendor_id' => 0,
                'virtuemart_product_id' => '@item_id',
                'order_item_sku' => '@sku',
                'order_item_name' => '@name',
                'product_quantity' => '^setOrderQuantity',
                'product_item_price' => '@price',
                'product_priceWithoutTax' => 0,
                'product_tax' => 0,
                'product_basePriceWithTax' => 0,
                'product_discountedPriceWithoutTax' => 0,
                'product_final_price' => 0,
                'product_subtotal_discount' => 0,
                'product_subtotal_with_tax' => 0,
                'order_item_currency' => 0,
                'order_status' => 0,
                'product_attribute' => 0, //'%getOrderOption',
                'delivery_date' => 0,
                'created_on' => 0,
                'created_by' => 0,
                'modified_on' => 0,
                'modified_by' => 0,
                'locked_on' => 0,
                'locked_by' => 0,
                'tax' => '^getOrderItemTax',
                'category' => '^getVM2OrderItemCategory',
                'type' => 0, //'!1',
            )
        ),
        'virtuemart_products' => array(
            'table' => 'spr_items',
            'data' => array(
                'virtuemart_product_id' => '@id',
                'virtuemart_vendor_id' => 0,
                'product_parent_id' => 0,
                'product_sku' => '@sku',
                'product_gtin' => 0,
                'product_mpn' => 0,
                'product_weight' => '@weight',
                'product_weight_uom' => 0,
                'product_length' => '@depth',
                'product_width' => '@width',
                'product_height' => '@height',
                'product_lwh_uom' => 0,
                'product_url' => 0,
                'product_in_stock' => '@stock', //@stock_level',
                'product_ordered' => 0,
                'low_stock_notification' => 0,
                'product_available_date' => 0, //@stock_date',
                'product_availability' => 0,
                'product_special' => 0, //IS THIS A DISCOUNTED ITEM - POSSIBLY ADD IN FUTURE
                'product_discount_id' => 0, //WHAT IS THE DISCOUNT - POSSIBLY ADD IN FUTURE
                'product_sales' => 0, //HOW MANY SALES - POSSIBLY ADD IN FUTURE
                'product_unit' => 0,
                'product_packaging' => 0,
                'product_params' => 0, //CHECK WHAT THIS IS
                'hits' => 0,
                'intnotes' => 0,
                'metarobot' => 0,
                'metaauthor' => 0,
                'layout' => 0,
                'published' => '%YNstatus',
                'status' => '&published', //CHECK THIS
                'pordering' => '@sort',
                'created_on' => '@added',
                'created_by' => 0,
                'modified_on' => 0,
                'modified_by' => 0,
                'locked_on' => 0,
                'locked_by' => 0,
                'tab1_active' => '!1',
                'tab2_active' => '!1',
                'tab3_active' => '!2',
                'tab4_active' => '!2',
                'tab5_active' => '!2',
                'featured' => '!2',
                'type' => '!1',
            ),
        ),
        'virtuemart_shipmentmethods_en_gb' => array(
            'table' => 'spr_shipping',
            'data' => array(
                'virtuemart_shipmentmethod_id' => '@id',
                'shipment_name' => '@name',
                'shipment_desc' => '@info',
                'slug' => '@alias',
                'status' => '!1',
                'paymentoptions' => '^getShipPaymentOptions',
            )
        ),
        'virtuemart_product_customfields' => array(
            'table' => 'spr_item_options',
            'data' => array(
                'virtuemart_customfield_id' => '@id',
                'virtuemart_product_id' => '@item_id',
                'virtuemart_custom_id' => '^getVM2OptionGroup',
                'custom_value' => '@name',
                'custom_price' => '@price',
                'custom_param' => 0,
                'published' => 0,
                'created_on' => 0,
                'created_by' => 0,
                'modified_on' => 0,
                'modified_by' => 0,
                'locked_on' => 0,
                'locked_by' => 0,
                'ordering' => '@sort'
            )
        )
    );
    
    function getVM2OptionGroup($data) {
        //GET THE OPTION GROUP DATA
        $query = "SELECT `custom_title` FROM `{$this->dbPrefix}virtuemart_customs` WHERE `virtuemart_custom_id` = '{$data['virtuemart_custom_id']}' LIMIT 1";
        $this->db->setQuery($query);
        $customs = $this->db->loadAssoc();
        $group['id'] = 0;
        if(isset($customs['custom_title'])) {
            $mytitle = $customs['custom_title'];
            $query = "SELECT `id` FROM `#__spr_item_optiongroups` WHERE `item_id` = '{$data['virtuemart_product_id']}' AND `name` = '{$mytitle}' LIMIT 1";
            $this->db->setQuery($query);
            $group = $this->db->loadAssoc($query);
            if(!isset($group['id'])) {
                $query = "INSERT INTO `#__spr_item_optiongroups` SET `item_id` = '{$data['virtuemart_product_id']}', `name` = '{$customs['custom_title']}', `type` = '1', `sort` = '0'";
                $this->db->setQuery($query);
                $this->db->query();
                $group['id'] = (int)$this->db->insertid();
            }
        }
        return array('group_id' => $group['id']);
    }

    function import_VM2TaxCountries($data) {
        foreach($data as $d) {
            $query = "SELECT `regions` FROM `#__spr_taxes` WHERE `id` = '{$d['virtuemart_calc_id']}' LIMIT 1";
            $this->db->setQuery($query);
            $regions = $this->db->loadResult();
            if(count($regions)<1) continue;
            $regions = (array)json_decode($regions);
            $regions[] = $d['virtuemart_country_id'];
            $regions = json_encode($regions);
            $query = "UPDATE `#__spr_taxes` SET `regions` = '{$regions}' WHERE `id` = '{$d['virtuemart_calc_id']}' LIMIT 1";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }    
    function import_VM2TaxStates($data) {
        foreach($data as $d) {
            $query = "SELECT `regions` FROM `#__spr_taxes` WHERE `id` = '{$d['virtuemart_calc_id']}' LIMIT 1";
            $this->db->setQuery($query);
            $regions = $this->db->loadResult();
            if(count($regions)<1) continue;
            $regions = (array)json_decode($regions);
            $regions[] = $d['virtuemart_state_id'];
            $regions = json_encode($regions);
            $query = "UPDATE `#__spr_taxes` SET `regions` = '{$regions}' WHERE `id` = '{$d['virtuemart_calc_id']}' LIMIT 1";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
    
    function getVM2TaxType($data) {
        $value = $data['calc_value'];
        switch($data['calc_value_mathop']) {
            case '+':
                $type = '2';
                break;
            case '+%':
                $type = '1';
                break;
            case '-':
                return FALSE;
                break;
            case '-%':
                return FALSE;
                break;
            default:
                $type = '1';
                break;
        }
        return array('value' => $value, 'type' => $type);
    }

    function import_VM2shippingRates($data) {
        
        //GET ALL REGIONS
        $query = "SELECT `id` FROM `#__spr_regions`";
        $this->db->setQuery($query);
        $regions = $this->db->loadAssocList();
        $saveregions = array();
        foreach($regions as $r) {
            $saveregions[] = $r['id'];
        }
        $regions = json_encode($saveregions);
        
        foreach($data as $d) {

            //DELETE OLD SHIPPING RULES
            $query = "DELETE FROM `#__spr_shippingrules` WHERE `id` = '{$d['virtuemart_shipmentmethod_id']}'";
            $this->db->setQuery($query);
            $this->db->query();
        
            $d['shipment_params'] = 'shipment_logos=""|show_on_pdetails="1"|countries=["5","7","9","11","13"]|zip_start="123"|zip_stop="234"|weight_start="165"|weight_stop="988"|weight_unit="LB"|nbproducts_start=1|nbproducts_stop=43|orderamount_start="1"|orderamount_stop="23"|cost="50"|package_fee="2.49"|tax_id="0"|free_shipment="500"|';
            
            //GET THE SHIPPING RULES
            $rules = explode('|', $d['shipment_params']);
            $rule = array(
                'countries' => '',
                'weight_start' => '',
                'weight_stop' => '',
                'nbproducts_start' => '',
                'nbproducts_stop' => '',
                'orderamount_start' => '',
                'orderamount_stop' => '',
                'cost' => '',
                'package_fee' => ''
            );
            foreach($rules as $r) {
                $string = explode('=',$r);
                if(count($string)<2) continue;
                $rule[$string[0]] = trim(str_replace('"', "", $string[1]));
            }
            if(strlen($rule['countries'])<1) $rule['countries'] = $regions;
            
            $price = $rule['cost'] + $rule['package_fee'];

            //ADD NEW SHIPPING RULES
            $query = "INSERT INTO `#__spr_shippingrules` SET `id` = '', `regions` = '{$rule['countries']}', `shipping_id` = '{$d['virtuemart_shipmentmethod_id']}', `start_weight` = '{$rule['weight_start']}', `end_weight` = '{$rule['weight_stop']}', `start_items` = '{$rule['nbproducts_start']}', `end_items` = '{$rule['nbproducts_stop']}', `start_price` = '{$rule['orderamount_start']}', `end_price` = '{$rule['orderamount_stop']}', `price` = '{$price}'";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
    
    function import_VM2orderUserInfo($data) {
        $regions = new salesProRegions;
        $sales = new salesProSales;
        foreach($data as $d) {
            
            //GET STATE AND COUNTRY SalesPro IDs
            $query = "SELECT `state_3_code` FROM `{$this->dbPrefix}virtuemart_states` WHERE `virtuemart_state_id` = '{$d['virtuemart_state_id']}'";
            $this->db->setQuery($query);
            $state = $this->db->loadResult();
            $query = "SELECT `country_3_code` FROM `{$this->dbPrefix}virtuemart_countries` WHERE `virtuemart_country_id` = '{$d['virtuemart_country_id']}'";
            $this->db->setQuery($query);
            $country = $this->db->loadResult();
            
            $savedata = array();
            //$savedata['user_email'] = $d['user_email']; !NEED TO ADD EMAIL ON USER IMPORT!
            $name = $d['title'].' '.$d['first_name'].' '.$d['last_name'];
            $savedata['user_bill_name'] = $name;
            $savedata['user_del_name'] = $name;
            $savedata['user_bill_address'] = $d['address_1'];
            $savedata['user_bill_address2'] = $d['address_2'];
            $savedata['user_bill_town'] = $d['city'];
            $mystate = $regions->db->getAssoc($regions->_table,'id',array('code_3'=>$state));
            $savedata['user_bill_state'] = (isset($mystate['id'])) ? (int) $mystate['id'] : 0;
            $mycountry = $regions->db->getAssoc($regions->_table,'id',array('code_3'=>$country));
            $savedata['user_bill_country'] = (isset($mycountry['id'])) ? (int) $mycountry['id'] : 0;
            $savedata['user_bill_postcode'] = $d['zip'];
            $savedata['user_bill_phone'] = $d['phone_1'];
            if(isset($mystate['id'])) $region_id = (int)$mystate['id'];
            elseif (isset($mycountry['id'])) $region_id = (int) $mycountry['id'];
            else $region_id = 0;
            $savedata['user_bill_region_id'] = $region_id;
            foreach(array('name','address','address2','town','state','country','postcode','phone','region_id') as $x) {
                if(isset($savedata['user_bill_'.$x])) $savedata['user_del_'.$x] = $savedata['user_bill_'.$x];
            }
            
            //SAVE THE ORDER DETAILS
            $sales->db->updateData($sales->_table,$savedata,array('user_id' => $d['virtuemart_user_id']));
        }
    }
    
    function import_VM2productDetails($data) {
        $map = array(
            'product_s_desc' => 'mini_desc',
            'product_desc' => 'full_desc',
            'product_name' => 'name',
            'product_special' => 'featured',
            'metadesc' => 'meta_desc',
            'metakey' => 'meta_keys',
            'customtitle' => 'meta_title',
            'slug' => 'alias'
        );
        foreach($data as $d) {
            $n = 0;
            $savedata = '';
            foreach($d as $key=>$val) {
                if(!array_key_exists($key,$map)) continue;
                $newkey = $map[$key];
                if($n++ > 0) $savedata .= ',';
                $savedata .= $this->db->quoteName($newkey).'='.$this->db->quote($val);
            }
            $query = "UPDATE `#__spr_items` SET {$savedata} WHERE `id` = '{$d['virtuemart_product_id']}' LIMIT 1";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
    
    
    function import_VM2productImages($data) {
        foreach($data as $d) {
            $query = "SELECT * FROM `{$this->dbPrefix}virtuemart_medias` WHERE `virtuemart_media_id` = '{$d['virtuemart_media_id']}' LIMIT 1";
            $this->db->setQuery($query);
            $image = $this->db->loadAssoc();
            
            if(isset($image['virtuemart_media_id'])) {
                $file = $this->get_imageName($image['file_title']);
                $savedata = array(
                    'item_id' => $d['virtuemart_product_id'],
                    'name' => $file['name'],
                    'ext' => $file['ext'],
                    'status' => $image['published'],
                    'sort' => $d['virtuemart_media_id'],
                    'date' => $image['created_on'],
                );
                $x = new salesProItemImages;
                $x->saveData(0,$savedata);
            }
        }
    }

    function import_VM2productCategories($data) {
        $tables = $this->getTables();
        foreach($data as $d) {
            $myprods[$d['virtuemart_product_id']][] = $d['virtuemart_category_id'];
        }
        if(count($myprods)>0) foreach($myprods as $id=>$cats) {
            $mycats = json_encode($cats);
            $query = "UPDATE `#__spr_items` SET `category` = '{$mycats}' WHERE `id` = '{$id}' LIMIT 1";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
    
    function calc_VM2taxdetails($data) {
        $data = json_decode($data);
        $ret = array();
        $tax_id = array();
        foreach($data as $d) {
            $tax = round($d->result,2);
            $ret[] = array(
                'id' => $d->virtuemart_calc_id,
                'regions' => array(),
                'name' => $d->calc_name,
                'type' => '1',
                'value' => $d->calc_value,
                'status' => '1',
                'region' => '',
                'tax' => $d->result,
                'tax_formatted' => $tax
            );
            $tax_id[] = $d->virtuemart_calc_id;
        }
        $ret = json_encode($ret);
        $tax_id = json_encode($tax_id);
        return array('tax_details' => $ret);
    }
    
    function calc_VM2currencyCode($data) {
        $query = "SELECT `currency_code_3` FROM `{$this->dbPrefix}virtuemart_currencies` WHERE `virtuemart_currency_id` = '{$data}' LIMIT 1";
        $this->db->setQuery($query);
        $code = $this->db->loadAssoc();
        if(isset($code['currency_code_3'])) return $this->calc_currencyCode($code['currency_code_3']);
        else return array();
    }
    
    function calcVM2OrderShip($data) {
        $ship = array(
            'id' => $data['virtuemart_shipmentmethod_id'],
            'price' => $data['order_shipment'],
            'f_price' => $data['order_shipment'],
            'xe_price' => $data['order_shipment']
        );
        $ship = json_encode($ship);
        return array('shipping_details' => $ship);
    }
    
    function calcVM2OrderPay($data) {
        $pay = array(
            'id' => 0,
            'payment_method' => 0,
            'name' => '',
            'fee' => $data['order_payment'],
            'fee_type' => ''
        );
        $pay = json_encode($pay);
        return array('payment_details'  => $pay);
        /* ADD PAYMENT TO SALES TABLE!! */
    }
    
    function getVM2CategoryDetails($data) {
        $id = (int)$data['virtuemart_category_id'];
        //GET CATEGORY PARAMETERS
        $query = "SELECT * FROM `{$this->dbPrefix}virtuemart_categories` WHERE `virtuemart_category_id` = '{$id}' LIMIT 1";
        $this->db->setQuery($query);
        $cat = $this->db->loadAssoc();
        $ret = array();
        $params = array();
        $params['layout'] = '0';
        $params['show_title'] = '1';
        $params['show_desc'] = '1';
        $params['show_image'] = '1';
        $params['show_sortbar'] = '1';
        $params['show_pagesbar'] = '1';
        $params['subecategory_items'] = '1';
        $params['items'] = '6';
        $params['subcategory_levels'] = '1';
        if(sizeof($cat)>0) {
            $params['boxcols'] = $cat['products_per_row'];
            $ret['status'] = ($cat['published'] == '1') ? '1' : '2';
        }
        else {
            $params['boxcols'] = '3';
            $ret['status'] = '1';
        }
        $params = json_encode($params);
        $ret = array('params' => $params);
        
        //GET CATEGORY PARENTS
        $query = "SELECT * FROM `{$this->dbPrefix}virtuemart_category_categories` WHERE `category_child_id` = '{$id}' LIMIT 1";
        $this->db->setQuery($query);
        $cat = array();
        $cat = $this->db->loadAssoc();
//MIGHT NEED TO CALCULATE LEVEL HERE TOO...
        if(sizeof($cat)>0) {
            $ret['parent'] = $cat['category_parent_id'];
            $ret['sort'] = $cat['ordering'];
        } else {
            $ret['parent'] = 0;
            $ret['sort'] = 0;
        }
        
        //GET CATEGORY IMAGE
        $query = "SELECT `file_url` FROM `{$this->dbPrefix}virtuemart_medias` as m LEFT JOIN `{$this->dbPrefix}virtuemart_category_medias` as c ON (c.virtuemart_media_id = m.virtuemart_media_id) WHERE c.`virtuemart_category_id` = '{$id}' ORDER BY c.`ordering` ASC LIMIT 1";
        $this->db->setQuery($query);
        $cat = array();
        $cat = $this->db->loadAssoc();
        if(sizeof($cat)>0) {
            $file = $this->get_imageName($cat['file_url']);
            if(isset($file['name'])) {
                $ret['image'] = $file['name'].'.'.$file['ext'];
            }
        }
        return $ret;
    }
    
    function getShipPaymentOptions($data) {
                    
            //GET PAYMENT TYPES
            $class = new salesProPaymentOptions;
            $methods = $class->getPaymentOptions();
            $mymethods = array();
            if(count($methods)>0) foreach($methods as $m) {
                $mymethods[] = $m->id;
            }
            $mymethods = json_encode($mymethods);
            return array('paymentoptions' => $mymethods);
    }
    
    function getProductTaxDetails($data) {
        $res = array();
        $res[] = $data;
        return array('taxes' => json_encode($res));
    }
    
    function getTaxCountry($data) {
        $query = "SELECT `id` FROM `#__spr_regions` WHERE `code_3` = '{$data}' LIMIT 1";
        $this->db->setQuery($query);
        $region = $this->db->loadResult();
        $regions = array();
        $regions[] = $region;
        return array('regions' => json_encode($regions));
    }
    
    function getTaxRate($data) {
        $data = $data * 100;
        return array('value' => $data);
    }
    
    function getOrderItemCategory($data) {
        $productID = $data['product_id'];
        
        //GET PARENT
        $query = "SELECT `product_parent_id` FROM `{$this->dbPrefix}vm_product` WHERE `product_id` = '{$productID}'";
        $this->db->setQuery($query);
        $parentID = (int) $this->db->loadResult();
        if($parentID > 0) $productID = $parentID;
        
        //GET CAT ID
        $query = "SELECT `category_id` FROM `{$this->dbPrefix}vm_product_category_xref` WHERE `product_id` = '{$productID}'";
        $this->db->setQuery($query);
        $categories = $this->db->loadAssocList();
        $cats = array();
        $names = array();
        $class = new salesProCategories;
        if(count($categories)>0) foreach($categories as $c) {
            $cats[] = $c['category_id'];
            $category = $class->getCategory($c['category_id']);
            if(isset($category->name)) $names[] = $category->name;
        }
        $res = array('category_id' => json_encode($cats),'category_name'=>implode(',',$names));
        return $res;
    }
    
    function getVM2OrderItemCategory($data) {
        $productID = $data['virtuemart_product_id'];
        
        //GET PARENT
        $query = "SELECT `product_parent_id` FROM `{$this->dbPrefix}virtuemart_products` WHERE `virtuemart_product_id` = '{$productID}'";
        $this->db->setQuery($query);
        $parentID = (int) $this->db->loadResult();
        if($parentID > 0) $productID = $parentID;
        
        //GET CAT ID
        $query = "SELECT `virtuemart_category_id` FROM `{$this->dbPrefix}virtuemart_product_categories` WHERE `virtuemart_product_id` = '{$productID}'";
        $this->db->setQuery($query);
        $categories = $this->db->loadAssocList();
        $cats = array();
        $names = array();
        $class = new salesProCategories;
        if(count($categories)>0) foreach($categories as $c) {
            $cats[] = $c['virtuemart_category_id'];
            $category = $class->getCategory($c['virtuemart_category_id']);
            if(isset($category->name)) $names[] = $category->name;
        }
        $res = array('category_id' => json_encode($cats),'category_name'=>implode(',',$names));
        return $res;
    }
    
    function setOrderQuantity($data) {
        $quantity = $data['product_quantity'];
        $order_id = 0;
        if(isset($data['virtuemart_order_id'])) $order_id = (int) $data['virtuemart_order_id'];
        else if(isset($data['order_id'])) $order_id = (int) $data['order_id'];
        $query = "UPDATE `#__spr_sales` SET `quantity` = `quantity` + {$quantity} WHERE `id` = '{$order_id}'";
        $this->db->setQuery($query);
        $this->db->query();
        return array('quantity' => $quantity);
    }
    
    function getOrderItemTax($data) {
        $tax = $data['product_final_price'] - $data['product_item_price'];
        $details = array();
        $details[] = array('id' => '0', 'regions' => array(), 'name' => 'Tax', 'value' => '', 'status' => '1', 'region'=> '', 'tax'=> $tax, 'tax_formatted' => $tax);
        $details = json_encode($details);
        return array('tax' => $tax, 'tax_details' => $details);
    }
    
    function getOrderOption($data) {
        $data = explode(';',$data);
        $res = array();
        $opts = array();
        if(count($data)>0) foreach($data as $d) {
            $data2 = (array)json_decode($d);
            if(count($data2)>0) {
                $res = array_merge($res,$data2);
                $opts[] = key($data2);
            } else {
                $data2 = explode(':',$d);
                if(count($data2)>1) $res[$data2[0]] = $data2[1];
            }
        }
        $res = json_encode($res);
        $opts = json_encode($opts);
        return array('options' => $opts, 'option_details'=>$res);
    }
    
    function getGrossPrice($data) {
        $res = array();
        $res['gross_price'] = $data['order_tax'] + $data['order_subtotal'];
        return $res;
    }
    
    function checkImg($data) {
        if($data === '0') {
            return array('item_id' => 0);
        }
        return array('sort' => 0);
    }
    
    function get_imageName($data) {
        $info = pathinfo($data);
        if(isset($info['extension']) && isset($info['filename'])) return array('name' => $info['filename'],'ext' => $info['extension']);
        else return '';
    }
    
    function get_availDate($data) {
        $date = date("Y-m-j H:i:s",$data);
        return array('stock_date'=>$date);
    }
    
    function add_prodImage($data) {
        $image = new salesProItemImages;
        $img = $data['product_full_image'];
        if(strlen($img)<3) return array();
        $info = pathinfo($img);
        if(!isset($info['extension']) || !isset($info['filename'])) return array();
        $savedata = array(
            'item_id' => $data['product_id'],
            'name' => $info['filename'],
            'ext' => $info['extension'],
            'date' => $data['cdate'],
            'status' => '1'
        );
        $image->db->deleteData($image->_table,array('item_id'=>$data['product_id'],'name'=>$info['filename'],'ext'=>$info['extension']));
        $image->db->insertData($image->_table,$savedata);
        return array('tab2_active'=>'1','tab2_name'=>'Images');
    }

    
    function fixSorts() {
        $query = "UPDATE `#__spr_items` SET `sort` = `id`";
        $this->db->setQuery($query);
        $this->db->query();
        $query = "UPDATE `#__spr_categories` SET `sort` = `id`";
        $this->db->setQuery($query);
        $this->db->query();
    }
    
    function fix_prodPrice() {
        
        //GET THE MAIN PRICE FOR THE MASTER PRODUCT
        $tables = $this->getTables();
        if(!is_array($tables)) return;
        
        $tax_style = sprConfig::_load('core')->taxes;
        
        if(in_array($this->dbPrefix.'vm_product_price', $tables)) {
            $query = "SELECT p.`product_id`, p.`product_price`, i.`taxes` FROM `{$this->dbPrefix}vm_product_price` as p LEFT JOIN `#__spr_items` as i ON (i.id = p.product_id) WHERE i.id > 0";
            $this->db->setQuery($query);
            $prices = $this->db->loadAssocList();
            
            foreach($prices as $p) {
                
                $newprice = $p['product_price'];
                if($tax_style != '2') {
                    //ADJUST THE PRICE TO ACCOUNT FOR TAX
                    $taxes = json_decode($p['taxes']);
                    if(is_array($taxes)) $tax_id = (int)$taxes[0];
                    else $tax_id = (int)$taxes;
                    $mytax = sprTaxes::_load($tax_id);
                    $newprice = $newprice + sprTaxes::calculateTax($mytax,$newprice);
                    $newprice = round($newprice,2);
                }
                
                $query = "UPDATE `#__spr_items` SET `price` = '{$newprice}' WHERE `id` = '{$p['product_id']}' LIMIT 1";
                $this->db->setQuery($query);
                $this->db->query();
            }
        }
        
        if(in_array($this->dbPrefix.'virtuemart_product_prices', $tables)) {
            $query = "SELECT p.`virtuemart_product_id`, p.`product_price`, p.`product_tax_id` FROM `{$this->dbPrefix}virtuemart_product_prices` as p LEFT JOIN `#__spr_items` as i ON (i.id = p.virtuemart_product_id) WHERE i.id > 0";
            $this->db->setQuery($query);
            $prices = $this->db->loadAssocList();
            
            foreach($prices as $p) {
                
                $newprice = $p['product_price'];
                $tax_id = (int) $p['product_tax_id'];
                //ADJUST THE PRICE TO ACCOUNT FOR TAX
                if($tax_style != '2') {
                    if($tax_id > 0) {
                        $mytax = sprTaxes::_load($tax_id);
                        $newprice = $newprice + sprTaxes::calculateTax($mytax,$newprice);
                        $newprice = round($newprice,2);
                    }
                }
                $taxes = array();
                $taxes[] = $tax_id;
                $taxes = json_encode($taxes);
                
                $query = "UPDATE `#__spr_items` SET `price` = '{$newprice}', `taxes` = '{$taxes}' WHERE `id` = '{$p['virtuemart_product_id']}' LIMIT 1";
                $this->db->setQuery($query);
                $this->db->query();
            }
        }
    }


    function fix_prodOptions() {
        
        return FALSE; //DISABLED - THIS IS A HIGHLY COMPLEX BIT OF CODE THAT NEEDS REVISION
        
        //1: GET PRODUCTS THAT parent_id > 0
        //2: FOR EACH ONE, GET THE PARENT MAIN GROUP ATTRIBUTE_NAME & OPTION GROUP ID (IF EXISTS)
        //3: MOVE ALL ATTRIBUTE VALUES TO THE SINGLE OPTION GROUP ID (IN CSV)
        //4: MATCH UP IMAGES & SKUS FOR THAT PARTICULAR OPTION
        
        //CHECK THAT WE'RE IN VM 1
        $tables = $this->getTables();
        if(!is_array($tables)) return;
        if(!in_array($this->dbPrefix.'vm_product_price',$tables)) return;
        
        //GET ALL PRODUCT VARIATIONS
        $query = "SELECT i.`product_id`, i.`product_parent_id`, i.`product_sku`, p.`product_price` FROM `{$this->dbPrefix}vm_product` as i LEFT JOIN `{$this->dbPrefix}vm_product_price` as p ON (p.product_id = i.product_id) WHERE i.`product_parent_id` > '0'";
        $this->db->setQuery($query);
        $products = $this->db->loadAssocList();
        
        if(count($products)<1) return;
        
        foreach($products as $p) {

            //GET THE MAIN PRODUCT's FIRST OPTION GROUP
            $query = "SELECT `id` FROM `#__spr_item_optiongroups` WHERE `item_id` = '{$p['product_parent_id']}' LIMIT 1";
            $this->db->setQuery($query);
            $group = (int) $this->db->loadResult();
            
            if($group === 0) {
                //SET UP THE OPTION GROUP IF IT DOESNT EXIST
                $query = "SELECT `attribute_name` FROM `{$this->dbPrefix}vm_product_attribute` WHERE `product_id` = '{$p['product_parent_id']}' OR `product_id` = '{$p['product_id']}' ORDER BY `attribute_name` ASC LIMIT 1";
                $this->db->setQuery($query);
                $name = $this->db->loadResult();
                
                $query = "INSERT INTO `#__spr_item_optiongroups` SET `item_id` = '{$p['product_parent_id']}', `name` = '{$name}', `type` = '1'";
                $this->db->setQuery($query);
                $this->db->query();
                $group = (int) $this->db->insertid();
            }

            if($group === 0) continue;

            //GET THE PRICE FOR THE ATTRIBUTE PRODUCT
            $query = "SELECT `product_price` FROM `{$this->dbPrefix}vm_product_price` WHERE `product_id` = '{$p['product_id']}'";
            $this->db->setQuery($query);
            $price = (float) $this->db->loadResult();
            $newprice = 0;
            $newsku = '';
            if($price > 0) {
                $query = "SELECT `price`, `sku`, `taxes` FROM `#__spr_items` WHERE `id` = '{$p['product_parent_id']}' LIMIT 1";
                try {
                    $this->db->setQuery($query);
                    $res = $this->db->loadAssoc();
                } catch (Exception $e) {
                    //DO NOTHING
                }
                if(isset($res['price'])) {
                    
                    //SET THE NEW PRICE TO INCLUDE TAX IF NEEDED
                    $tax_style = sprConfig::_load('core')->taxes;
                    if($tax_style != '2') {
                        //ADJUST THE PRICE TO ACCOUNT FOR TAX
                        $taxes = json_decode($res['taxes']);
                        if(is_array($taxes)) $tax_id = (int)$taxes[0];
                        else $tax_id = (int)$taxes;
                        if($tax_id > 0) {
                            $mytax = sprTaxes::_load($tax_id);
                            $price = $price + sprTaxes::calculateTax($mytax,$price);
                            $price = round($price,2);
                        }
                    }
                    
                    $masterprice = (float) $res['price'];
                    $newprice = $price - $masterprice;
                    $mastersku = $res['sku'];
                    $newsku = str_replace($mastersku,'',$p['product_sku']);
                }
            }

            //GET ALL OPTIONS FOR THIS PRODUCT
            $query ="SELECT `attribute_id`, `attribute_value` FROM `{$this->dbPrefix}vm_product_attribute` WHERE `product_id` = '{$p['product_id']}' ORDER BY `attribute_name` ASC, `attribute_value` ASC";
            try {
                $this->db->setQuery($query);
                $options = $this->db->loadAssocList();
            } catch (Exception $e) {
                //DO NOTHING
            }
            if(count($options)>0) {
                $name = "";
                foreach($options as $n=>$o) {
                    if($n>0) $name .= ",";
                    $name .= $o['attribute_value'];
                }
                $option = $options[0];
                $query = "REPLACE INTO `#__spr_item_options` SET `id` = '{$option['attribute_id']}', `item_id` = '{$p['product_parent_id']}', `group_id` = '{$group}', `name` = '{$name}', `price` = '{$newprice}', `sku` = '{$newsku}'";
                try {
                    $this->db->setQuery($query);
                    $this->db->query();
                } catch (Exception $e) {
                    //DO NOTHING
                }
            }
        }
    }
    
    function import_shippingRates($data) {
        
        foreach($data as $d) {
            
            //DELETE OLD SHIPPING RULES
            $query = "DELETE FROM `#__spr_shippingrules` WHERE `id` = '{$d['shipping_rate_id']}'";
            $this->db->setQuery($query);
            $this->db->query();
        
            //UPDATE SHIPPING METHOD REGIONS
            $regions = explode(';',$d['shipping_rate_country']);
            $saveregions = array();
            if(count($regions)>0) {
                foreach($regions as $r) {
                    $query = "SELECT `id` FROM `#__spr_regions` WHERE `code_3` = '{$r}' LIMIT 1";
                    $this->db->setQuery($query);
                    $res = $this->db->loadAssoc();
                    if(isset($res['id'])) {
                        $saveregions[] = $res['id'];
                    }
                }
            }
            $regions = json_encode($saveregions);

            //ADD NEW SHIPPING RULES
            $query = "INSERT INTO `#__spr_shippingrules` SET `id` = '{$d['shipping_rate_id']}', `regions` = '{$regions}', `shipping_id` = '{$d['shipping_rate_carrier_id']}', `start_weight` = '{$d['shipping_rate_weight_start']}', `end_weight` = '{$d['shipping_rate_weight_end']}', `price` = '{$d['shipping_rate_value']}'";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
    
    function getJson($data) {
        $data = json_encode($data);
        return $data;        
    }
    
    function import_productCats($data) {
        $items = new salesProItems;
        foreach($data as $d) {
            $itemid = (int) $d['product_id'];
            $category = (int) $d['category_id'];
            $query = "UPDATE `#__spr_items` SET `category` = '{$category}' WHERE `id` = '{$itemid}' LIMIT 1";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
    
    function import_productPrices($data) {
        $items = new salesProItems;
        foreach($data as $d) {
            $query = "UPDATE `#__spr_items` SET `price` = '{$d['product_price']}' WHERE `id` = '{$d['product_id']}' LIMIT 1";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
    
    function import_orderUserInfo($data) {
        $regions = new salesProRegions;
        $sales = new salesProSales;
        foreach($data as $d) {
            $savedata = array();
            $savedata['user_email'] = $d['user_email'];
            $name = $d['title'].' '.$d['first_name'].' '.$d['last_name'];
            $savedata['user_bill_name'] = $name;
            $savedata['user_del_name'] = $name;
            $savedata['user_bill_address'] = $d['address_1'];
            $savedata['user_bill_address2'] = $d['address_2'];
            $savedata['user_bill_town'] = $d['city'];
            $state = $regions->db->getAssoc($regions->_table,'id',array('code_3'=>$d['state']));
            $savedata['user_bill_state'] = (isset($state['id'])) ? (int) $state['id'] : 0;
            $country = $regions->db->getAssoc($regions->_table,'id',array('code_3'=>$d['country']));
            $savedata['user_bill_country'] = (isset($country['id'])) ? (int) $country['id'] : 0;
            $savedata['user_bill_postcode'] = $d['zip'];
            $savedata['user_bill_phone'] = $d['phone_1'];
            if(isset($state['id'])) $region_id = (int)$state['id'];
            elseif (isset($country['id'])) $region_id = (int) $country['id'];
            else $region_id = 0;
            $savedata['user_bill_region_id'] = $region_id;
            foreach(array('name','address','address2','town','state','country','postcode','phone','region_id') as $x) {
                if(isset($savedata['user_bill_'.$x])) $savedata['user_del_'.$x] = $savedata['user_bill_'.$x];
            }
            
            //SAVE THE ORDER DETAILS
            $sales->db->updateData($sales->_table,$savedata,array('id' => $d['order_id']));
        }
    }
    
    function calc_orderShipMethod($data) {
        $data = @explode("|",$data);
        $array = array();
        if(count($data)>3) {
            $array['name'] = $data[1];
            $array['price'] = $data[3];
            $array['id'] = $data[4];
            $array['status'] = '1';
            $array['alias'] = '';
            $array['sort'] = '0';
        }
        return array('shipping_details' => json_encode($array));
    }
    
    function calc_orderDate($data) {
        $date = date("Y-m-j H:i:s", $data);
        return array('date' => $date);
    }
    
    function calc_orderStatus($data) {
        switch($data) {
            case 'P': //PENDING
                $ret = 4;
                break;
            case 'C': //CONFIRMED
                $ret = 1;
                break;
            case 'X': //CANCELLED
                $ret = 3;
                break;
            case 'R': //REFUNDED
                $ret = 2;
                break;
            case 'S': //SHIPPED
                $ret = 6;
                break;
            default: //DEFAULT IS 'CONFIRMED'
                $ret = 1;
                break;
        }
        return array('status' => $ret);
    }
    
    function calc_taxDetails($data) {
        $data = @unserialize($data);
        $ret = key($data);
        $tax_details = array();
        $tax_details[] = array(
            'id' => 0,
            'regions' => array(),
            'name' => 'Tax',
            'type' => '1',
            'value' => $ret * 100, 2,
            'status' => '1',
            'region' => '',
            'tax' => $data[$ret],
            'tax_formatted' => $data[$ret]
        );
        return array('tax_details' => json_encode($tax_details));
    }
    
    function calc_currencyCode($data) {
        $curr = new salesProCurrencies;
        $res = $curr->db->getAssoc($curr->_table,$curr->getVars(),array('code'=>$data));
        if(isset($res['id'])) {
            return array('currency_details'=>json_encode($res));
        }
        return '';
    }
    
    function calc_catXref($data) {
        if(count($data)>0) foreach($data as $d) {
            $parent = (int) $d['category_parent_id'];
            $child = (int) $d['category_child_id'];
            $query = "UPDATE `#__spr_categories` SET `parent` = '{$parent}' WHERE `id` = '{$child}'";
            $this->db->setQuery($query);
            $this->db->query();
        }
    }
    
    /* /// USER MIGRATION FUNCTIONS /// */
        
    function fixUser($data) {
        //CHECK IF A USER ALREADY HAS THIS ID
        $query = "SELECT `username`, `id` FROM `#__users` WHERE `id` = '{$data['id']}' LIMIT 1";
        $this->db->setQuery($query);
        $array = $this->db->loadAssoc();
        if(is_array($array)) {
            if($array['username'] == $data['username']) {
                return FALSE;
            } else {
                $query = "SELECT `id` FROM `#__users` ORDER BY `id` DESC LIMIT 1";
                $this->db->setQuery($query);
                $id = (int) $this->db->loadResult() + 1;
                $tables = $this->getTables();
                foreach(array('user_usergroup_map', 'user_profiles', 'user_usergroup_map') as $t) {
                    if(in_array($this->dbPrefix.$t,$tables)) {
                        $query = "UPDATE `{$this->dbPrefix}{$t}` SET `user_id` = '{$id}' WHERE `user_id` = '{$data['id']}'";
                        $this->db->setQuery($query);
                        $this->db->query();
                    }
                }
                $data['id'] = $id;
            }
        }
        
        $temp = $data['username'];
        $n = 0;
        do {
            $user = $temp;
            if($n > 0) $user .= '-'.$n;
            $query = 'SELECT '.$this->db->quoteName('id')
                .' FROM '.$this->db->quoteName('#__users')
                .' WHERE '.$this->db->quoteName('username')
                .' = '.$this->db->quote($user)
                .' LIMIT 1';
            $this->db->setQuery($query);
            $res = $this->db->loadResult();
            $n++;
        } while ((int)$res > 0);
        $data['username'] = $user;
        return $data;
    }

    function getUserGroup($data) {
        $map = array(
            'Super Administrator' => 'Super Users',
            'Administrator' => 'Administrator',
            'Manager' => 'Manager',
            'Public Back-end' => 'Guest',
            'Publisher' => 'Publisher',
            'Editor' => 'Editor',
            'Author' => 'Author',
            'Registered' => 'Registered',
            'Public Front-end' => 'Public'
        );
        
        if(array_key_exists($data['usertype'], $map)) {
            $type = $map[$data['usertype']];
        } else {
            $type = 'Public';
            //$this->log("WARNING: Unable to find correct Usergroup for user # {$data['id']}. Set as PUBLIC.");
        }
        
        $query = "SELECT `id` FROM `#__usergroups` WHERE `title` = '{$type}' LIMIT 1";
        $this->db->setQuery($query);
        try {
            $group = (int)$this->db->loadResult();
            $this->db->query();
            $query = "REPLACE INTO `#__user_usergroup_map` SET `user_id` = '{$data['id']}', `group_id` = '{$group}'";
            $this->db->setQuery($query);
            $this->db->query();
        }
        catch (Exception $e) {
            //$this->log("WARNING: Unable to save correct Usergroup for user # {$data['id']}");
        }
    }
    
	function __construct( $default = array()) {
		parent::__construct( $default );
        
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
		require_once(JPATH_CONFIGURATION.'/configuration.php');
		$CONFIG = new JConfig();
		$name = $CONFIG->db;
        $name = addslashes($name);
		$query = "SHOW TABLES FROM `$name` LIKE ".$this->db->quote($this->dbPrefix.'%');
		$this->db->setQuery($query);
		$array = $this->db->loadObjectList();
        $tabarray = array();
        $startarray = array();
        $endarray = array();
        foreach($array as $a=>$b) {
            foreach($b as $c) {
                $thistable = str_replace($this->dbPrefix, '',$c);
                if(in_array($thistable,array('vm_orders', 'vm_tax_rate'))) {
                    $startarray[] = $c;
                }
                elseif(in_array($thistable, array('virtuemart_product_customfields', 'virtuemart_product_categories', 'user_notes', 'user_profiles', 'user_usergroup_map'))) {
                    $endarray[] =  $c;
                }
                else $tabarray[] = $c;
            }
        }
        if(count($startarray)>0) foreach($startarray as $s) array_unshift($tabarray,$s);
        if(count($endarray)>0) foreach($endarray as $e) array_push($tabarray,$e);
        return $tabarray;
    }
    
    private function mapData($data) {
        
        //FIX USER DATA
        if($this->newtable === 'users') {
            foreach($data as $n=>$savedata) {
                $data[$n] = $this->fixUser($savedata);
                if($data[$n] === FALSE) {
                    unset($data[$n]);
                    continue;
                } else {
                    $this->getUserGroup($data[$n]);
                }
            }
        }

        foreach($data as $n=>$savedata) {
            
            if(isset($this->map[$this->newtable])) {
                
                $mtemp = $this->map[$this->newtable];
                $this->savetable = $mtemp['table'];

                foreach($mtemp['data'] as $field=>$replace) { //LOOP THROUGH THE FIELD MAPS
                    
                    //DISCARD DEPRECATED FIELDS
                    if($replace === 0) {
                        unset($savedata[$field]);
                        continue;
                    }
                    
                    $content = '';
                    
                    //FORCE A VALUE
                    if(substr($replace,0,1) === '!') {
                        $content = substr($replace,1);
                        $savedata[$field] = $content;
                        continue;
                    }
                    
                    //CHANGE THE FIELD NAME
                    if(substr($replace,0,1) === '@') {
                        $newfield = substr($replace,1);
                        $savedata[$newfield] = $savedata[$field];
                        unset($savedata[$field]);
                        continue;
                    }
    
                    //USE ANOTHER FIELD'S VALUE
                    elseif(substr($replace,0,1) === '$') {
                        if(isset($savedata[substr($replace,1)])) {
                            $savedata[$field] = $savedata[substr($replace,1)];
                        }
                        continue;
                    }
                    
                    //USE ANOTHER FIELD AND DISCARD THE ORIGINAL FIELD
                    elseif(substr($replace,0,1) === '&') {
                        if(isset($savedata[substr($replace,1)])) {
                            $savedata[$field] = $savedata[substr($replace,1)];
                            unset($savedata[substr($replace,1)]);
                        }
                        continue;
                    }
                    
                    //ADD 1 TO THE CURRENT NUMBER
                    elseif(substr($replace,0,1) === '+') {
                        if(!isset($savedata[$field])) $savedata[$field] = 0;                    
                        $content = ((int)$savedata[$field] + 1);
                        $savedata[$field] = $content;
                        continue;
                    }
                    
                    //USE A FUNCTION TO FIND THE VALUE
                    elseif(substr($replace,0,1) === '%') {
                        if(!isset($savedata[$field])) $savedata[$field] = '';
                        $func = substr($replace,1);
                        $content = $this->$func($savedata[$field]);
                        if(is_array($content)) foreach($content as $fld=>$cont) {
                            $savedata[$fld] = $cont;
                            unset($savedata[$field]);
                        } else {
                            $savedata[$field] = $content;
                        }
                        continue;
                    }
                    
                    //USE A FUNCTION AND FULL DATA ARRAY TO FIND THE VALUE
                    elseif(substr($replace,0,1) === '^') {
                        if(!isset($savedata[$field])) $savedata[$field] = '';
                        $func = substr($replace,1);
                        $content = $this->$func($data[$n]);
                        if($content === FALSE) { //REMOVE THE ENTIRE DATA ARRAY
                            unset($data[$n]);
                            continue 2;
                        }
                        if(is_array($content)) {
                            unset($savedata[$field]);
                            foreach($content as $fld=>$cont) {
                                $savedata[$fld] = $cont;
                            }
                        } else {
                            $savedata[$field] = $content;
                        }
                        continue;
                    }
                }
            }
            $data[$n] = $savedata;
        }
        return $data;
    }
    private function YNstatus($type) {
        if($type == 'N') return 0;
        else return 1;
    }
    
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
            if(in_array($ext, $this->_fileTypes)) {
                $tempFile = $_FILES['Filedata']['tmp_name'];
                if($ext === 'gz') {
                    $gzip = (function_exists("readgzfile")) ? 1 : 0;
                    if($gzip === 0) die(JText::_('SPR_IMP_ERROR_GZIP'));
                    if(!$handle = gzopen($tempFile, "r")) die(JText::_('SPR_IMP_ERROR_OPEN'));
                    if(!$newhandle = fopen($this->dbFile,'w+')) die(JText::_('SPR_IMP_ERROR_SAVE').': '.$this->dbFile);
                    while (!gzeof($handle)) {
                        fwrite($newhandle, gzread($handle, 10240));
                    }
                    fclose($newhandle);
                    gzclose($handle);
                    echo 1;
                } else {
                    if(!move_uploaded_file($tempFile,$this->dbFile)) die(JText::_('SPR_IMP_ERROR_SAVE').': '.$this->dbFile);
                    echo 1;
                }
        	} else {
        		echo JText::_('SPR_IMP_ERROR_FTYPE');
        	}
        } else {
            echo JText::_('SPR_IMP_ERROR_NOFILE');
        }
    }
    
    public function readFile() {
        
        if(!$this->auth()) return;
        //DELETE OLD IMPORT DATA
        $tables = $this->getTables();
        if(count($tables)>0) foreach($tables as $t) {
            $query = "DROP TABLE IF EXISTS ".$this->db->quoteName($t);
            $this->db->setQuery($query);
            $this->db->query();
        }
        
        //DELETE OLD SALES PRO DATA
        $tables = array('attributes', 'attributes_map', 'attributes_values', 'carts', 'cart_items', 'categories', 'categories_map', 'cookies', 'cookie_vars', 'items', 'item_dls', 'item_dls_links', 'item_faqs', 'item_images', 'item_variants', 'item_variants_map', 'item_videos', 'sales', 'sales_items', 'shipping', 'shippingrules', 'taxes', 'uniques');
        if(count($tables)>0) foreach($tables as $t) {
            $t = '#__spr_'.$t;
            $query = "DELETE FROM ".$this->db->quoteName($t);
            $this->db->setQuery($query);
            $this->db->query();
        }
                
        $handle = @fopen($this->dbFile, 'r+');
        if($handle === FALSE) {
            $this->error(JText::_('SPR_IMP_ERROR_DBOPEN').': '.$this->dbFile);
        }
        $speed = 1024;
        $sql = '';

        //READ THE DATABASE FILE & SAVE AS TEMPORARY TABLES        
        while(!feof($handle)) {
            $sql .= fread($handle, $speed);
            if(!isset($separator)) {
                foreach(array(";\r\n",";\n",";\r") as $a) {
                    if(strpos($sql,$a)!== FALSE) {
                        $separator = $a;
                        break;
                    }
                }
            }
            if(substr_count($sql, $a) > 1) {
                $queries = explode($a, $sql);
                if(!feof($handle)) $sql = array_pop($queries);
                if(count($queries)>0) foreach($queries as $q) {
                    $tabs = array();
                    if(strlen(trim($q))<5) continue;
                    $pos = strpos($q, '(');
                    $tables = substr($q, 0, $pos);
                    $tables = explode('`', $tables);
                    if(count($tables)<2) continue;
                    foreach($tables as $n=>$t) {
                        $i = $n+1;
                        if($i%2 !== 0) continue;
                        if(!in_array($t, $tabs)) {
                            $tab = $t;
                            $t = explode('_',$t);
                            $prefix = str_replace('_','',$this->dbPrefix);
                            $t[0] = $prefix;
                            $t = implode($t,'_');
                            $q = str_replace($tab, $t, $q);
                        }
                    }
                    $this->db->setQuery($q);
                    try {
                        $result = $this->db->query();
                    }
                    catch (Exception $e) {
                        //$this->error(JText::_('SPR_IMP_ERROR_TEMPTABLE').': '.$this->db->getErrorMsg().'\n\n'.JText::_('SPR_IMP_ERROR_FULLQUERY').': '.$q).'\n\n';
                    }
                }
            }
        }
        
        //CLOSE THE HANDLE
        ftruncate($handle,0);
        fclose($handle);
        
        //GET TEMP TABLE NAMES                
        $tables = $this->getTables();
        $this->json['tables'] = $tables;
        $this->json['point'] = 'migrateData';
        $this->json['text'] = JText::_('SPR_IMP_MIGRATING_VM_DATA').': '.$tables[0];
        $this->json['progress'] = 20;
        $this->json['jdata'] = '0';
        $this->saveJson();
    }
    
    function migrateData() {
        
        if(!$this->auth()) return;        
        $this->table = '';
        $this->savetable = '';
        $this->json['done'] = 0;
        $pos = (int) @$_POST['jdata'];
        
        $tables = $this->getTables();
        $tablecount = count($tables);
        if($pos >= $tablecount) {
            //FINISH IMPORT
            $this->json['jdata'] = '0';
            $this->json['text'] = JText::_('SPR_IMP_COMPLETING');
            $this->json['point'] = 'finishImport';
            $this->json['progress'] = 90;
            $this->saveJson();
            return FALSE;
        }
        $this->table = $tables[$pos];
        $tmp = substr($this->table,strlen($this->dbPrefix));
        $this->newtable = $tmp;
                
        $importwhere = "";
        //EXCEPTION FOR VM1 PRODUCT TABLE
        if($this->newtable === 'vm_product') {
            $importwhere = " WHERE `product_parent_id` = '0' ";
        };

        $query = "SELECT * FROM `{$this->table}` {$importwhere} LIMIT 0, {$this->speed}";
        try {
            $this->db->setQuery($query);
            $queries = $this->db->loadAssocList();
        }
        catch (Exception $e) {
            $pos++;
            $this->error(JText::_('SPR_IMP_ERROR_CANTIMPORT').': '.$this->table.' '.JText::_('SPR_IMP_ERROR').': '.$this->db->getErrorMsg());
            $_POST['jdata'] = $pos;
            return $this->migrateData();
        }

        $count = count($queries);

        //RUN A CALCULATION
        if(isset($this->calcTables[$this->newtable])) {
            $func = $this->calcTables[$this->newtable];
            $this->$func($queries);

        //OR JUST MIGRATE DATA
        } else {
            $data = $this->mapData($queries);
            if($this->savetable === '') {
                $pos++;
                $_POST['jdata'] = $pos;
                return $this->migrateData();
            }

            //LOOP THROUGH DATA AND SAVE TO DATABASE
            if(sizeof($data)>0) foreach($data as $n=>$values) {

                //DELETE OLD DATA
                if(isset($values['id'])) {
                    $id = (int)$values['id'];
                    $query = "DELETE FROM ".$this->db->quoteName('#__'.$this->savetable)." WHERE ".$this->db->quoteName('id').'='.$this->db->quote($id);
                    if($this->savetable === 'users') $query .= " AND `username` = ".$this->db->quote($values['username']);
                    try {
                        $this->db->setQuery($query);
                        $this->db->query();
                    }
                    catch (Exception $e) {
                        //DO NOTHING
                    }
                } else if(isset($values['user_id'])) {
                    $id = (int)$values['user_id'];
                    $query = "DELETE FROM ".$this->db->quoteName('#__'.$this->savetable)." WHERE ".$this->db->quoteName('user_id').'='.$this->db->quote($id);
                    if($this->savetable === 'user_usergroup_map') {
                        $query .= " AND `group_id` = ".$this->db->quote($values['group_id']);
                    }
                    try {
                        $this->db->setQuery($query);
                        $this->db->query();
                    }
                    catch (Exception $e) {
                        //DO NOTHING
                    }
                }
                
                //FIX FOR COUNTRY STATES
                if($this->newtable === 'vm_country') {
                    $query = "DELETE FROM `#__{$this->savetable}` WHERE `parent` = '{$id}'";
                    $this->db->setQuery($query);
                    $this->db->query();
                }
                
                /*
                //FIX USER LOGINS
                if($this->savetable === 'users') {
                    $tempalias = $values['username'];
                    $n = 0;
                    $done = 0;
                    while ($done === 0) {
                        $myalias = $tempalias;
                        if($n > 0) $myalias .= '-'.$n;
                        $n++;
                        $query = "SELECT `id` FROM `#__users` WHERE `username` = '{$myalias}' LIMIT 1";
                        $this->db->setQuery($query);
                        $res = (int) $this->db->loadResult();
                        if($res === 0) $done = 1;
                    }
                    $values['username'] = $myalias;
                }
                */
                
                //DELETE DATA DUPLICATES
                /*
                $query = $this->db->getQuery(true);
                $query
                    ->insert($this->db->quoteName('#__'.$this->savetable))
                    ->columns($this->db->quoteName(array_keys($values)))
                    ->values((implode(',',$this->db->quote($values))));
                */
                $query = "INSERT INTO ".$this->db->quoteName('#__'.$this->savetable).' SET ';
                $x = 0;
                foreach($values as $field=>$val) {
                    if($x++ > 0) $query .= ",";
                    $query .= $this->db->quoteName($field).' = '.$this->db->quote($val);
                }
                try {
                    $this->db->setQuery($query);
                    $this->db->query();
                } catch(Exception $e) {
                    $this->error(JText::_('SPR_IMP_ERROR_CANTIMPORTDATA').': '.$this->db->getErrorMsg());
                }
                if($this->savetable === 'spr_item_images') {
                    //echo $query;
                    //die();
                }
            }
        }
        //die('STOPPING PROCESS!');
        
        //DELETE PROCESSED RECORDS
        $query = "DELETE FROM `{$this->table}` {$importwhere} LIMIT {$this->speed}";
        try {
            $this->db->setQuery($query);
            $this->db->query();
        }
        catch (Exception $e) {
            $this->error(JText::_('SPR_IMP_ERROR_CANTDELDATA').' '.$this->table.' '.JText::_('SPR_IMP_ERROR').': '.$this->db->getErrorMsg());
        }
        
//        $this->log(JText::_('SPR_IMP_MIGRATING_VM_DATA').": {$this->table} ({$count})");
        
        //CHECK HOW MANY ARE DONE
        $query = "SELECT COUNT(*) as c FROM `{$this->table}`";
        $this->db->setQuery($query);
        $count = (int) $this->db->loadResult();
        if($count === 0) $pos++;
        
        if($pos >= $tablecount) {
            
            //FINISH IMPORT
            $this->json['jdata'] = '0';
            $this->json['text'] = JText::_('SPR_IMP_COMPLETING');
            $this->json['point'] = 'finishImport';
            $this->json['progress'] = 90;
            
        } else {
        
            //CONTINUE WITH NEXT TABLE
            $this->json['count'] = $count;
            $this->json['jdata'] = $pos;
            $this->json['text'] = JText::_('SPR_IMP_MIGRATING_VM_DATA').': '.$tables[$pos];
            $this->json['point'] = 'migrateData';
            $this->json['progress'] = 20 + (round(($pos*60) / $tablecount,0));
        }
        
        $this->saveJson();
    }
    
    function finishImport() { //POST PROCESSING
        if(!$this->auth()) return;
        //FIX PRODUCT PRICES
        $this->fix_prodPrice();

        //FIX PRODUCT OPTIONS
        $this->fix_prodOptions();
        
        //FIX SORTING
        $this->fixSorts();
                
        //DELETE TEMPORARY TABLES
        $tables = $this->getTables();
        if(count($tables)>0) foreach($tables as $t) {
            $query = "DROP TABLE ".$this->db->quoteName($t);
            $this->db->setQuery($query);
            $this->db->query();
        }
        $this->json['progress'] = 100;
        $this->json['point'] = 1;
        $this->saveJson();
    }
}