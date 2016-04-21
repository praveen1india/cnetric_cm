<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.$this->title, 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
JToolBarHelper::custom( 'apply', 'apply', 'apply', JText::_('SPR_APPLY'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo $this->title; ?></h1>
</div>

<div id="spr_tabs">
    <div class="spr_tabs" >
        <ul>
            <li><a href="#details"><?php echo JText::_('SPR_CAT_DETAILS'); ?></a></li>
            <li><a href="#design"><?php echo JText::_('SPR_CAT_DESIGN'); ?></a></li>
            <li><a href="#metas"><?php echo JText::_('SPR_CAT_METATAGS'); ?></a></li>
        </ul>

        <form action="" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

            <div id="details">
                <div class="spr_notab">
                <fieldset class="spr_fieldset">
                <div class="spr_field">
                <label for="spr_categories_name"><?php echo JText::_('SPR_CAT_CTITLE'); ?></label>
                <input id="spr_categories_name" name="spr_categories_name" type="text" value="<?php echo $this->category->name; ?>" />
                </div>

                <div class="spr_field">
                <label for="spr_categories_alias"><?php echo JText::_('SPR_CAT_CALIAS'); ?></label>
                <input id="spr_categories_alias" name="spr_categories_alias" type="text" placeholder="<?php echo JText::_('SPR_CAT_CALIAS_INFO'); ?>" value="<?php echo $this->category->alias; ?>" />
                </div>

                <div class="spr_field">
                <label for="spr_categories_parent"><?php echo JText::_('SPR_CAT_CPARENT'); ?></label>
                <select name="spr_categories_parent" id="spr_categories_parent" style="width: 275px;">
                <option value="0">-- <?php echo JText::_('SPR_CAT_SELECTLVL'); ?> --</option>
                <?php if(count($this->categories)>0)
                    foreach($this->categories as $c) {
                        echo $this->class->showCatOption($c,$this->category->parent,$this->category->id);
                } ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_status"><?php echo JText::_('SPR_CAT_CSTATUS'); ?></label>
                <select name="spr_categories_status" id="spr_categories_status">
                <?php echo sprCategories::_options($this->category->status,'pub'); ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_image"><?php echo JText::_('SPR_CAT_CIMAGE'); ?></label>
                <input id="spr_categories_image" name="spr_categories_image" type="file" value="<?php echo $this->category->image; ?>" />
                </div>

                <div class="spr_field">
                <label for="spr_categories_desc"><?php echo JText::_('SPR_CAT_CDESC'); ?></label>
                <textarea name="spr_categories_desc" id="spr_categories_desc" class="spr_input"><?php echo $this->category->desc; ?></textarea>
                </div>

                <div class="spr_field">
                <label for="spr_categories_desc"><?php echo JText::_('Short Description'); ?></label>
                <textarea name="short_desc" id="spr_categories_short_desc" class="spr_input"><?php echo $this->category->short_des; ?></textarea>
                </div>
                </fieldset>
                </div>
            </div>

            <div id="design" class="tab-pane">
                <div class="spr_notab">
                <fieldset class="spr_fieldset">

                <div class="spr_field">
                <label for="spr_categories_params[show_title]"><?php echo JText::_('SPR_CAT_SHOWTITLE'); ?></label>
                <select name="spr_categories_params[show_title]" id="spr_categories_params[show_title]">
                <?php echo sprCategories::_options($this->category->params->show_title,'yesno'); ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_params[show_desc]"><?php echo JText::_('SPR_CAT_SHOWDESC'); ?></label>
                <select name="spr_categories_params[show_desc]" id="spr_categories_params[show_desc]">
                <?php echo sprCategories::_options($this->category->params->show_desc,'yesno'); ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_params[show_image]"><?php echo JText::_('SPR_CAT_SHOWIMAGE'); ?></label>
                <select name="spr_categories_params[show_image]" id="spr_categories_params[show_image]">
                <?php echo sprCategories::_options($this->category->params->show_image,'yesno'); ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_params[show_sortbar]"><?php echo JText::_('SPR_CAT_SHOWSORT'); ?></label>
                <select name="spr_categories_params[show_sortbar]" id="spr_categories_params[show_sortbar]">
                <?php echo sprCategories::_options($this->category->params->show_sortbar,'yesno'); ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_params[show_pagesbar]"><?php echo JText::_('SPR_CAT_SHOWPAGES'); ?></label>
                <select name="spr_categories_params[show_pagesbar]" id="spr_categories_params[show_pagesbar]">
                <?php echo sprCategories::_options($this->category->params->show_pagesbar,'showbar'); ?>
                </select>
                </div>

                <hr />
                <h5><?php echo JText::_('SPR_CAT_GRIDLAYOUT'); ?></h5>

                <div class="spr_field">
                <label for="spr_categories_params[layout]"><?php echo JText::_('SPR_CAT_CLAYOUT'); ?></label>
                <select name="spr_categories_params[layout]" id="spr_categories_params[layout]">
                <?php echo sprCategories::_options($this->category->params->layout,'layout'); ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_params[boxcols]"><?php echo JText::_('SPR_CAT_PAGEBOXES'); ?></label>
                <select id="spr_categories_params[boxcols]" name="spr_categories_params[boxcols]">
                <?php echo sprCategories::_options($this->category->params->boxcols,array(1=>'1',2=>'2',3=>'3',4=>'4',5=>'5')); ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_params[items]"><?php echo JText::_('SPR_CAT_PAGEITEMS'); ?></label>
                <input id="spr_categories_params[items]" name="spr_categories_params[items]" type="text" value="<?php echo $this->category->params->items; ?>" style="width:150px" />
                </select>
                </div>

                <hr />
                <h5><?php echo JText::_('SPR_CAT_SUBCATS'); ?></h5>

                <div class="spr_field">
                <label for="spr_categories_params[subcats]"><?php echo JText::_('SPR_CAT_SHOWSUBCS'); ?></label>
                <select name="spr_categories_params[subcats]" id="spr_categories_params[subcats]">
                <?php echo sprCategories::_options($this->category->params->subcats,'yesno'); ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_params[subcatitems]"><?php echo JText::_('SPR_CAT_SHOWSUBCITEMS'); ?></label>
                <select name="spr_categories_params[subcatitems]" id="spr_categories_params[subcatitems]">
                <?php echo sprCategories::_options($this->category->params->subcatitems,'yesno'); ?>
                </select>
                </div>

                <div class="spr_field">
                <label for="spr_categories_params[subcategory_levels]"><?php echo JText::_('SPR_CAT_SUBCDEPTH') ?></label>
                <select name="spr_categories_params[subcategory_levels]" id="spr_categories_params[subcategory_levels]">
                <?php echo sprCategories::_options($this->category->params->subcategory_levels,array(0=>'0',1,2,3,4,5)); ?>
                </select>
                </div>

                </fieldset>
                </div>
            </div>

            <div id="metas" class="tab-pane">
                <div class="spr_notab">
                <fieldset class="spr_fieldset">
                    <div class="spr_field">
                        <label for="spr_categories_meta_title"><?php echo JText::_('SPR_CAT_PAGETITLE'); ?></label>
                        <input type="text" name="spr_categories_meta_title" id="spr_categories_meta_title" value="<?php echo $this->category->meta_title; ?>" />
                    </div>
                    <div class="spr_field">
                        <label for="spr_categories_meta_keys"><?php echo JText::_('SPR_CAT_PAGEKEYS'); ?></label>
                        <input type="text" name="spr_categories_meta_keys" id="spr_categories_meta_keys" value="<?php echo $this->category->meta_keys; ?>" />
                    </div>
                    <div class="spr_field">
                        <label for="spr_categories_meta_desc"><?php echo JText::_('SPR_CAT_PAGEDESC'); ?></label>
                        <textarea name="spr_categories_meta_desc" id="spr_categories_meta_desc" class="spr_input"><?php echo $this->category->meta_desc; ?></textarea>
                    </div>
                </fieldset>
                </div>
            </div>

        <input type="hidden" name="spr_table" value="categories" />
        <input type="hidden" name="spr_id" value="<?php echo $this->category->id; ?>" />
        <input type="hidden" name="extension" value="com_salespro" />
        <input type="hidden" name="task" value=""  />
        <input type="hidden" name="view" value="categories" />
        <?php echo JHTML::_( 'form.token' ); ?>
        </form>

    </div>

</fieldset>
</div>
</div>
</div>