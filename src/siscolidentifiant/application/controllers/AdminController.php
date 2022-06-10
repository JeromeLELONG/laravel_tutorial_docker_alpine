<?php

class AdminController extends Zend_Controller_Action {

    private $auth = null;

    public function init() {
        $this->auth = Zend_Auth::getInstance();
        if (Application_Model_AccessControl::inGroup("admin"))
            $this->view->admin = true;
        if ($this->auth->hasIdentity())
            $this->view->islogged = true;
        else {
            $form = new Application_Form_Login();
            $form->setAction($this->_helper->url("login", "index", null));
            $this->view->loginForm = $form;
        }
        Application_Model_AccessControl::isAllowed($this);
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function credentialAction() {
        $credentialMapper = new Application_Model_ZCredentialMapper();
        $this->view->credentials = $credentialMapper->findAll();
    }

    public function userAction() {
        $form = new Application_Form_Searchuser();
        $mapper = new Application_Model_ZUserMapper();
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) && $this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPost();
            $this->view->foundusers = $mapper->ldapFind($values["login"]);
        }
        $usergroupMapper = new Application_Model_ZUsergroupMapper();
        $usersgroups = $usergroupMapper->findAll();
        $this->view->groups = array();
        foreach ($usersgroups as $usergroup) {
            $this->view->groups [$usergroup->getUser()][] = $usergroup->getGroup();
        }
//        foreach($this->view->groups as $login=>$usergroup) {
//            $this->view->groups[$login]=implode(',',$usergroup);
//        }
        $this->view->users = $mapper->findAll();
        $this->view->form = $form;
    }

    public function usergroupAction() {
        
    }

    public function adduserAction() {
        $mapper = new Application_Model_ZUserMapper();
        $user = $mapper->ldapGet($this->getRequest()->getParam("login"));
        $mapper->save($user);
        $this->getHelper('Redirector')->gotosimple('user', 'admin', null);
    }

    public function adduseringroupAction() {
        $this->_helper->layout->disableLayout();
        $form = new Application_Form_Adduseringroup();
        $groupMapper = new Application_Model_ZGroupMapper();
        foreach ($groupMapper->findAll() as $group) {
            $groups[$group->getGroup()] = $group->getGroup();
        }
        $form->getElement("group")->setMultiOptions($groups);
        if ($this->getRequest()->getParam("group")) {
            $usergroupMapper = new Application_Model_ZUsergroupMapper();
            $usergroup = array('user' => $this->getRequest()->getParam("login"),
                'group' => $this->getRequest()->getParam("group"));
            $exists = $usergroupMapper->find($usergroup);
            if (count($exists) == 0) {
                $usergroupMapper->insert($usergroup);
            }
        }
        else
            $this->view->form = $form;
    }

    public function deleteuserAction() {
        if ($this->getRequest()->getParam("login")) {
            $usergroupMapper = new Application_Model_ZUsergroupMapper();
            $usergroupMapper->delete($this->getRequest()->getParam("login"));
            $userMapper = new Application_Model_ZUserMapper();
            $userMapper->delete($this->getRequest()->getParam("login"));
        }
        $this->getHelper('Redirector')->gotosimple('user', 'admin', null);
    }

    public function deleteusergroupAction() {
        if ($this->getRequest()->getParam("login")) {
            $usergroupMapper = new Application_Model_ZUsergroupMapper();
            echo $this->getRequest()->getParam("login")." ".$this->getRequest()->getParam("group");
            $usergroupMapper->delete($this->getRequest()->getParam("login"),$this->getRequest()->getParam("group"));
        }
        //$this->getHelper('Redirector')->gotosimple('user', 'admin', null);
    }

    public function ldapgetAction() {
        // action body
    }

}

