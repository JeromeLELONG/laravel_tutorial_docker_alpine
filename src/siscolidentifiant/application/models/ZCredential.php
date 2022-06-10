<?php

class Application_Model_ZCredential
{
    private $group;
    private $action;

    public function __construct($row){
        $this->action = $row->action;
        $this->group = $row->group;
    }

    public function getAction(){
        return $this->action;
    }

    public function setAction($action){
        $this->action = $action;
    }

    public function getGroup(){
        return $this->group;
    }

    public function setGroup($group){
        $this->group = $group;
    }

}

