<?php

class stb_fieldsattachAutoloader
{
    private $context;
    private $article;
    private $params;
    private $page;

    private $faValues = array();

    function __construct($context, &$article, &$params, $page) {
        $this->context = $context;
        $this->article = $article;
        $this->params = $params;
        $this->page = $page;
    }

    function getValueById(/* PHP 7+ use typehint: int */ $id) {
        if ( !is_int($id) )
            throw new Exception('Argument 1 passed to '.__CLASS__.'::'.__FUNCTION__.'() must be an integer, '.gettype($id).' given');

        // try and load the fieldsAttach fields
        $this->getFields();

        if ( array_key_exists($id, $this->faValues) )
            return $this->faValues[$id]->value;
        return '';
    }

    function getValueByName(/* PHP 7+ use typehint: string */ $name) {
        // expect at least 1 letter
        if ( !preg_match('~[a-z]~i', $name) ) 
            throw new Exception('Argument 1 passed to '.__CLASS__.'::'.__FUNCTION__.'() must be a string, '.gettype($name).' given');

        // try and load the fieldsAttach fields
        $this->getFields();

        if ( array_key_exists($name, $this->faValues) )
            return $this->faValues[$name]->value;
        return '';
    }

    function getFields() {
        if ( count($this->faValues) )
            return;

        $faFieldMap = stb_fieldsattachFields::getInstance();

        $db = JFactory::getDBO();
        $sql = "SELECT * FROM #__fieldsattach_values WHERE articleid = ".$db->quote($this->article->id);
        $db->setQuery($sql);
        $rows = $db->loadObjectList();
        foreach ( $rows as $row ) {
            $field_name = $faFieldMap->getFieldTitle($row->fieldsid);
            if ( $field_name === NULL ) {
                // This field was deleted, but FA has left orphaned records in the db
                continue;
            }

            $field_id = $row->fieldsid;
            $field_alias= preg_replace('~([^a-z0-9])~', '_', strtolower($field_name));

            $this->faValues[$field_id] = $row;
            $this->faValues[$field_name] = &$this->faValues[$field_id];
            $this->faValues[$field_alias] = &$this->faValues[$field_id];
        }
    }
}

