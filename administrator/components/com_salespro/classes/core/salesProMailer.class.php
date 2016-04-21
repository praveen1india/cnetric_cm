<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProMailer extends salesPro {
    function __construct() {
        parent::__construct();
        $this->start();
    }
    function start() {
        require_once $this->_adminpath . 'lib/Swift/swift_required.php';
        require_once $this->_adminpath . 'lib/Swift/swift_init.php';
        $this->email = Swift_Message::newInstance();
    }
    function setFrom($email = '') {
        $res = Swift_Validate::email($email);
        if($res !== 1) {
            new salesProLog(JText::_('SPR_MAIL_FROMERROR').': '.$email);
            return FALSE;
        }
        $this->email->setFrom($email);
        return TRUE;
    }
    function setTo($email = '') {
        $res = Swift_Validate::email($email);
        if($res !== 1) {
            new salesProLog(JText::_('SPR_MAIL_TOERROR').': '.$email);
            return FALSE;
        }
        $this->email->setTo($email);
        return TRUE;
    }
    function setCc($email = '') {
        $res = Swift_Validate::email($email);
        if($res !== 1) {
            new salesProLog(JText::_('SPR_MAIL_CCERROR').': '.$email);
            return FALSE;
        }
        $this->email->setCc($email);
        return TRUE;
    }
    function setBcc($email = '') {
        $res = Swift_Validate::email($email);
        if($res !== 1) {
            new salesProLog(JText::_('SPR_MAIL_BCCERROR').': '.$email);
            return FALSE;
        }
        $this->email->setBcc($email);
        return TRUE;
    }
    function setBody($msg = '') {
        $plaintext = str_replace("<br />", "\n", nl2br($msg));
        $this->email->setBody($msg, 'text/html');
        $this->email->addPart($plaintext, 'text/plain');
        return TRUE;
    }
    function setAttachment($file = '') {
        $this->email->attach(Swift_Attachment::fromPath($file));
        return TRUE;
    }
    function setSubject($subject = '') {
        $this->email->setSubject($subject);
        return TRUE;
    }
    function send() {
        $transport = Swift_MailTransport::newInstance();
        $mymailer = Swift_Mailer::newInstance($transport);
        $mymailer->send($this->email);
        
        
    }
}
