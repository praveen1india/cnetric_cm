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

<div id="mod_salespro_basket">
<?php echo $basket; ?>
</div>

<style>
#mod_salespro_basket ul.spr_basket_items {
    margin: 10px 0;
    padding: 0;
    list-style: none;
}

#mod_salespro_basket li.spr_basket_item {
    padding: 0;
    margin: 0;
}

#mod_salespro_basket li.spr_basket_item a {
    background: -moz-linear-gradient(center top , #FFF 0%, #EDEDED 100%) repeat scroll 0% 0% transparent;
    display: block;
    border: 1px solid #ccc;
    padding: 5px 10px;
    margin: 0;
}

#mod_salespro_basket p.spr_basket_action {
    display: block;
    overflow: auto;
    clear: both;
    margin: 0;
}

#mod_salespro_basket a.spr_basket_checkout {
    float: right;
    margin: 0 12px 0 0;
    width: auto !important;
    height: auto !important;
    padding: 10px 20px 10px 20px !important;
    line-height: normal;
    border-radius: 6px;
    font-weight: normal;
    color: #fff;
    text-shadow: 0 1px rgba(255, 255, 255, 0.4);
    background: #c12200;
}


#mod_salespro_basket a.spr_basket_checkout:after {
    position: absolute;
    z-index: 2;
    width: 24px;
    height: 24px;
    margin-left: 4px;
    margin-top: -4px;
    border-top: thin solid rgba(0, 0, 0, 0.1);
    border-right: thin solid rgba(0, 0, 0, 0.3);
    border-top-right-radius: 4px;
    background-image: -webkit-gradient(linear, left top, #eee, #c6c6c6);
    background-image: -webkit-linear-gradient(left top, #eee, #c6c6c6);
    background-image: -moz-linear-gradient(left top, #eee, #c6c6c6);
    background-image: -ms-linear-gradient(left top, #eee, #c6c6c6);
    background-image: -o-linear-gradient(left top, #eee, #c6c6c6);
    background-image: linear-gradient(left top, #eee, #c6c6c6);
    box-shadow: inset 0 1px rgba(255, 255, 255, 0.3),
              1px 0 rgba(255, 255, 255, 0.3);
    content: "";
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    -o-transform: rotate(45deg);
    transform: rotate(45deg);
    background: #c12200;
}


#mod_salespro_basket a.spr_basket_checkout:hover {
    text-decoration: underline;
    cursor: pointer;
}

#mod_salespro_basket a.spr_basket_view {
    float: left;
}

</style>