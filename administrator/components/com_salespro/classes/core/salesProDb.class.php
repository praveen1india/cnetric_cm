<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProDb {
    private $debug = 1;
    private function getWhere($field = '', $val = '') {
        $db = JFactory::getDBO();
        $ret = '';
        //GET MULTIPLE CONDITIONS
        if (is_array($val)) {
            foreach ($val as $a => $b) {
                $pos = array_search($a, array_keys($val));
                //GET A RANGE (e.g. dates)
                if ($a === 'from') {
                    if ($pos > 0) $ret .= " AND ";
                    $condition = '>=';
                    $ret .= $db->quoteName($field) . $condition . $db->quote($b);
                } else if ($a === 'to') {
                    if ($pos > 0) $ret .= " AND ";
                    $condition = '<=';
                    $ret .= $db->quoteName($field) . $condition . $db->quote($b);
                
                } else if (is_numeric($a)) {
                    if ($pos === 0) $ret .= '(';
                    else  $ret .= ' OR ';
                    $condition = '=';
                    $ret .= $db->quoteName($field) . $condition . $db->quote($b);
                    if ($pos === (count($val) - 1)) $ret .= ')';

                //GET LIKE FROM DIFFERENT FIELDS
                } else if(is_array($b)) {
                    $ret .= '(';
                    foreach($b as $x=>$c) {
                        if($x > 0) $ret .= ' OR ';
                        $condition = ' LIKE '; 
                        $ret .= $db->quoteName($field).$condition.$db->quote('%"'.$c.'"%');
                    }
                    $ret .= ')';
                } else {
                    if ($pos === 0) $ret .= '(';
                    else  $ret .= ' OR ';
                    //$condition = ' LIKE ';
                    //$ret .= $db->quoteName($a) . $condition . $db->quote('%'.$b.'%');
                    $condition = ' = ';
                    $ret .= $db->quoteName($a) . $condition . $db->quote($b);
                    if ($pos === (count($val) - 1)) $ret .= ')';
                }
            }

        //GET A SINGLE CONDITION
        } else {
            $condition = '=';
            $conditions = array('=', '!=', '<', '>', '<=', '>=', 'LIKE');
            foreach ($conditions as $c) {
                if (strpos($val, $c) === 0) {
                    $condition = $c;
                    $val = substr($val, strlen($c));
                    break;
                }
            }
            $ret = $db->quoteName($field) . $condition . $db->quote($val);
        }
        return $ret;
    }
    function getJoin($join = array()) {
        if (count($join) < 5) return false;
        $type = $join[0];
        $table = $join[1];
        $prefix = $join[2];
        $on = $join[3];
        $condition = $join[4];
        $db = JFactory::getDBO();
        if($type === 'like') {
            $ret = array(strtoupper('left'), $db->quoteName($table, $prefix) . ' ON (' . $db->quoteName($on) . ' LIKE CONCAT ("%",'. $db->quoteName($condition) . ',"%"))');
        } else {
            $ret = array(strtoupper($type), $db->quoteName($table, $prefix) . ' ON (' . $db->quoteName($on) . '=' . $db->quoteName($condition) . ')');
        }
        return $ret;
    }
    private function getData($list = 0, $array = 0, $table = '', $fields = array(),
        $where = array(), $order = array()) {
        $db = JFactory::getDBO();
        $conditions = array();
        if (count($where) > 0)
            foreach ($where as $field => $val) {
                $conditions[] = $this->getWhere($field, $val);
            }
        $sort = array();
        if(isset($order['sort']) && is_array($order['sort'])) {
            foreach($order['sort'] as $field=>$dir) {
                $dir = ($dir === 'DESC') ? 'DESC' : 'ASC';
                $sort[] = $db->quoteName($field). ' ' . $dir;
            }
        } else if (isset($order['sort']) && isset($order['dir'])) {
            $dir = ($order['dir'] === 'DESC') ? 'DESC' : 'ASC';
            $sort[] = $db->quoteName($order['sort']) . ' ' . $dir;
        }
        $myfields = array();
        if (is_array($fields))
            foreach ($fields as $field) {
                $myfields[] = $field;
            }
        else {
            $myfields[] = $fields;
        }
        $query = $db->getQuery(true);
        $query->select($db->quoteName($myfields))->from($db->quoteName($table));
        if (count($conditions) > 0) $query->where($conditions);
        if (count($sort) > 0) $query->order($sort);
        try {      
            $db->setQuery($query);
            if ($list === 1) {
                if ($array === 1) $res = $db->loadAssocList();
                else  $res = $db->loadObjectList();
            }
            else {
                if ($array === 1) $res = $db->loadAssoc();
                else  $res = $db->loadObject();
            }
        }
        catch (exception $e) {
            if ($this->debug == 1) {
                $this->error($db->getErrorMsg());
            }
            return false;
        }
        if (sizeof($res) > 0) return $res;
        else  return array();
    }
    function getCount($table = '', $where = array(), $joins = array()) {
        $prefix = 'z';
        $myfield = 'id';
        $db = JFactory::getDBO();
        //SET WHERE CONDITIONS
        $conditions = array();
        if (count($where) > 0)
            foreach ($where as $field => $val) {
                $conditions[] = $this->getWhere($field, $val);
            }
        //SET JOIN CONDITIONS
        $myjoins = array();
        if (count($joins) > 0) {
            foreach ($joins as $join) {
                $myjoins[] = $this->getJoin($join);
            }
        }
        //BUILD THE QUERY
        $query = $db->getQuery(true);
        $query->select($db->quoteName($prefix . '.' . $myfield))->from($db->quoteName($table,
            $prefix));
        if (count($conditions) > 0) $query->where($conditions);
        if (count($myjoins) > 0)
            foreach ($myjoins as $join) {
                $query->join($join[0], $join[1]);
            }
        //LIMIT THE QUERY
        if (isset($order['page']) && isset($order['limit'])) {
            $start = $order['page'] * $order['limit'];
            $end = $order['limit'];
            $query->setLimit($end, $start);
        }
        try {
            $db->setQuery($query);
            $db->execute();
            $res = $db->getNumRows();
        }
        catch (exception $e) {
            if ($this->debug == 1) {
                $this->error($db->getErrorMsg());
            }
            return false;
        }
        if (sizeof($res) > 0) return $res;
        else  return array();
    }
    function getSearch($table = '', $where = array(), $joins = array(), $order = array()) {
        $prefix = 'z';
        $myfield = 'id';
        $db = JFactory::getDBO();
        //SET WHERE CONDITIONS
        $conditions = array();
        if (count($where) > 0) foreach ($where as $field => $val) {
            $conditions[] = $this->getWhere($field, $val);
        }
        //SET JOIN CONDITIONS
        $myjoins = array();
        if (count($joins) > 0) {
            foreach ($joins as $join) {
                $myjoins[] = $this->getJoin($join);
            }
        }
        //SET ORDERING
        $sort = array();
        if(isset($order['sort'])) {
            if($order['sort'] === 'RAND()') {
                $sort[] = $order['sort'];
            } else {
                $dir = 'DESC';
                if(isset($order['dir']) && $order['dir'] === 'ASC') $dir = 'ASC';
                $sort[] = $db->quoteName($order['sort']) . ' ' . $dir;
            }
        }
        //BUILD THE QUERY
        $query = $db->getQuery(true);
        $query->select(' DISTINCT '.$db->quoteName($prefix . '.' . $myfield))->from($db->quoteName($table,
            $prefix));
        if (count($conditions) > 0) $query->where($conditions);
        if (count($sort) > 0) $query->order($sort);
        if (count($myjoins) > 0)
            foreach ($myjoins as $join) {
                $query->join($join[0], $join[1]);
            }
        //LIMIT THE QUERY
        if (isset($order['page']) && isset($order['limit'])) {
            $start = (int)$order['page'] * $order['limit'];
            $end = (int)$order['limit'];
            //$query->setLimit($end,$start); (NOT WORKING RELIABLY!)
            $query .= " LIMIT {$start},{$end}";
        }
        try {
            $db->setQuery($query);
            $res = $db->loadAssocList();
        }
        catch (exception $e) {
            if ($this->debug == 1) {
                $this->error($db->getErrorMsg());
            }
            return false;
        }
        $ret = array();
        if (sizeof($res) > 0) {
            foreach ($res as $a => $b) {
                foreach ($b as $c) {
                    $ret[] = $c;
                }
            }
        }
        return $ret;
    }
    function insertData($table = '', $data = array()) {
        $db = JFactory::getDBO();
        $fields = array();
        $cells = array();
        foreach ($data as $field => $cell) {
            $fields[] = $db->quoteName($field);
            if(is_array($cell)) $cell = json_encode($cell);
            $cells[] = $db->quote($cell);
        }
        $query = $db->getQuery(true);
        $query->insert($db->quoteName($table))->columns($fields)->values(implode(',', $cells));
        try {
            $db->setQuery($query);
            $db->query();
        }
        catch (exception $e) {
            if ($this->debug == 1) {
                $this->error($db->getErrorMsg());
            }
            return FALSE;
        }
        return $db->insertid();
    }
    function updateData($table = '', $data = array(), $where = array()) {
        if (count($where) < 1) {
            $error = JText::_('SPR_DB_UPDATE_ERROR');
            $this->error($error);
            return FALSE;
        }
        $db = JFactory::getDBO();
        $fields = array();
        if (count($data) > 0)
            foreach ($data as $field => $cell) {
                if(is_array($cell)) $cell = json_encode($cell);
                $fields[] = $db->quoteName($field) . '=' . $db->quote($cell);
            }
        $conditions = array();
        if (count($where) > 0)
            foreach ($where as $field => $val) {
                $conditions[] = $this->getWhere($field, $val);
            }
        $query = $db->getQuery(true);
        $query->update($db->quoteName($table))->set($fields);
        if (count($conditions) > 0) $query->where($conditions);
        try {
            $db->setQuery($query);
            $db->query();
        }
        catch (exception $e) {
            if ($this->debug == 1) {
                $this->error($db->getErrorMsg());
            }
            return FALSE;
        }
        if($db->getAffectedRows() > 0) return TRUE;
        return FALSE;
    }
    function deleteData($table = '', $where = array(), $order = array()) {
        $db = JFactory::getDBO();
        $conditions = array();
        if (count($where) > 0)
            foreach ($where as $field => $val) {
                $conditions[] = $this->getWhere($field, $val);
            }
        $sort = array();
        if (count($order) > 0)
            foreach ($order as $field => $val) {
                $dir = ($val === 'DESC') ? 'DESC' : 'ASC';
                $sort[] = $db->quoteName($field) . ' ' . $dir;
            }
        $query = $db->getQuery(true);
        $query->delete($db->quoteName($table));
        if (count($conditions) > 0) $query->where($conditions);
        if (count($sort) > 0) $query->order($sort);
        try {
            $db->setQuery($query);
            $db->query();
        }
        catch (exception $e) {
            if ($this->debug == 1) {
                $this->error($db->getErrorMsg());
            }
            return false;
        }
        
        return TRUE;
    }
    function getResult($table = '', $fields = array(), $where = array(), $order = array()) {
        $db = JFactory::getDBO();
        $conditions = array();
        if (count($where) > 0)
            foreach ($where as $field => $val) {
                $conditions[] = $this->getWhere($field, $val);
            }
        $sort = array();
        if (count($order) > 0)
            foreach ($order as $field => $val) {
                $dir = ($val === 'DESC') ? 'DESC' : 'ASC';
                $sort[] = $db->quoteName($field) . ' ' . $dir;
            }
        $query = $db->getQuery(true);
        $query->select($db->quoteName($fields))->from($db->quoteName($table));
        if (count($conditions) > 0) $query->where($conditions);
        if (count($sort) > 0) $query->order($sort);
        try {
            $db->setQuery($query);
            $res = $db->loadResult();
        }
        catch (exception $e) {
            if ($this->debug == 1) {
                $this->error($db->getErrorMsg());
            }
            return false;
        }
        if (sizeof($res) > 0) return $res;
        else  return false;
    }
    function getObj($table = '', $fields = array(), $where = array(), $order = array()) {
        return $this->getData(0, 0, $table, $fields, $where, $order);
    }
    function getObjList($table = '', $fields = array(), $where = array(), $order = array()) {
        return $this->getData(1, 0, $table, $fields, $where, $order);
    }
    function getAssoc($table = '', $fields = array(), $where = array(), $order = array()) {
        return $this->getData(0, 1, $table, $fields, $where, $order);
    }
    function getAssocList($table = '', $fields = array(), $where = array(), $order = array()) {
        return $this->getData(1, 1, $table, $fields, $where, $order);
    }
    function error($msg) {
        echo $msg;
        die();
    }
}
