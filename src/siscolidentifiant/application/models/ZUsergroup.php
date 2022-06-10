<?php

class Application_Model_ZUsergroup
{
    private $id;
    private $user;
    private $group;

    public function __construct($row){
        $this->id = $row->id;
        $this->user = $row->user;
        $this->group = $row->group;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setGroup($group) {
        $this->group = $group;
    }

    public function getGroup() {
        return $this->group;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

}

