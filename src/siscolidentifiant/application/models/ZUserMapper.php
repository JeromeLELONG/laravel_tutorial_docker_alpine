<?php

class Application_Model_ZUserMapper
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
            $this->setDbTable('Application_Model_DbTable_ZUser');
        }
        return $this->_dbTable;
    }

    public function find($login) {
        $request = $this->getDbTable()->select();
        $request->where("`login` LIKE '$login'");
        $request->order("nom");
        $resultSet = $this->getDbTable()->fetchAll($request,0);
        $entries   = array();
        foreach ($resultSet as $row)
            $entries[] = new Application_Model_ZUser($row);
        return $entries;
    }

    public function findAll() {
        $request = $this->getDbTable()->select();
        $request->order("nom");
        $resultSet = $this->getDbTable()->fetchAll($request,0);
        $entries   = array();
        foreach ($resultSet as $row)
            $entries[] = new Application_Model_ZUser($row);
        return $entries;
    }

    public function ldapFind($uid) {
        $request="(uid=$uid)";
        //$config = new Zend_Config_Ini('../application/configs/application.ini',APPLICATION_ENV);
        //$options = $config->ldap->toArray();
        $config["server1"]["host"] = getenv('SISCOLIDENTIFIANT_LDAP_1_HOSTNAME');
        $config["server1"]["accountDomainName"] = getenv('SISCOLIDENTIFIANT_LDAP_1_ACCOUNT_DOMAIN_NAME');
        $config["server1"]["accountDomainNameShort"] = getenv('SISCOLIDENTIFIANT_LDAP_1_ACCOUNT_DOMAIN_NAME_SHORT');
        $config["server1"]["baseDn"] = getenv('SISCOLIDENTIFIANT_LDAP_1_BASE_DN');
        $config["server1"]["bindRequiresDn"] = getenv('SISCOLIDENTIFIANT_LDAP_1_BIND_REQUIRE_DN');
        $config["server1"]["useSsl"] = false;
        $config["server1"]["useStartTls"] = false;
        $config["server1"]["port"] = 389;
        $config["server2"]["host"] = getenv('SISCOLIDENTIFIANT_LDAP_2_HOSTNAME');
        $config["server2"]["accountDomainName"] = getenv('SISCOLIDENTIFIANT_LDAP_2_ACCOUNT_DOMAIN_NAME');
        $config["server2"]["accountDomainNameShort"] = getenv('SISCOLIDENTIFIANT_LDAP_2_ACCOUNT_DOMAIN_NAME_SHORT');
        $config["server2"]["baseDn"] = getenv('SISCOLIDENTIFIANT_LDAP_2_BASE_DN');
        $config["server2"]["bindRequiresDn"] = getenv('SISCOLIDENTIFIANT_LDAP_2_BIND_REQUIRE_DN');
        $config["server2"]["accountFilterFormat"] = getenv('SISCOLIDENTIFIANT_LDAP_2_ACCOUNT_FILTER_FORMAT');
        $config["server2"]["useSsl"] = false;
        $config["server2"]["useStartTls"] = false;
        $config["server2"]["port"] = 389;


        $options = $config;
        $ldap = new Zend_Ldap($options["server2"]);
        $ldap->bind();
        $foundusers=array();
        try{
//              $users =$ldap->searchEntries("(&(objectClass=etudiantcnam)(|(attributbase=intec)(attributbase=siscol)(attributbase=grafic))$request)",
//                        $options["server1"]["baseDn"],Zend_Ldap::SEARCH_SCOPE_SUB,arsearchEntriesray("sn","givenname","sapid","uid","numerocartegrafic","supanncodeine","supannmailperso"));
            foreach($ldap->searchEntries("(uid=$uid*)",$options["server2"]["baseDn"],array("sn","givenname","uid")) as $user){
                $newUser = new Application_Model_ZUser();
                if(isset($user["sn"])) $newUser->setNom($user["sn"][0]);
                if(isset($user["givenname"])) $newUser->setPrenom($user["givenname"][0]);
                if(isset($user["uid"])) $newUser->setLogin($user["uid"][0]);
                $foundusers[]=$newUser;
            }
        }catch(Exception $e){
        }
        return $foundusers;
    }

    public function ldapGet($uid) {
        $request="(uid=$uid)";
        //$config = new Zend_Config_Ini('../application/configs/application.ini',APPLICATION_ENV);
        //$options = $config->ldap->toArray();
        $config["server1"]["host"] = getenv('SISCOLIDENTIFIANT_LDAP_1_HOSTNAME');
        $config["server1"]["accountDomainName"] = getenv('SISCOLIDENTIFIANT_LDAP_1_ACCOUNT_DOMAIN_NAME');
        $config["server1"]["accountDomainNameShort"] = getenv('SISCOLIDENTIFIANT_LDAP_1_ACCOUNT_DOMAIN_NAME_SHORT');
        $config["server1"]["baseDn"] = getenv('SISCOLIDENTIFIANT_LDAP_1_BASE_DN');
        $config["server1"]["bindRequiresDn"] = getenv('SISCOLIDENTIFIANT_LDAP_1_BIND_REQUIRE_DN');
        $config["server1"]["useSsl"] = false;
        $config["server1"]["useStartTls"] = false;
        $config["server1"]["port"] = 389;
        $config["server2"]["host"] = getenv('SISCOLIDENTIFIANT_LDAP_2_HOSTNAME');
        $config["server2"]["accountDomainName"] = getenv('SISCOLIDENTIFIANT_LDAP_2_ACCOUNT_DOMAIN_NAME');
        $config["server2"]["accountDomainNameShort"] = getenv('SISCOLIDENTIFIANT_LDAP_2_ACCOUNT_DOMAIN_NAME_SHORT');
        $config["server2"]["baseDn"] = getenv('SISCOLIDENTIFIANT_LDAP_2_BASE_DN');
        $config["server2"]["bindRequiresDn"] = getenv('SISCOLIDENTIFIANT_LDAP_2_BIND_REQUIRE_DN');
        $config["server2"]["accountFilterFormat"] = getenv('SISCOLIDENTIFIANT_LDAP_2_ACCOUNT_FILTER_FORMAT');
        $config["server2"]["useSsl"] = false;
        $config["server2"]["useStartTls"] = false;
        $config["server2"]["port"] = 389;

        $options = $config;
        $ldap = new Zend_Ldap($options["server2"]);
        $ldap->bind();
        $newUser = new Application_Model_ZUser();
        try{
            //$user =$ldap->getEntry("uid=$uid,".$options["server2"]["baseDn"],array("sn","givenname","uid"),true);
            $result = $ldap->searchEntries("(uid=$uid)",$options["server2"]["baseDn"],array("sn","givenname","uid"));
    
            if(isset($result[0]["sn"])) $newUser->setNom($result[0]["sn"][0]);
            if(isset($result[0]["givenname"])) $newUser->setPrenom($result[0]["givenname"][0]);
            if(isset($result[0]["uid"])) $newUser->setLogin($result[0]["uid"][0]);
        }catch(Exception $e){
        }
        return $newUser;
    }


    public function save($user) {
        $exists = $this->find($user->getLogin());
        if(count($exists)==0) {
            $this->getDbTable()->insert(array('login'=>$user->getLogin(),'nom'=>$user->getNom(),'prenom'=>$user->getPrenom()));
        }
    }

    public function delete($login) {
        $this->getDbTable()->delete("`login`='$login'");
    }
}

