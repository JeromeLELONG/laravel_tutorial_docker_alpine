<?php

class IndexController extends Zend_Controller_Action
{
    private $auth;
    
    public function init()
    {
        $this->auth = Zend_Auth::getInstance();
        if(Application_Model_AccessControl::inGroup("admin"))
            $this->view->admin=true;
        if($this->auth->hasIdentity())
            $this->view->islogged=true;
        else {
            $form = new Application_Form_Login();
            $form->setAction($this->_helper->url("login","index",null));
            $this->view->loginForm=$form;
        }
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        $this->view->login=true;
        if($this->getRequest()->isGet() && $this->getRequest()->getParam("alert"))
            $this->view->alert = $this->getRequest()->getParam("alert");
        $form=new Application_Form_Login();
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            try {
                $username = $this->_request->getParam('login');
                $password = $this->_request->getParam('password');


                $config["host"] = getenv('SISCOLIDENTIFIANT_LDAP_2_HOSTNAME');
                $config["accountDomainName"] = getenv('SISCOLIDENTIFIANT_LDAP_2_ACCOUNT_DOMAIN_NAME');
                $config["accountDomainNameShort"] = getenv('SISCOLIDENTIFIANT_LDAP_2_ACCOUNT_DOMAIN_NAME_SHORT');
                $config["baseDn"] = getenv('SISCOLIDENTIFIANT_LDAP_2_BASE_DN');
                $config["bindRequiresDn"] = getenv('SISCOLIDENTIFIANT_LDAP_2_BIND_REQUIRE_DN');
                $config["accountFilterFormat"] = getenv('SISCOLIDENTIFIANT_LDAP_2_ACCOUNT_FILTER_FORMAT');
                $config["useSsl"] = false;
                $config["useStartTls"] = false;
                $config["port"] = 389;

                $options["server2"] = $config;
                
                unset($options['log_path']);
                $adapter = new Zend_Auth_Adapter_Ldap($options,$username,$password);
                //var_dump($adapter);
                
                //print_r($adapter);
                $result = $this->auth->authenticate($adapter);
                //var_dump($result);
                // on log l'auditeur
                if($result->isValid()) {
                    $this->auth->getStorage()->write($username);
                    $this->getHelper('Redirector')->gotosimple('index','index',null);
                }
                $this->view->alert="Mot de passe ou login incorrect !";
            }catch(Exception $e) {
                $this->view->alert="Connexion impossible !";
            }
        }
        $this->view->form=$form;
        // action body
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
            return $this->_helper->redirector('index');
    }


}





