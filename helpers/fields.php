<?php

class stb_fieldsattachFields
{
    private $fields = array();

    function __construct() {
        $sql = "SELECT id, title FROM #__fieldsattach";
        $db = JFactory::getDBO();
        $db->setQuery($sql);
        $rows = $db->loadObjectList();
        foreach ( $rows as $row )
            $this->fields[$row->id] = $row;
    }

    static function getInstance() {
        static $instance;
        if ( !$instance ) {
            $instance = new stb_fieldsattachFields();
        }
        return $instance;
    }

    function getFieldTitle($id) {
        if ( !empty($this->fields[$id]) )
            return $this->fields[$id]->title;
        return null; // throw an exception?
    }

    function getFieldId($label) {
        return array_search($this->fields, $label);
    }
}

