<?php

class Application_Model_AccessControl {
    private static $acl;
    private static $group;

    private static function loadAcl() {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) return;
        //$this->getHelper('Redirector')->gotosimple('index','index',null,array('alert'=>"Utilisateur non authentifié !"));
        self::$acl = new Zend_Acl();
        $zusergroupMapper = new Application_Model_ZUsergroupMapper();
        $zcredentialMapper = new Application_Model_ZCredentialMapper();
        $login=$auth->getIdentity();
        $actions = array();
        foreach($zusergroupMapper->findByUser($login) as $usergroup) {
            self::$acl->addRole(new Zend_Acl_Role($usergroup->getGroup()));
            foreach($zcredentialMapper->findGroup($usergroup->getGroup()) as $zc) {
                try {
                    $action = $zc->getAction();
                    if(!in_array($action,$actions)) {
                        $actions[]=$action;
                        self::$acl->add(new Zend_Acl_Resource($action));
                    }
                } catch(Exception $e) {

                }
                try {
                    self::$acl->allow($usergroup->getGroup(), $zc->getAction());
                } catch(Exception $e) {

                }
            }
        }
    }

    public static function isAllowed($ctrl) {
        try {
            $auth = Zend_Auth::getInstance();
            if (!$auth->hasIdentity())
                $ctrl->getHelper('Redirector')->gotosimple('login','index',null,array('alert'=>"Utilisateur non authentifié !"));
            if(!self::$acl) self::loadAcl();
            $login=$auth->getIdentity();
            $zusergroupMapper = new Application_Model_ZUsergroupMapper();
            $isAllowed = false;
            foreach($zusergroupMapper->findByUser($login) as $usergroup) {
                $action = strtolower($ctrl->getRequest()->getControllerName().$ctrl->getRequest()->getActionName());
                if(self::$acl->isAllowed($usergroup->getGroup(), $action)) {
                    $isAllowed = true;
                }
            }
            if(!$isAllowed)
                $ctrl->getHelper('Redirector')->gotosimple('login','index',null,array('alert'=>"Droits insuffisants !"));
        }catch(Exception $e) {
            $ctrl->getHelper('Redirector')->gotosimple('login','index',null,array('alert'=>"Droits insuffisants !"));
        }
    }

    public static function inGroup($group) {
        $auth = Zend_Auth::getInstance();
        if(!self::$acl) self::loadAcl();
        $login=$auth->getIdentity();
        $zusergroupMapper = new Application_Model_ZUsergroupMapper();
        foreach($zusergroupMapper->findByUser($login) as $usergroup) {
            if($usergroup->getGroup() == $group) return true;
        }
        return false;
    }
}

