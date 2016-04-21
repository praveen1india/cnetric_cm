<?php
/* -------------------------------------------
Module: mod_salespro_categories
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die;
?> 

<div id="mod_salespro_menu">
<form id="spr_top_menu" action="<?php echo $activeurl; ?>" method="post">
<ul class="mod_salespro_menu">
<?php foreach ($list as $li) {
    echo "<li>{$li}</li>";
} ?>
</ul>
</form>
</div>
<style>
#mod_salespro_menu {
    overflow: auto;
    clear: both;
    margin: 5px 0 0 10px;
	float: right;
    min-width: 530px;
}

#mod_salespro_menu form {
    margin: 0;
    padding: 0;
}

#mod_salespro_menu ul.mod_salespro_menu {
    position: relative;
    left: inherit;
    top: inherit;
    right: inherit;
    list-style: none;
    margin:  0 0 10px 0;
    padding: 0;
    overflow: auto;
    clear: both;
    width: auto;
    float: right;
}

#mod_salespro_menu ul.mod_salespro_menu li {
    float: left;
    margin: 0;
    padding: 0 12px;
    border-right: 1px solid #ddd;
}

#mod_salespro_menu ul.mod_salespro_menu li:last-child {
    border: 0;
}
#mod_salespro_menu select.currency_select {
    line-height: 12px;
    width: auto;
    margin: 0;
    padding: 0;
    height: 18px;
    border: 0;
}
</style>

<script>
jQuery('.currency_select').change(function() {
    jQuery('#spr_top_menu').submit();
});
</script>