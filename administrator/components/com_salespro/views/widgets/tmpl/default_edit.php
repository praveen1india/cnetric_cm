<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_WIDGET_HEADING'), 'salespro');
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
    <h1><?php echo JText::_('SPR_WIDGET_MANAGER'); ?></h1>
</div>

<div id="spr_main">
<?php if(strlen($this->widget->about)>0) echo "<div class='spr_tip'><p>{$this->widget->about}</p></div>"; ?>

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

    <fieldset class="spr_fieldset">
    
    <div class="spr_field">
    <label for="spr_widgets_id"><?php echo JText::_('SPR_WIDGET_ID'); ?></label>
    <span id="spr_widgets_id"><strong><?php echo $this->widget->id; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_widgets_type"><?php echo JText::_('SPR_WIDGET_TYPE'); ?></label>
    <span id="spr_widgets_type"><strong><?php echo $this->widget->type; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_widgets_name"><?php echo JText::_('SPR_WIDGET_NAME'); ?></label>
    <input id="spr_widgets_name" name="spr_widgets_name" type="text" value="<?php echo $this->widget->name; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_widgets_status"><?php echo JText::_('SPR_WIDGET_ENABLED'); ?></label>
    <select id="spr_widgets_status" name="spr_widgets_status">
        <?php echo sprConfig::_options($this->widget->status,'yesno'); ?>
    </select>
    </div>

    </fieldset>
    <br />
    
    <fieldset class="spr_fieldset">
    <h5><?php echo JText::_('SPR_WIDGET_SETTINGS'); ?></h5>
    
    <div class="spr_field">
    <label for="spr_widgets_params[showtitle]"><?php echo JText::_('SPR_WIDGET_SHOWTITLE'); ?></label>
    <select id="spr_widgets_params[showtitle]" name="spr_widgets_params[showtitle]">
        <?php echo sprConfig::_options($this->widget->params->showtitle,'yesno'); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_widgets_params[btn]"><?php echo JText::_('SPR_WIDGET_SHOWBTN'); ?></label>
    <select id="spr_widgets_params[btn]" name="spr_widgets_params[btn]">
        <?php echo sprConfig::_options($this->widget->params->btn,'yesno'); ?>
    </select>
    </div>

    <?php if($this->widget->type !== 'showcase') { ?>
    <div class="spr_field">
    <label for="spr_widgets_params[layout]"><?php echo JText::_('SPR_WIDGET_LAYOUT'); ?></label>
    <select id="spr_widgets_params[layout]" name="spr_widgets_params[layout]">
        <?php echo sprConfig::_options($this->widget->params->layout,'layout'); ?>
    </select>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_widgets_params[cols]"><?php echo JText::_('SPR_WIDGET_COLS'); ?></label>
    <select id="spr_widgets_params[cols]" name="spr_widgets_params[cols]">
        <?php echo sprConfig::_options($this->widget->params->cols,array(1=>1,2=>2,3=>3,4=>4,5=>5)); ?>
    </select>
    </div>
    <?php } ?>
    
    <div class="spr_field">
    <label for="spr_widgets_params[count]"><?php echo JText::_('SPR_WIDGET_COUNT'); ?></label>
    <input id="spr_widgets_params[count]" name="spr_widgets_params[count]" type="text" value="<?php echo $this->widget->params->count; ?>" style="width: 150px;" />
    </div>
    
    </fieldset>
    
    <fieldset class="spr_fieldset">
    <h5><?php echo JText::_('SPR_WIDGET_VIEWS'); ?></h5>
    
    <div class="spr_field">
    <label for="spr_widgets_views[home]"><?php echo JText::_('SPR_WIDGET_VIEWHOME'); ?></label>
    <select id="spr_widgets_views[home]" name="spr_widgets_views[home]">
        <?php echo sprConfig::_options($this->widget->views->home,'yesno'); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_widgets_views[category]"><?php echo JText::_('SPR_WIDGET_VIEWCAT'); ?></label>
    <select id="spr_widgets_views[category]" name="spr_widgets_views[category]">
        <?php echo sprConfig::_options($this->widget->views->category,'yesno'); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_widgets_views[item]"><?php echo JText::_('SPR_WIDGET_VIEWITEM'); ?></label>
    <select id="spr_widgets_views[item]" name="spr_widgets_views[item]">
        <?php echo sprConfig::_options($this->widget->views->item,'yesno'); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_widgets_views[basket]"><?php echo JText::_('SPR_WIDGET_VIEWBASKET'); ?></label>
    <select id="spr_widgets_views[basket]" name="spr_widgets_views[basket]">
        <?php echo sprConfig::_options($this->widget->views->basket,'yesno'); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_widgets_views[checkout]"><?php echo JText::_('SPR_WIDGET_VIEWCHECKOUT'); ?></label>
    <select id="spr_widgets_views[checkout]" name="spr_widgets_views[checkout]">
        <?php echo sprConfig::_options($this->widget->views->checkout,'yesno'); ?>
    </select>
    </div>

    <div class="spr_field">
    <label for="spr_widgets_views[thankyou]"><?php echo JText::_('SPR_WIDGET_VIEWTHANKYOU'); ?></label>
    <select id="spr_widgets_views[thankyou]" name="spr_widgets_views[thankyou]">
        <?php echo sprConfig::_options($this->widget->views->thankyou,'yesno'); ?>
    </select>
    </div>
    </fieldset>

<input type="hidden" name="spr_table" value="widgets" />
<input type="hidden" name="spr_id" id="spr_id" value="<?php echo $this->widget->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="widgets" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>
</div>
</div>
