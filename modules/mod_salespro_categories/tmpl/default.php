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

<ul class="mod_salespro_categories">
<?php foreach ($list as $cat) {
    $link = sprCategories::_directLink($cat->id,$cat->name,$cat->alias);
    echo "<li><a href='{$link}'>{$cat->name}</a></li>";
} ?>
</ul>

<style>
ul.mod_salespro_categories {
    list-style: none;
    margin: 0;
    padding: 0;
}

ul.mod_salespro_categories a {
    display: block;
}
</style>