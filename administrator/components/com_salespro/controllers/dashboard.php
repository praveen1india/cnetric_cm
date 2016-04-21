<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2015 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

require_once(JPATH_ADMINISTRATOR.'/components/com_salespro/controllers/controller.php');

class salesProDashboardController extends salesProControllerAdmin {

	function __construct( $default = array()) {	   
		parent::__construct( $default );
	}
    
    function getChart() {
        if(!$this->auth()) return;
        $sales = new sprSales;
        $range = (isset($_GET['range'])) ? $_GET['range'] : 'thisweek';
        switch($range) {
                
                case 'lastweek':
                    $end = date("Y-m-d 23:59:59", strtotime('-1 week'));
                    $start = date("Y-m-d 00:00:00", strtotime('-2 weeks'));
                    break;
                case 'thismonth':
                    $end = date("Y-m-d 23:59:59");
                    $start = date("Y-m-d 00:00:00", strtotime('-1 month'));
                    break;
                case 'lastmonth':
                    $end = date("Y-m-d 23:59:59", strtotime('-1 month'));
                    $start = date("Y-m-d 00:00:00", strtotime('-2 months'));
                    break;
                case 'thisyear':
                    $end = date("Y-m-d 23:59:59");
                    $start = date("Y-m-d 00:00:00", strtotime('-1 year'));
                    break;
                case 'lastyear':
                    $end = date("Y-m-d 23:59:59", strtotime('-1 year'));
                    $start = date("Y-m-d 00:00:00", strtotime('-2 years'));
                    break;                
                case 'thisweek':
                default:
                    $end = date("Y-m-d 23:59:59");
                    $start = date("Y-m-d 00:00:00", strtotime('-1 week'));
                    break;
        }
        $mysales = $sales->_getSalesByDateRange($start,$end);
        $myvars = array();
        $quantity = 0;
        $grandtotal = 0;
        if(count($mysales)>0) foreach($mysales as $date=>$value) {
            $myvars[] = array($date.' 00:00:00',$value['value']);
            $grandtotal += $value['value'];
            $quantity += $value['quantity'];
        }
        $grandtotal = sprCurrencies::_format($grandtotal);
        
        //GET NEW USERS
        $users = sprUsers::_getUserCountByDateRange($start,$end);
        $json = array('chart'=>array($myvars),'grandtotal'=>$grandtotal,'quantity'=>$quantity,'users'=>$users);
        echo json_encode($json);
        die();
    }
}