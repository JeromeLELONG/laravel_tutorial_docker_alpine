<?php

class Application_Model_ZGroup
{
    private $group;

    public function __construct($row) {
        $this->group = $row->group;
    }

    public function getGroup(){
        return $this->group;
    }

    public function setGroup($group) {
        $this->group = $group;
    }
}

