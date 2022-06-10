<?php
class Application_Model_ZUsergroupMapper {
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
            $this->setDbTable('Application_Model_DbTable_ZUsergroup');
        }
        return $this->_dbTable;
    }

    public function findByUser($user) {
        $request = $this->getDbTable()->select();
        $request->where("`user` like '$user'");
        $resultSet = $this->getDbTable()->fetchAll($request,0);
        $entries   = array();
        foreach ($resultSet as $row)
            $entries[] = new Application_Model_ZUsergroup($row);
        return $entries;
    }

    public function find($usergroup) {
        $request = $this->getDbTable()->select();
        $request->where("`user` like '".$usergroup['user']."' AND `group` like '".$usergroup["group"]."'");
        $resultSet = $this->getDbTable()->fetchAll($request,0);
        $entries   = array();
        foreach ($resultSet as $row)
            $entries[] = new Application_Model_ZUsergroup($row);
        return $entries;
    }

    public function findAll() {
        $request = $this->getDbTable()->select();
        $request->order("group");
        $resultSet = $this->getDbTable()->fetchAll($request,0);
        $entries   = array();
        foreach ($resultSet as $row)
            $entries[] = new Application_Model_ZUsergroup($row);
        return $entries;
    }

    public function insert($usergroup) {
        $this->getDbTable()->insert($usergroup);
    }

    public function delete($login,$group=null) {
        $request = "`user`='$login'";
        if($group)$request .=" AND `group` = '$group' ";
        $this->getDbTable()->delete($request);
    }
}