<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProEmails extends salesPro {
    public $_table = '#__spr_emails';
    public $id = 0;
    public $_vars = array(
        'id' => array('int', 6),
        'status' => array('int', 1),
        'trigger' => array('int', 4),
        'prodtypes' => array('json'),        
        'subject' => array('string', 255),
        'copy' => array('int', 1),
        'from' => array('string', 50),
        'content' => array('string'),
        'params' => array('string')
        );
    public $actions = array(
        'status',
        'setDefault',
        'getDummy'
    );
    public $emailtriggerOptions = array(
        '0' => 'SPR_ORDER_PLACED',
        '1' => 'SPR_ORDER_SHIPPED',
        '2' => 'SPR_ORDER_COMPLETED',
        '3' => 'SPR_ORDER_REFUNDED',
        '4' => 'SPR_ORDER_CANCELLED'
    );
    function __construct() {
        parent::__construct();
        foreach($this->emailtriggerOptions as $n=>$o) {
            $this->emailtriggerOptions[$n] = JText::_($o);
        }
    }
    function getSearch($table = '', $where = array(), $joins = array(), $order = array()) {
        //SET THE ORDERING
        $order = $this->order;
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
        $this->order['total'] = parent::getCount($table);
    }
    function getEmails() {
        $res = array();
        if (count($this->_searchRes) < 1) $this->getSearch();
        if (count($this->_searchRes) > 0 && $this->_searchRes !== false)
            foreach ($this->_searchRes as $id) {
                $res[] = $this->getEmail($id);
            }
        return $res;
    }
    function getEmail($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (!isset($object->id)) {
            $object = $this->getDefaultObject();
        }
        $object->prodtypes = json_decode($object->prodtypes);
        if(is_null($object->prodtypes)) $object->prodtypes = array();
        $object->prodtypes_name = '';
        $prodtypes = sprProdTypes::_getTypes();
        if(count($object->prodtypes) === 0) $object->prodtypes_name = JText::_('SPR_EMAIL_ALLTYPES');
        else if(count($object->prodtypes)>= count($prodtypes)) $object->prodtypes_name = JText::_('SPR_EMAIL_ALLTYPES');
        else foreach($object->prodtypes as $n=>$p) {
            if($n>0) $object->prodtypes_name .= ', ';
            $object->prodtypes_name .= sprProdTypes::_load($p)->name;
        }
        $object->trigger_name = $this->emailtriggerOptions[$object->trigger]; 
        return $object;
    }
    
    function getEmailsByTrigger($trigger=0) {
        $res = array();
        $this->getSearch($this->_table,array('trigger'=>$trigger,'status'=>'1'));
        if (count($this->_searchRes) > 0 && $this->_searchRes !== false)
            foreach ($this->_searchRes as $id) {
                $res[] = $this->getEmail($id);
            }
        return $res;
            
    }        
    
    function sendEmailNotification($trigger,$order_id) {
        /*// EMAIL TYPES //
        0 = Order is placed
        1 = Order is shipped
        2 = Order is completed
        3 = Order is refunded
        4 = Order is cancelled
        */
        
        $sales = new salesProSales;
        $sale = $sales->getSale($order_id);
        //GET APPLICABLE ITEM PRODUCT TYPES
        $product_types = array();
        foreach($sale->items as $i) {
            if(!in_array($i->type,$product_types)) $product_types[] = $i->type;
        }
        $to = $sale->user->email;
        $emails = $this->getEmailsByTrigger($trigger);

        foreach($emails as $email) {
            if($email->status !== '1') continue;
            $sendmail = 0;
            if(count($email->prodtypes)<1) $sendmail = 1;
            else foreach($email->prodtypes as $p) {
                if(in_array($p,$product_types)) $sendmail = 1;
            }
            if($sendmail === 0) continue;
            $from = $email->from;
            $subject = $email->subject;
            $content = $email->content;
            $content = $this->parseContent($content,$sale);            
            $mail = new salesProMailer;
            if(!$mail->setFrom($email->from)) return FALSE;
            if(!$mail->setTo($to)) return FALSE;
            if(!$mail->setSubject($subject)) return FALSE;
            if(!$mail->setBody($content)) return FALSE;
            if($email->copy == '1') if(!$mail->setBcc($email->from)) return FALSE;
            try {
                $res = $mail->send();
            }
            catch (Exception $e) {
                $msg = JText::_('SPR_EMAIL_CANTSEND');
                sprLog::_log($msg.$order_id);
            }
        }
    }
    
    function getDummyData($email_id = 0) {
        
        $sales = new salesProSales;
        $users = new salesProUsers;
        $id = (int) $this->db->getResult($sales->_table, 'id', array(), array('id'=>'DESC'));
        if($id === 0) return JText::_('SPR_EMAIL_PREVIEWDUMMY');
        $sale = $sales->getSale($id);
        $email = $this->getEmail($email_id);
        $content = $this->parseContent($email->content, $sale);
        return $content;
    }
    
    function parseContent($content,$sale) {
        
        $sales = new salesProSales;
        
        $css = "
        <style>
        html, body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table.spr_email_sales_items tr td {
            border-bottom: 1px dotted #ccf;
        }
        table.spr_email_sales_items thead th {
            background: #ddd;
            color: #333;
        }
        </style>
        ";
        
        //CREATE THE BILLING ADDRESS
        $bill_address = "";
        foreach($sale as $field=>$val) {
            if(strpos($field,'user_bill_') === 0) {
                if($val == '0') continue;
                if($field === 'user_bill_state') $val = sprRegions::_getName($val);
                elseif($field === 'user_bill_region_id' && $val > 0) $val = sprRegions::_getName($val);
                $bill_address.= $val."<br />";
            }
        }
        
        //CREATE THE DELIVERY ADDRESS
        $del_address = "";
        foreach($sale as $field=>$val) {
            if(strpos($field,'user_del_') === 0) {
                if($val == '0') continue;
                if($field === 'user_del_state') $val = sprRegions::_getName($val);
                elseif($field === 'user_del_region_id' && $val > 0) $val = sprRegions::_getName($val);
                $del_address.= $val."<br />";
            }
        }
        
        //CREATE THE LINK TO THE DOWNLOADS AREA
        $dl_link = JURI::root().'index.php?option=com_salespro&view=downloads';
        $dls = "<a href='{$dl_link}'>{$dl_link}</a>";
        
        //CREATE THE ORDER ITEMS
        $items = '<table class="spr_email_sales_items" cellspacing="0" cellpadding="10" border="0"><thead><tr><th colspan="2" align="left">'.JText::_('SPR_ITEM').'</th><th width="1%">'.JText::_('SPR_QUANTITY').'</th><th width="1%">'.JText::_('SPR_PRICE').'</th></tr></thead><tbody>';
        foreach($sale->items as $i) {
            $link = 'index.php?option=com_salespro&view=item&id='.$i->id;
            $link = JUri::root().$link;
            $items .= "<tr><td width='1%'><img src='".salesProImage::_($i->mainimage,80, 60)."' style='height: 60px'/></td><td><p><strong><a href='{$link}'>{$i->name}</a></strong></p>";
            $attributes = json_decode($i->attributes);
            if(count($attributes)>0) {
                $items .= "<ul>";
                foreach($attributes as $attr) {
                    $items .= "<li><strong>{$attr->name}:</strong> {$attr->value}</li>";
                    $items .= "</ul>";
                }
            }
            $items .= "</td><td align='center'>{$i->quantity}</td><td align='center'>{$i->f_price}</td></tr>";
        }
        //CREATE THE EXTRA ORDER INFORMATION
        $order = $items;

        $tax = '';
        if(count($sale->tax)>0) foreach($sale->tax as $t) {
            $order .= "<tr><th colspan='3' align='right' nowrap='nowrap'>{$t->name}</th><th align='right'>{$t->f_tax}</th></tr>";
            $tax .= '<p>{$t->name}: {$t->tax}</p>';
        }
        $order .= "<tr><th colspan='3' align='right' nowrap='nowrap'>".JText::_('SPR_SUBTOTAL')."</th><th>{$sale->f_grandtotal}</th></tr>";
        $order .= "<tr><th colspan='3' align='right' nowrap='nowrap'>".JText::_('SPR_EMAIL_SHIPPINGMETHOD')."</td><th align='right'>{$sale->shipping->f_price}</th></tr>";
        if($sale->payment->fee > 0) {
            $order .= "<tr><th colspan='3' align='right' nowrap='nowrap'>".JText::_('SPR_PAYMENTFEE')."</th><th align='right'>{$sale->payment->f_fee}</th></tr>";
        }
        $order .= "<tr><th colspan='3' align='right' nowrap='nowrap'>".JText::_('SPR_TOTAL')."</td><th align='right'>{$sale->f_grandtotal}</th></tr>";
        $order .= "</tbody></table>";
        $items .= "</tbody></table>";
        
        //SET UP THE SHIPPING INFORMATION
        $shipping_details = '';
        if($sale->shipping->name !='') {
            $shipping_details = "<p>".JText::_('SPR_EMAIL_SHIPPINGMETHOD').": <strong>{$sale->shipping->name}</strong></p>";
            $shipping_details .= "<p>{$sale->shipping->info}</p>";
            $order .= $shipping_details;
        }
        
        //SET UP THE PAYMENT INFORMATION
        $payment_details = '';
        if($sale->payment->name !='') {
            $payment_details = "<p>".JText::_('SPR_EMAIL_PAYMENTMETHOD').": <strong>{$sale->payment->name}</strong></p>";
            $payment_details .= "<p>{$sale->payment->info}</p>";
            $order .= $payment_details;
        }
        
        //PARSE THE USER NAME
        $name = (strlen($sale->user_bill_name)>0) ? $sale->user_bill_name : ($sale->user->name);

        //PARSE THE EMAIL CONTENT
        $content = "<!DOCTYPE HTML><html><head><meta http-equiv='content-type' content='text/html' />{$css}</head><body>".$content."</body></html>";
        $content = str_ireplace('{user_name}',$name,$content);
        $content = str_ireplace('{user_email}',$sale->user->email,$content);
        $content = str_ireplace('{order_details}',$order,$content);
        $content = str_ireplace('{order_date}',$sale->date,$content);
        $content = str_ireplace('{order_status}','<b>'.$sales->_statusOptions[$sale->status].'</b>',$content);
        $content = str_ireplace('{total_quantity}',$sale->quantity,$content);
        $content = str_ireplace('{total_weight}',$sale->weight,$content);
        $content = str_ireplace('{net_price}',$sale->f_price,$content);
        $content = str_ireplace('{shipping_type}',$sale->shipping->name,$content);
        $content = str_ireplace('{shipping_price}',$sale->shipping->f_price,$content);
        $content = str_ireplace('{taxes}',$tax,$content);
        $content = str_ireplace('{grand_total}',$sale->f_grandtotal,$content);
        $content = str_ireplace('{billing_name}',$sale->user_bill_name,$content);
        $content = str_ireplace('{delivery_name}',$sale->user_del_name,$content);
        $content = str_ireplace('{items}',$items,$content);
        $content = str_ireplace('{billing_address}',$bill_address,$content);
        $content = str_ireplace('{delivery_address}',$del_address,$content);
        $content = str_ireplace('{download_link}',$dls,$content);
        $content = str_ireplace('{note}',$sale->note,$content);
        return $content;        
    }
    function ajaxGetDummy () {
        $id = (int)$_POST['email_id'];
        echo $this->getDummyData($id);
    }
}