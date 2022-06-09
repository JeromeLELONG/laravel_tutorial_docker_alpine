<?php

class Application_Model_ZCredentialMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_ZCredential');
        }
        return $this->_dbTable;
    }

    public function findGroup($group) {
        $request = $this->getDbTable()->select();
        $request->where("`group` like '$group'");
        $resultSet = $this->getDbTable()->fetchAll($request,0);
        $entries   = array();
        foreach ($resultSet as $row)
            $entries[] = new Application_Model_ZCredential($row);
        return $entries;
    }

    public function findAll(){
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row)
            $entries[] = new Application_Model_ZCredential($row);
        return $entries;
    }
}

