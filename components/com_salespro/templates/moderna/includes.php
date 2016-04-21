<?php
/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

//CORE TEMPLATE INCLUDE FILE - THIS HOLDS ALL EXTRA JAVASCRIPT AND STYLESHEET REFERENCES

defined('_JEXEC') or die( 'Restricted access' );

$templates = new salesProTemplates;
$mytemplate = $templates->getDefault();

//STYLESHEETS
$template_stylesheet = 'components/com_salespro/templates/'.$mytemplate->alias.'/css/stylesheet.css';
if(file_exists($template_stylesheet)) 
    $document->addStyleSheet($template_stylesheet);
$color_stylesheet = 'components/com_salespro/templates/'.$mytemplate->alias.'/css/colors/'.$mytemplate->color.'.css';
if(file_exists($color_stylesheet)) 
    $document->addStyleSheet($color_stylesheet);

//JAVASCRIPT
$template_script = 'components/com_salespro/templates/'.$mytemplate->alias.'/js/salespro.js';
if(file_exists($template_script)) 
    $document->addScript($template_script);
        //INCLUDE THE STANDARD JAVASCRIPT
        $document->addScript('components/com_salespro/resources/js/salespro.js');