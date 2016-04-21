<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title($this->title, 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
JToolBarHelper::custom( 'apply', 'apply', 'apply', JText::_('SPR_APPLY'), 0,0 );
$editor = JFactory::getEditor();
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_salespro/resources/uploadifive/uploadifive.css');
?>

<div id="spr_overlay"></div>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo $this->title; ?></h1>
</div>

<form action="" method="post" name="adminForm" id="adminForm">

<div id="spr_tabs">
<div class="spr_tabs" >
<ul>
    <li><a href="#setup"><?php echo JText::_('SPR_ITEM_TAB_SETUP'); ?></a></li>
    <li><a href="#description"><?php echo JText::_('SPR_ITEM_TAB_DESC'); ?></a></li>
    <li><a href="#images"><?php echo JText::_('SPR_ITEM_TAB_IMAGES'); ?></a></li>
    <li id="var_tab"><a href="#variants"><?php echo JText::_('SPR_ITEM_TAB_VARIANTS'); ?></a></li>
    <li id="dl_tab"><a href="#downloads"><?php echo JText::_('SPR_ITEM_TAB_DLS'); ?></a></li>
    <li><a href="#videos"><?php echo JText::_('SPR_ITEM_TAB_VIDS'); ?></a></li>
    <li><a href="#faqs"><?php echo JText::_('SPR_ITEM_TAB_FAQS'); ?></a></li>
    <li><a href="#specs"><?php echo JText::_('SPR_ITEM_TAB_SPECS'); ?></a></li>
    <li><a href="#metas"><?php echo JText::_('SPR_ITEM_TAB_METAS'); ?></a></li>
</ul>

    <div id="setup">

        <div class="spr_tab_right">
        <fieldset class="spr_fieldset">
            <h4><?php echo JText::_('SPR_ITEM_HEADING_DETAILS'); ?></h4>
            <div class="spr_field">
                <label for="spr_items_type"><?php echo JText::_('SPR_ITEM_PRODTYPE'); ?></label>
                <select name="spr_items_type" id="spr_items_type">
                <?php foreach($this->prodTypes as $t) {
                    $sel = ($t->id == $this->item->type) ? 'selected="selected"' : '';
                    echo "<option value='{$t->id}'";
                    foreach($t->params as $field=>$val) {
                        echo " {$field}='{$val}'";
                    }
                    echo "{$sel}>{$t->name}</option>";
                } ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_items_category"><?php echo JText::_('SPR_ITEM_MAINCAT'); ?></label>
                <select id="spr_items_category" name="spr_items_category">
                <?php if(count($this->all_categories)>0)
                    foreach($this->all_categories as $c) {
                        echo $this->categories->showCatOption($c,$this->item->category);
                } ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_categories"><?php echo JText::_('SPR_ITEM_EXTRA_CATS'); ?></label>
                <select id="spr_categories" name="spr_categories[]" multiple="multiple" style="height: 100px">
                <?php if(count($this->all_categories)>0)
                    foreach($this->all_categories as $c) {
                        echo $this->categories->showCatOption($c,$this->item->categories);
                } ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_items_status"><?php echo JText::_('SPR_ITEM_STATUS'); ?></label>
                <select id="spr_items_status" name="spr_items_status">
                <?php echo $this->class->selectOptions($this->item->status,'pub'); ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_items_featured"><?php echo JText::_('SPR_ITEM_FEATURED'); ?></label>
                <select name="spr_items_featured" id="spr_items_featured">
                <?php echo $this->class->selectOptions($this->item->featured,'yesno'); ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_items_taxes"><?php echo JText::_('SPR_ITEM_TAXRULES'); ?></label>
                <select name="spr_items_taxes[]" id="spr_items_taxes" multiple="multiple" style="height: 100px">
                    <?php
                    $seltax = json_decode($this->item->taxes);
                    echo sprItems::_options($seltax,$this->taxes); ?>
                </select>
            </div>
            <div id="simple_inputs">
                <div class="spr_field">
                    <label for="spr_items_price"><?php echo JText::_('SPR_ITEM_PRICE'); ?></label>
                    <input type="text" name="spr_items_price" id="spr_items_price" value="<?php echo $this->item->price; ?>" />
                </div>
                <div class="spr_field">
                    <label for="spr_items_sale"><?php echo JText::_('SPR_ITEM_SALEPRICE'); ?></label>
                    <input type="text" name="spr_items_sale" id="spr_items_sale" value="<?php echo $this->item->sale; ?>" />
                </div>
                <div class="spr_field">
                    <label for="spr_items_onsale"><?php echo JText::_('SPR_ITEM_ONSALE'); ?></label>
                    <select name="spr_items_onsale" id="spr_items_onsale"><?php echo sprItemVariants::_options($this->item->onsale,'noyes'); ?></select>
                </div>
                <div class="spr_field">
                    <label for="spr_items_sku"><?php echo JText::_('SPR_ITEM_SKU'); ?></label>
                    <input type="text" name="spr_items_sku" id="spr_items_sku" value="<?php echo $this->item->sku; ?>" />
                </div>
                <div class="spr_field stock_man" <?php if($this->item->prodtype->params->sm !== '1') echo 'style="display:none"'; ?>>
                    <label for="spr_items_stock"><?php echo JText::_('SPR_ITEM_INSTOCK'); ?></label>
                    <input type="text" name="spr_items_stock" id="spr_items_stock" value="<?php echo $this->item->stock; ?>" />
                </div>
            </div>
        </fieldset>
        </div>
    	<div class="spr_tab_left">
            <fieldset class="spr_table-container spr_fieldset">
            <div class="spr_table-row">
                <div class="spr_table-cell">
                <label for="spr_items_name" style="min-width:140px;"><?php echo JText::_('SPR_ITEM_PRODNAME'); ?></label>
                </div>
                <div class="spr_table-cell-wide">
                <input type="text" name="spr_items_name" id="spr_items_name" value="<?php echo $this->item->name; ?>" style="width: calc(100% - 7px);" />
                </div>
            </div>
            <div class="spr_table-row">
                <div class="spr_table-cell">
                <label for="spr_items_tagline" style="width:120px" style="width: 15%"><?php echo JText::_('SPR_ITEM_TAGLINE'); ?></label>
                </div>
                <div class="spr_table-cell-wide">
                <input type="text" name="spr_items_tagline" id="spr_items_tagline"  style="width: calc(100% - 7px);" value="<?php echo $this->item->tagline; ?>" />
                </div>
            </div>
            <div class="spr_table-row">
                <div class="spr_table-cell" style="vertical-align:top">
                <label for="spr_items_mini_desc"><?php echo JText::_('SPR_ITEM_BRIEFDESC'); ?></label>
                </div>
                <div class="spr_table-cell-wide">
                    <?php echo $editor->display('spr_items_mini_desc', $this->item->mini_desc, '100%', '400', '70', '15',false); ?>
            </div>
            </fieldset>
        </div>
        
        <style>
        fieldset.spr_table-container {
            display: table;
            border-collapse: collapse;
            overflow: visible;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        
        div.spr_table-row {
            display: table-row;
            width: 100%;
            margin: 10px 0;
        }
        
        div.spr_table-cell {
            display: table-cell;
            width: 110px;
            padding: 10px 0;                                    
        }
        div.spr_table-cell-wide {
            display: table-cell;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        div.spr_table-cell-wide input {
            float: left;
        }
        </style>

        <style>
        #dl_tab {
            <?php if($this->item->prodtype->params->dl !== '1') echo 'display: none'; ?>
        }
        #var_tab {
            <?php if($this->item->prodtype->params->var !== '1') echo 'display:none'; ?>
        }
        #simple_inputs {
            <?php if($this->item->prodtype->params->var === '1') echo 'display:none'; ?>
        }
        .stock_man {
             <?php if($this->item->prodtype->params->sm !== '1') echo 'display:none'; ?>
        }

        </style>
    </div>

    <div id="description">
        <div class="spr_tab_right">
            <fieldset class="spr_fieldset">
            <h4><?php echo JText::_('SPR_ITEM_RHS_SETTINGS'); ?></h4>
            <div class="spr_field">
                <label for="spr_items_tab1_active"><?php echo JText::_('SPR_ITEM_ACTIVETAB'); ?></label>
                <select id="spr_items_tab1_active" name="spr_items_tab1_active">
                    <?php echo $this->class->selectOptions($this->item->tab1_active, 'yesno'); ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_items_tab1_name"><?php echo JText::_('SPR_ITEM_TABNAME'); ?></label>
                <input id="spr_items_tab1_name" name="spr_items_tab1_name" type="text" value="<?php echo $this->item->tab1_name; ?>" placeholder="e.g. <?php echo JText::_('SPR_ITEM_TAB1'); ?>" />
            </div>
            </fieldset>
        </div>
		<div class="spr_tab_left">
            <fieldset class="spr_fieldset">
            <label for="spr_items_full_desc"><?php echo JText::_('SPR_ITEM_FULLDESC'); ?></label>
            <br style="clear:both" />
    		<div class="editor">
            <?php
            echo $editor->display('spr_items_full_desc', $this->item->full_desc, '100%', '400', '70', '15',false); ?>
            </div>
            </fieldset>
        </div>
    </div>

    <div id="images">
    
        <div class="spr_tip"><p><?php echo JText::_('SPR_ITEM_TAB_IMAGES_TIP'); ?></p></div>
                
        <div class="spr_tab_right">
            <fieldset class="spr_fieldset" id="imagesDetails">
            <h4><?php echo JText::_('SPR_ITEM_RHS_SETTINGS'); ?></h4>
            <div class="spr_field">
                <label for="spr_items_tab2_active"><?php echo JText::_('SPR_ITEM_ACTIVETAB'); ?></label>
                <select id="spr_items_tab2_active" name="spr_items_tab2_active">
                    <?php echo $this->class->selectOptions($this->item->tab2_active,'yesno'); ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_items_tab2_name"><?php echo JText::_('SPR_ITEM_TABNAME'); ?></label>
                <input id="spr_items_tab2_name" name="spr_items_tab2_name" type="text" value="<?php echo $this->item->tab2_name; ?>" placeholder="e.g. <?php echo JText::_('SPR_ITEM_TAB2'); ?>" />
            </div>
            </fieldset>
            <fieldset class="spr_fieldset" id="imagesEdit" style="display: none;">
            <input type="hidden" id="i_id" value="" />
            <h4><?php echo JText::_('SPR_ITEM_IMAGE_EDIT'); ?></h4>
            <div class="spr_field">
                <label for="i_title"><?php echo JText::_('SPR_ITEM_IMAGE_TITLE'); ?></label>
                <input id="i_title" id="i_title" />
            </div>
            <div class="spr_field">
                <label for="i_desc"><?php echo JText::_('SPR_ITEM_IMAGE_CAPTION'); ?></label>
                <textarea id="i_desc" id="i_desc"></textarea>
            </div>
            <div class="spr_confirm_buttons">
                <a href="#" onclick="cancelImage();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
                <a href="#" onclick="saveImage();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
            </div>
            </fieldset>
        </div>
        <div class="spr_tab_left" id="spr_item_imgs">
            <div class="spr_import">
                <input id="spr_upload" name="spr_upload" type="file" />
                <div id="queue"></div>
            </div>
            <div id="spr_html5"><p><?php echo JText::_('SPR_ITEM_IMAGE_HTML5'); ?></p></div>
            
            <ul id="spr_item_images_list" class="sortable">
            <?php if(count($this->item->images)>0) foreach($this->item->images as $img) {
                echo "<li class='spr_img_edit' id='spr_item_images_{$img->id}' i_id='{$img->id}' i_title='{$img->title}' i_desc='{$img->desc}'><span class='ui-icon ui-icon-arrowthick-2-n-s' style='position: absolute; margin: 5px;;float: left;'>&nbsp;</span><div class='spr_icon_holder'><a class='spr_icon spr_icon_edit' onclick='editImage({$img->id})'>&nbsp;</a><a class='spr_icon spr_icon_delete' onclick='deleteImage({$img->id})'>&nbsp;</a></div><img src='{$img->src}' /></li>";
            } ?>
            </ul>
        </div>
    </div>
    
    <div id="variants">
    
        <div class="spr_tab_right" id="itemAttributes">
            <fieldset class="spr_fieldset" id="attributeAdd">
            
            <h4><?php echo JText::_('Select product attributes'); ?></h4>
            
            <p>Select the attributes for this product, which you can then use to create each product variant.</p>
                <h5><?php echo JText::_('Add a new Attribute'); ?></h5>
                <div class="spr_field">
                    <input id="attributes_name" type="text" style="width:240px;" placeholder="<?php echo JText::_('Enter an attribute name'); ?>" /><a href='#' onclick='saveAtt()' class='spr_icon spr_icon_save'>&nbsp;</a>
                    <div style="display: none;" class="spr_field_options"></div>
                </div>
                
                <h5><?php echo JText::_('Selected attributes'); ?></h5>
                <div id="attributesList"></div>

            </fieldset>
        </div>
        
        <style>
        #attributeAdd .spr_field_options {
            width: 252px;
            margin-left: 0;
        }
        </style>
        
        <script>

jQuery(document).on('click', '#attributeAdd .spr_field_options li', function() {
    var attr = jQuery(this).html();
    jQuery('#attributes_name').val(attr);
    closeAttr();
});

function closeAttr() {
    jQuery('#attributeAdd .spr_field_options').hide();
}

function getItemAttributes($catchange) {
    console.log($catchange);
    if(typeof $catchange === 'undefined') $catchange = 0;
    else $catchange = 1;
    var catid = jQuery('#spr_items_category').val();
    var item_id = jQuery('#spr_id').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=load&tab=itemAttributesMap',
        data: {item_id:item_id,catid:catid,catchange:$catchange},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#attributesList').html(json.string);
            getAttributeFields();
        }
    });
}
function getItemAttributesOptions($changecat) {
    var item_id = jQuery('#spr_id').val();
    var att_name = jQuery('#attributes_name').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=getOptions&tab=itemAttributesMap',
        data: {item_id:item_id,att_name:att_name},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            if(json.string.length < 1) closeAttr();
            else jQuery('#attributeAdd .spr_field_options').html(json.string).show();
        }
    });
}

function saveAtt() {
    var item_id = jQuery('#spr_id').val();
    var att_name = jQuery('#attributes_name').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=itemAttributesMap',
        data: {item_id:item_id,att_name:att_name},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#attributesList').html(json.string);
            jQuery('#attributes_name').val('');
            getAttributeFields();
        }
    });
}
function deleteAtt($id) {
    var item_id = jQuery('#spr_id').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=itemAttributesMap',
        data: {item_id:item_id,att:$id},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#attributesList').html(json.string);
            getAttributeFields();
        }
    });
}
jQuery(document).on('keyup', '#attributes_name',function() {
    if(jQuery(this).val().length > 0) getItemAttributesOptions();
    else closeAttr();
});
        </script>
    
        <div class="spr_tab_right" id="variantFields" style="display:none">
            <fieldset class="spr_fieldset" id="variantAdd">
            <h4><?php echo JText::_('SPR_ITEM_VARADD'); ?></h4>

            <h5><?php echo JText::_('SPR_ITEM_VARATTRS'); ?></h5>
            
            <div id="spr_attr_fields"></div>

            <style>
            .spr_field_options {
                width: 160px;
                height: auto;
                position: absolute;
                margin-left: 110px;
                margin-top: -5px;
                box-shadow: 1px 1px 1px 1px #ccc;
                background: white;
                display: none;
            }
            .spr_field_options ul {
                list-style: none;
                margin: 0;
            }
            .spr_field_options li {
                padding: 5px 10px;
                display: block;
                float: none;
                background: blue;
                color: white;
            }
            .spr_field_options li:hover {
                color: white;
                background: red;
                cursor: pointer;
            }
            </style>
            <hr />
            <h5><?php echo JText::_('SPR_ITEM_VARDETS'); ?></h5>
            <input type="hidden" id="var_id" value="" class="variant_field" />
            <div class="spr_field">
                <label for="var_image_id"><?php echo JText::_('SPR_ITEM_IMAGE'); ?></label>
                <input id="var_image_id" class="variant_field" type="text" value="" style="width: 120px;float:left;" readonly="readonly" />
                <div class="spr_icon spr_icon_search" style="float:left" onclick="getVariantImages()"></div>
            </div>
            <div class="spr_field">
                <label for="var_price"><?php echo JText::_('SPR_ITEM_PRICE'); ?></label>
                <input type="text" id="var_price" class="variant_field" value="" />
            </div>
            <div class="spr_field">
                <label for="var_sku"><?php echo JText::_('SPR_ITEM_SKU'); ?></label>
                <input type="text" id="var_sku" class="variant_field" value="" />
            </div>
            <div class="spr_field stock_man">
                <label for="var_stock"><?php echo JText::_('SPR_ITEM_INSTOCK'); ?></label>
                <input type="text" id="var_stock" class="variant_field" value="" />
            </div>
            <div class="spr_field">
                <label for="var_status"><?php echo JText::_('SPR_ITEM_STATUS'); ?></label>
                <select id="var_status" class="variant_field"><?php echo sprItemVariants::_options('','pub'); ?></select>
            </div>
            <hr />
            <h5><?php echo JText::_('SPR_ITEM_VARPROMO'); ?></h5>
            <div class="spr_field">
                <label for="var_sale"><?php echo JText::_('SPR_ITEM_SALEPRiCE'); ?></label>
                <input type="text" id="var_sale" class="variant_field" value="" />
            </div>
            <div class="spr_field">
                <label for="var_onsale"><?php echo JText::_('SPR_ITEM_ONSALE'); ?></label>
                <select id="var_onsale" class="variant_field"><?php echo sprItemVariants::_options('','noyes'); ?></select>
            </div>
            <hr />
            <div class="spr_confirm_buttons">
                <a href="#" onclick="cancelVariant();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
                <a href="#" onclick="saveVariant();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
            </div>
            </fieldset>
        </div>

        <div class="spr_tab_left">

        <div class="spr_big_button" id="createVariant" style="clear: none; margin-left: 10px;"><?php echo JText::_('SPR_ITEM_VARADDA'); ?></div>
        
        <table class="spr_table" id="variantsList">
        <thead>
        <tr>
        <th width="1%" class="nowrap" align="left"><?php echo JText::_('SPR_ITEM_IMAGE'); ?></th>
        <th class="nowrap" align="left"><?php echo JText::_('SPR_ITEM_VARATTRS'); ?></th>
        <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_PRICE'); ?></th>
        <th width="1%" class="nowrap center stock_man"><?php echo JText::_('SPR_ITEM_INSTOCK'); ?></th>
        <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_SALEPRICE'); ?></th>
        <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_ONSALE'); ?></th>
        <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_STATUS'); ?></th>
        <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_ACTION'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(count($this->item->variants)>0) foreach($this->item->variants as $v) {
            echo sprItemVariants::_getHTML($v);
        } ?>
        </tbody>
        </table>
        
        </div>
    </div>
    
    <div id="videos">
    
        <div class="spr_tip"><p><?php echo JText::_('SPR_ITEM_TAB_VIDS_TIP'); ?></p></div>
        
		<div class="spr_tab_right">
            <fieldset class="spr_fieldset" id="videoDetails">
            <h4><?php echo JText::_('SPR_ITEM_RHS_SETTINGS'); ?></h4>
            <div class="spr_field">
                <label for="spr_items_tab3_active"><?php echo JText::_('SPR_ITEM_ACTIVETAB'); ?></label>
                <select id="spr_items_tab3_active" name="spr_items_tab3_active">
                    <?php echo $this->class->selectOptions($this->item->tab3_active,'yesno'); ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_items_tab3_name"><?php echo JText::_('SPR_ITEM_TABNAME'); ?></label>
                <input id="spr_items_tab3_name" name="spr_items_tab3_name" type="text" value="<?php echo $this->item->tab3_name; ?>" placeholder="e.g. <?php echo JText::_('SPR_ITEM_TAB3'); ?>" />
            </div>
            </fieldset>
            <fieldset class="spr_fieldset" style="display:none" id="createVideoFields">
            <h4><?php echo JText::_('SPR_ITEM_VIDS_ADD'); ?></h4>
            <input type="hidden" id="v_id" value="" />
            <div class="spr_field">
                <label for="v_url"><?php echo JText::_('SPR_ITEM_VIDS_URL'); ?></label>
                <input id="v_url" type="text" value="" placeholder="e.g. https://www.youtube.com/embed/RR9VbmIh1Rs" />
            </div>
            <div class="spr_field">
                <label for="v_height"><?php echo JText::_('SPR_ITEM_VIDS_HEIGHT'); ?></label>
                <input id="v_height" style="width: 60px;" type="text" value="" /> px
            </div>
            <div class="spr_field">
                <label for="v_width"><?php echo JText::_('SPR_ITEM_VIDS_WIDTH'); ?></label>
                <input id="v_width" style="width: 60px;" type="text" value="" /> px
            </div>
            <div class="spr_confirm_buttons">
                <a href="#" onclick="cancelVideo();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
                <a href="#" onclick="saveVideo();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
            </div>                                 
            </fieldset>
        </div>
        <div class="spr_tab_left">
            <div class="spr_big_button" id="createVideo" style="float:right"><?php echo JText::_('SPR_ITEM_VIDS_ADD'); ?></div>
            <table class="spr_table" id="videosList">
            <thead>
                <tr>
                    <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_ORDER'); ?></th>
                    <th align="left" width="40%"><?php echo JText::_('SPR_ITEM_VIDS_URL'); ?></th>
                    <th width="20%" class="nowrap center"><?php echo JText::_('SPR_ITEM_VIDS_HEIGHT'); ?></th>
                    <th width="20%" class="nowrap center"><?php echo JText::_('SPR_ITEM_VIDS_WIDTH'); ?></th>
                    <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_ACTION'); ?></th>
                </tr>
            </thead>
            <tbody id="spr_item_videos_list">
                <?php foreach($this->item->videos as $v) {
                echo "
                <tr id='spr_item_videos_{$v->id}' v_id='{$v->id}' v_url='$v->url' v_height='{$v->height}' v_width='{$v->width}'>
                <td width='1%' class='nowrap center'><span class='ui-icon ui-icon-arrowthick-2-n-s' style='margin: 0 10px;'>&nbsp;</span></td>
                <td>{$v->url}</td>
                <td class='nowrap center'>{$v->height} px</td>
                <td class='nowrap center'>{$v->width} px</td>
                <td width='1%' class='nowrap center'><a href='#' onclick='editVideo({$v->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteVideo({$v->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
                </tr>";
                } ?>
            </tbody>
            </table>
        </div>
    </div>
    
    <div id="faqs">

		<div class="spr_tab_right">
            <fieldset class="spr_fieldset" id="faqDetails">
            <h4><?php echo JText::_('SPR_ITEM_RHS_SETTINGS'); ?></h4>
            <div class="spr_field">
                <label for="spr_items_tab4_active"><?php echo JText::_('SPR_ITEM_ACTIVETAB'); ?></label>
                <select id="spr_items_tab4_active" name="spr_items_tab4_active">
                    <?php echo $this->class->selectOptions($this->item->tab4_active,'yesno'); ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_items_tab4_name"><?php echo JText::_('SPR_ITEM_TABNAME'); ?></label>
                <input id="spr_items_tab4_name" name="spr_items_tab4_name" type="text" value="<?php echo $this->item->tab4_name; ?>" placeholder="e.g. <?php echo JText::_('SPR_ITEM_TAB4'); ?>" />
            </div>
            </fieldset>
            <fieldset class="spr_fieldset" style="display:none" id="createFaqFields">
            <h4><?php echo JText::_('SPR_ITEM_FAQS_ADD'); ?></h4>
            <input type="hidden" id="f_id" value="" />
            <div class="spr_field">
                <label for="f_question"><?php echo JText::_('SPR_ITEM_FAQS_QUESTION'); ?></label>
                <input id="f_question" type="text" value="" />
            </div>
            <div class="spr_field">
                <label for="f_answer"><?php echo JText::_('SPR_ITEM_FAQS_ANSWER'); ?></label>
                <textarea id="f_answer"></textarea>
            </div>
            <div class="spr_confirm_buttons">
                <a href="#" onclick="cancelFaq();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
                <a href="#" onclick="saveFaq();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
            </div>                                 
            </fieldset>
        </div>
        
        <div class="spr_tab_left">
            <div class="spr_big_button" id="createFaq" style="float:right"><?php echo JText::_('SPR_ITEM_FAQS_ADD'); ?></div>
            <table class="spr_table" id="faqsList">
            <thead>
                <tr>
                    <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_ORDER'); ?></th>
                    <th align="left" width="50%"><?php echo JText::_('SPR_ITEM_FAQS_QUESTION'); ?></th>
                    <th align="left" width="50%"><?php echo JText::_('SPR_ITEM_FAQS_ANSWER'); ?></th>
                    <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_ACTION'); ?></th>
                </tr>
            </thead>
            <tbody id="spr_item_faqs_list">
                <?php foreach($this->item->faqs as $f) {
                echo "
                <tr id='spr_item_faqs_{$f->id}' f_id='{$f->id}' f_question='{$f->question}' f_answer='{$f->answer}'>
                <td width='1%' class='nowrap center'><span class='ui-icon ui-icon-arrowthick-2-n-s' style='margin: 0 10px;'>&nbsp;</span></td>
                <td>{$f->question}</td>
                <td>{$f->answer}</td>
                <td width='1%' class='nowrap center'><a href='#' onclick='editFaq({$f->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteFaq({$f->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
                </tr>";
                } ?>
            </tbody>
            </table>
        </div>
    </div>
    				
    <div id="specs">
    
        <div class="spr_tab_right">
            <fieldset class="spr_fieldset" id="faqDetails">
            <h4><?php echo JText::_('SPR_ITEM_RHS_SETTINGS'); ?></h4>
            <div class="spr_field">
                <label for="spr_items_tab5_active"><?php echo JText::_('SPR_ITEM_ACTIVETAB'); ?></label>
                <select id="spr_items_tab5_active" name="spr_items_tab5_active">
                    <?php echo $this->class->selectOptions($this->item->tab5_active,'yesno'); ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="spr_items_tab5_name"><?php echo JText::_('SPR_ITEM_TABNAME'); ?></label>
                <input id="spr_items_tab5_name" name="spr_items_tab5_name" type="text" value="<?php echo $this->item->tab5_name; ?>" placeholder="e.g. <?php echo JText::_('SPR_ITEM_TAB5'); ?>" />
            </div>
            </fieldset>
        </div>
        <div class="spr_tab_left">
        <fieldset class="spr_fieldset">
            <div class="spr_field">
                <label for="spr_items_manufacturer"><?php echo JText::_('SPR_ITEM_SPEC_MANU'); ?></label>
                <input type="text" name="spr_items_manufacturer" id="spr_items_manufacturer" value="<?php echo $this->item->manufacturer; ?>" />
            </div>
            <div class="spr_field">
                <label for="spr_items_origin"><?php echo JText::_('SPR_ITEM_SPEC_ORIGIN'); ?></label>
                <input type="text" name="spr_items_origin" id="spr_items_origin" value="<?php echo $this->item->origin; ?>" />
            </div>
            <div class="spr_field">
                <label for="spr_items_weight"><?php echo JText::_('SPR_ITEM_SPEC_WEIGHT'); ?> (<?php echo $this->weightunit; ?>)</label>
                <input type="text" name="spr_items_weight" id="spr_items_weight" value="<?php echo $this->item->weight; ?>" />
            </div>
            <div class="spr_field">
                <label for="spr_items_height"><?php echo JText::_('SPR_ITEM_SPEC_HEIGHT'); ?> (<?php echo $this->sizeunit; ?>)</label>
                <input type="text" name="spr_items_height" id="spr_items_height" value="<?php echo $this->item->height; ?>" />
            </div>
            <div class="spr_field">
                <label for="spr_items_width"><?php echo JText::_('SPR_ITEM_SPEC_WIDTH'); ?> (<?php echo $this->sizeunit; ?>)</label>
                <input type="text" name="spr_items_width" id="spr_items_width" value="<?php echo $this->item->width; ?>" />
            </div>
            <div class="spr_field">
                <label for="spr_items_depth"><?php echo JText::_('SPR_ITEM_SPEC_DEPTH'); ?> (<?php echo $this->sizeunit; ?>)</label>
                <input type="text" name="spr_items_depth" id="spr_items_depth" value="<?php echo $this->item->depth; ?>" />
            </div>
        </fieldset>
        <fieldset style="border: 0;">
            <label for="spr_items_specification"><?php echo JText::_('SPR_ITEM_SPEC_EXTRA'); ?></label><br style="clear: both" />
    		<div class="editor">
            <?php
            echo $editor->display('spr_items_specification', $this->item->specification, '98%', '400', '70', '15',false); ?>
            </div>
        </fieldset>
        </div>
    </div>
    
        <div id="downloads">
        
            <div class="spr_tip"><p><?php echo JText::_('SPR_ITEM_TAB_DLS_TIP'); ?> <a href="../index.php?option=com_salespro&view=downloads" target="_blank"><?php echo JText::_('SPR_ITEM_TAB_DLS_TIP2'); ?></a></p></div>
        
            <div class="spr_tab_right" id="dlEdit" style="padding: 10px; border-left: 1px solid #ddd; margin-left: 10px;display:none;">
            <fieldset class="spr_fieldset">
            <input type="hidden" id="d_id" value="" />
            <h4><?php echo JText::_('SPR_ITEM_DLS_EDIT'); ?></h4>
            <div class="spr_field">
                <label for="d_name"><?php echo JText::_('SPR_ITEM_DLS_NAME'); ?></label>
                <input id="d_name" id="d_name" />
            </div>
            <div class="spr_field">
                <label for="d_ext"><?php echo JText::_('SPR_ITEM_DLS_EXT'); ?></label>
                <span id="d_ext"></span>
            </div>
            <div class="spr_field">
                <label for="d_status"><?php echo JText::_('SPR_ITEM_DLS_STATUS'); ?></label>
                <select id="d_status">
                <?php echo $this->class->selectOptions($this->item->status,'yesno'); ?>
                </select>
            </div>
            <div class="spr_field">
                <label for="d_days"><?php echo JText::_('SPR_ITEM_DLS_DAYS'); ?></label>
                <input id="d_days" id="d_days" />
                <span style="font-size: 10px; clear: both;"><?php echo JText::_('SPR_ITEM_DLS_DAYSTIP'); ?></span>
            </div>
            <div class="spr_field">
                <label for="d_times"><?php echo JText::_('SPR_ITEM_DLS_COUNT'); ?></label>
                <input id="d_times" id="d_times" />
                <span style="font-size: 10px; clear: both;"><?php echo JText::_('SPR_ITEM_DLS_COUNTTIP'); ?></span>
            </div>
            <div class="spr_confirm_buttons">
                <a href="#" onclick="cancelDl();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
                <a href="#" onclick="saveDl();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
            </div>
            </fieldset>
            </div>
            <div class="spr_tab_left" id="spr_item_imgs">
                <div class="spr_import">
                    <input id="spr_dlupload" name="spr_dlupload" type="file" />
                    <div id="dlqueue"></div>
                    <div id="spr_dlhtml5" style="display:none"><p><?php echo JText::_('SPR_ITEM_DLS_HTML5'); ?></p></div>
                </div>
                <h4><?php echo JText::_('SPR_ITEM_DLS_HEADING'); ?></h4>
                <br />
<table class="spr_table" id="dlsList" style="overflow: hidden">
<thead>
<tr>
<th nowrap="nowrap"><?php echo JText::_('SPR_ITEM_ORDER'); ?></th>
<th align="left"><?php echo JText::_('SPR_ITEM_DLS_NAME'); ?></th>
<th align="left"><?php echo JText::_('SPR_ITEM_DLS_EXT'); ?></th>
<th align="left" class='nowrap center' width='1%'><?php echo JText::_('SPR_ITEM_DLS_AVAIL'); ?></th>
<th align="left" class='nowrap center' width='1%'><?php echo JText::_('SPR_ITEM_DLS_AVAILTIMES'); ?></th>
<th class="nowrap center" width="1%"><?php echo JText::_('SPR_ITEM_STATUS'); ?></th>
<th class="nowrap center" width="1%"><?php echo JText::_('SPR_ITEM_ACTION'); ?></th>
</tr>
</thead>
<tbody class="ui-sortable" id="spr_item_dls_list">
<?php if(count($this->item->dls)>0) foreach($this->item->dls as $dl) {
    echo $dl->string;
} ?>
</tbody>
</table>
            </div>
        </div>
    
    <div id="metas">
        <div class="spr_tab_left">
        <fieldset class="spr_fieldset">
            <div class="spr_field">
                <label for="spr_items_meta_title"><?php echo JText::_('SPR_ITEM_METAS_PAGE'); ?></label>
                <input type="text" name="spr_items_meta_title" id="spr_items_meta_title" value="<?php echo $this->item->meta_title; ?>" />
            </div>
            <div class="spr_field">
                <label for="spr_items_meta_keys"><?php echo JText::_('SPR_ITEM_METAS_KEYS'); ?></label>
                <input type="text" name="spr_items_meta_keys" id="spr_items_meta_keys" value="<?php echo $this->item->meta_keys; ?>" />
            </div>
            <div class="spr_field">
                <label for="spr_items_meta_desc"><?php echo JText::_('SPR_ITEM_METAS_DESC'); ?></label>
                <textarea name="spr_items_meta_desc" id="spr_items_meta_desc"><?php echo $this->item->meta_desc; ?></textarea>
            </div>
        </fieldset>
        </div>
    </div>
</div>
</div>

<input type="hidden" name="spr_table" value="items" />
<input type="hidden" name="spr_id" id="spr_id" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="items" />

<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>

<div id="spr_popup">
<div id="spr_popup_close" class="spr_icon spr_icon_delete">&nbsp;</div>
<div id="spr_popup_header">
<img src='components/com_salespro/resources/images/salespro.png' />
</div>
<h2><?php echo JText::_('SPR_ITEM_IMAGE_SELECT'); ?></h2>
<div id="spr_popup_content">
<div id="spr_popup_images_list"><p><?php echo JText::_('SPR_ITEM_IMAGE_UPLOAD'); ?></p>
</div>
</div>
</div>
<script src="components/com_salespro/resources/uploadifive/jquery.uploadifive.min.js" type="text/javascript"></script>