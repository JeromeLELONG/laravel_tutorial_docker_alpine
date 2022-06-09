<?php

class Application_Model_EtudiantMapper {

    private $config;
    private $memory;

    public function __construct() {
        $this->config = new Zend_Config_Ini('../application/configs/application.ini', APPLICATION_ENV);
    }

    public function findEtudiantsBy($values) {
        
        //$options = $this->config->ldap->toArray();
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

        $ldap = new Zend_Ldap($options["server1"]);
        $request = null;
        if ($values) {
            foreach ($values as $name => $value)
                $request[] = "($name=$value)";
            $request = implode(' ', $request);
        }
        $ldap->bind(getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));
        $etudiants = array();
        try {
            $users = $ldap->searchEntries("(&$request)", "ou=People,o=Etudiant,dc=cnam,dc=fr", Zend_Ldap::SEARCH_SCOPE_SUB, array("sapid"));
            foreach ($users as $user) {
                $etudiant = new Application_Model_Etudiant();
                $etudiant->setDn($user["dn"]);

                if (isset($user["sapid"])) {
                    $etudiant->setSapid($user["sapid"][0]);
                    //$etudiant->setDateNaissance($this->getDate($user["sapid"][0]));
                }
                if (isset($user["numerocartegrafic"]))
                    $etudiant->setNumgrafic($user["numerocartegrafic"][0]);
                if (isset($user["supanncodeine"]))
                    $etudiant->setIne($user["supanncodeine"][0]);
                if (isset($user["sn"]))
                    $etudiant->setNom($user["sn"][0]);
                if (isset($user["givenname"]))
                    $etudiant->setPrenom($user["givenname"][0]);
                if (isset($user["supannmailperso"]))
                    $etudiant->setEmail($user["supannmailperso"][0]);
                if (isset($user["uid"]))
                    $etudiant->setLogin($user["uid"][0]);
                $etudiants[$etudiant->getNom() . $etudiant->getPrenom() . $etudiant->getSapid()] = $etudiant;
            }
            ksort($etudiants);

            //print_r($this->view->entries);
        } catch (Exception $e) {
            echo $e->getMessage();
            //mail("jc.barrez@cnam.fr","Erreur recherche étudiant ",$e->getMessage());
            //$logger->err($e->getMessage());
        }
        return $etudiants;
    }

    public function findBySapid($sapid) {
        $sapid = trim($sapid);
        $request = "(sapid=$sapid)";

        //$options = $this->config->ldap->toArray();
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

        var_dump($options["server1"]);

        $options = $config;
        $ldap = new Zend_Ldap($options["server1"]);
        //echo $this->config->annuaire->get("login");
        $ldap->bind(getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));
        $etudiant = null;
        try {
            $reqStr = "(|(&(objectClass=candidatcnam)$request)(&(objectClass=etudiantcnam)(|(attributbase=siscol)(attributbase=intec)(attributbase=grafic))$request))";
            $users = $ldap->searchEntries($reqStr, $options["server1"]["baseDn"], Zend_Ldap::SEARCH_SCOPE_SUB, array("sn", "givenname", "sapid", "uid", "numerocartegrafic", "supanncodeine", "supannmailperso"));
            foreach ($users as $user) {
                $etudiant = new Application_Model_Etudiant();
                $etudiant->setDn($user["dn"]);
                if (isset($user["sapid"])) {
                    $etudiant->setSapid($user["sapid"][0]);
                   // $etudiant->setDateNaissance($this->getDate($user["sapid"][0]));
                }
                if (isset($user["numerocartegrafic"]))
                    $etudiant->setNumgrafic($user["numerocartegrafic"][0]);
                if (isset($user["supanncodeine"]))
                    $etudiant->setIne($user["supanncodeine"][0]);
                if (isset($user["sn"]))
                    $etudiant->setNom($user["sn"][0]);
                if (isset($user["givenname"]))
                    $etudiant->setPrenom($user["givenname"][0]);
                if (isset($user["supannmailperso"]))
                    $etudiant->setEmail($user["supannmailperso"][0]);
                if (isset($user["uid"]))
                    $etudiant->setLogin($user["uid"][0]);
                // $etudiants[$etudiant->getNom() . $etudiant->getPrenom() . $etudiant->getSapid()] = $etudiant;
            }

            //print_r($this->view->entries);
        } catch (Exception $e) {
            echo $e->getMessage();
            //mail("jc.barrez@cnam.fr","Erreur recherche étudiant ",$e->getMessage());
            //$logger->err($e->getMessage());
        }
        return $etudiant;
    }

    public function diffMem (){
        $current = memory_get_usage();
        $dbt = debug_backtrace();
        echo $dbt[0]["line"].":".number_format($current - $this->memory, 2)."<br>";
        $this->memory = $current;
    }
    
    public function findBy($values) {
        $request = array();
        $request2 = array();
        foreach ($values as $name => $value) {
            if ($value) {
                $value = trim($value);
                $value2 = $value;
                if ($name == "sapid" && strlen($value) < 10) {
                    $value = str_repeat("0", 10 - strlen($value)) . $value;
//                    if(strlen($value2)==9)
//                        $value2 = "0".$value2;
//                    else
//                        $value2 = "01".str_repeat("0",8-strlen($value2)).$value2;
                }
                //echo "<br>$value $value2<br>";
                if ($name == "supanncodeine") {
                    $value = strtolower($value);
                    $value2 = strtoupper($value);
                    //echo "$value $value2";
                }
                //echo "<br>$value $value2<br>";
                $request[] = "($name=$value)";
                $request2[] = "($name=$value2)";
            }
            unset($name,$value,$value2);
        }
        $request = implode(' ', $request);
        $request2 = implode(' ', $request2);
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
        //$options = $this->config->ldap->toArray();
        $ldap = new Zend_Ldap($options["server1"]);
        $ldap->bind(getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));
        $etudiants = array();
        try {
            $users = $ldap->searchEntries("(|(&(objectClass=candidatcnam)$request)(&(objectClass=etudiantcnam)(|(attributbase=siscol)(attributbase=intec)(attributbase=grafic))$request))", 
                    $options["server1"]["baseDn"], Zend_Ldap::SEARCH_SCOPE_SUB, array("sn", "givenname", "sapid", "uid", "numerocartegrafic", "supanncodeine", "supannmailperso","dateFin"));
            $users2 = $ldap->searchEntries("(|(&(objectClass=candidatcnam)$request2)(&(objectClass=etudiantcnam)(|(attributbase=siscol)(attributbase=intec)(attributbase=grafic))$request2))",
                    $options["server1"]["baseDn"], Zend_Ldap::SEARCH_SCOPE_SUB, array("sn", "passwordgrafic", "givenname", "sapid", "uid", "numerocartegrafic", "supanncodeine", "supannmailperso","datefin"));
                    $users = array_merge($users, $users2);
            unset($ldap);
            unset($users2);
            foreach ($users as $user) {
                $etudiant = new Application_Model_Etudiant();
                $etudiant->setDn($user["dn"]);
                if(strpos($user["dn"],"backup")) $etudiant->setStatutLdap ("backup");
                if (isset($user["sapid"])) 
                    $etudiant->setSapid($user["sapid"][0]);
                if (isset($user["numerocartegrafic"]))
                    $etudiant->setNumgrafic($user["numerocartegrafic"][0]);
                if (isset($user["supanncodeine"]))
                    $etudiant->setIne($user["supanncodeine"][0]);
                if (isset($user["sn"]))
                    $etudiant->setNom($user["sn"][0]);
                if (isset($user["givenname"]))
                    $etudiant->setPrenom($user["givenname"][0]);
                if (isset($user["supannmailperso"]))
                    $etudiant->setEmail($user["supannmailperso"][0]);
                if (isset($user["uid"]))
                    $etudiant->setLogin($user["uid"][0]);
                if (isset($user["datefin"]))
                    $etudiant->setDatefin($user["datefin"][0]);
                if (isset($user["passwordgrafic"]))
                    $etudiant->setPassword($user["passwordgrafic"][0]);
                $etudiants[$etudiant->getNom() . $etudiant->getPrenom() . $etudiant->getSapid()] = $etudiant;
                unset($user,$etudiant);
            }
            ksort($etudiants);

            //print_r($this->view->entries);
        } catch (Exception $e) {
            echo $e->getMessage();
            //mail("jc.barrez@cnam.fr","Erreur recherche étudiant ",$e->getMessage());
            //$logger->err($e->getMessage());
        }
        return $etudiants;
    }

    public function testPassword($login, $password) {
        //$options = $this->config->ldap->toArray();
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
        $ldap = new Zend_Ldap($options["server1"]);
//        $login = explode(",",$login);
//        $login = explode("=",$login[0]);
//        $login = $login[1];
        try {
            // Mot de passe réinitialisé à une valeur utilisable 07/09/2010
            //$ldap->bind($login,"$password#$password");
            $ldap->bind($login, $password);
            return true;
        } catch (Exception $e) {
            try {
                $ldap->bind($login, "$password#$password");
            } catch (Exception $e) {
                return false;
            }
        }
    }

    public function changeEmail($dn, $email) {
        //$options = $this->config->ldap->toArray();
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
        $ldap = new Zend_Ldap($options["server1"]);
        try {
            $ldap->bind(getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));
            $user = $ldap->getEntry($dn, array("supannmailperso"), true);
            Zend_Ldap_Attribute::setAttribute($user, "supannmailperso", $email);
            //$ldap->update($user["dn"], $user);
            ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
            $ldapconn = ldap_connect('ldap://'.getenv('SISCOLIDENTIFIANT_LDAP_1_HOSTNAME'), 389);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); 
            ldap_bind($ldapconn, getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));

            $pwdarr = array('supannmailperso' => $email);
            $result = ldap_mod_replace ($ldapconn, $dn, $pwdarr);
        } catch (Exception $e) {
            return false;
        }
    }

    public function reinitPassword($dn) {
        //$options = $this->config->ldap->toArray();
        $config["server1"]["host"] = getenv('SISCOLIDENTIFIANT_LDAP_1_HOSTNAME');
        $config["server1"]["accountDomainName"] = getenv('SISCOLIDENTIFIANT_LDAP_1_ACCOUNT_DOMAIN_NAME');
        $config["server1"]["accountDomainNameShort"] = getenv('SISCOLIDENTIFIANT_LDAP_1_ACCOUNT_DOMAIN_NAME_SHORT');
        $config["server1"]["baseDn"] = getenv('SISCOLIDENTIFIANT_LDAP_1_BASE_DN');
        $config["server1"]["bindRequiresDn"] = getenv('SISCOLIDENTIFIANT_LDAP_1_BIND_REQUIRE_DN');
        $config["server1"]["useSsl"] = false;
        $config["server1"]["useStartTls"] = false;
        $config["server1"]["port"] = 389;
        $config["server1"]["username"] = getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN');
        $config["server1"]["password"] = getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD');
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
        $ldap = new Zend_Ldap($options["server1"]);
        try {
            $result_bind = $ldap->bind(getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));
            $user = $ldap->getEntry($dn, array("supanncodeine", "supannmailperso", "cn", "uid", "passwordgrafic"), true);
            $newpwd = $user["passwordgrafic"][0];
            // Mot de passe réinitialisé à une valeur utilisable 07/09/2010
            //$newpwd = $newpwd."#".$newpwd;
            Zend_Ldap_Attribute::setAttribute($user, "userpassword", $newpwd);
            $user2["userpassword"][0] = $newpwd;
            list($y, $m, $d, $h, $i, $s) = explode("-", date("Y-m-d-H-i-s"));
//            $exptime=date("YmdHis",mktime($h,$i+$this->config->annuaire->get("exptime"),$s,$m,$d,$y))."Z";
//            if($this->config->annuaire->get("expirationtimeFieldname")) Zend_Ldap_Attribute::setAttribute($user,$config->annuaire->get("expirationtimeFieldname"),$exptime);
            //$result = $ldap->update($user["dn"], $user);
            
            // A REMPLACER -------
            //$result = $ldap->update($user["dn"], $user2);

            ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
            $ldapconn = ldap_connect('ldap://'.getenv('SISCOLIDENTIFIANT_LDAP_1_HOSTNAME'), 389);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); 
            ldap_bind($ldapconn, getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));

            $pwdarr = array('userpassword' => $newpwd);
            $result = ldap_mod_replace ($ldapconn, $user["dn"], $pwdarr);
            
            $ldap_aud = new Zend_Ldap($options["server1"]);
            if ($ldap_aud->bind($user["dn"], $newpwd))
                return $user;
            return false;
        } catch (Exception $e) {

            //echo str_replace("#", "<br>",$e);
            return false;
        }
    }

    public function updateLdap($dn, $sapid) {
        //$options = $this->config->ldap->toArray();
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
        $ldap = new Zend_Ldap($options["server1"]);
        try {
            $ldap->bind(getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));
            $user = $ldap->getEntry($dn, array("cn", "uid", "sapid"), true);
            Zend_Ldap_Attribute::setAttribute($user, "sapid", $sapid);
            //$ldap->update($dn, $user);
            ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
            $ldapconn = ldap_connect('ldap://'.getenv('SISCOLIDENTIFIANT_LDAP_1_HOSTNAME'), 389);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); 
            ldap_bind($ldapconn, getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));

            $pwdarr = array('sapid' => $sapid);
            $result = ldap_mod_replace ($ldapconn, $dn, $pwdarr);
            
            return $user;
        } catch (Exception $e) {
            //echo str_replace("#", "<br>",$e);
            return false;
        }
    }

    public function find($dn) {
        //$options = $this->config->ldap->toArray();
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
        $ldap = new Zend_Ldap($options["server1"]);
        $ldap->bind(getenv('SISCOLIDENTIFIANT_ANNUAIRE_LOGIN'), getenv('SISCOLIDENTIFIANT_ANNUAIRE_ADMIN_PASSWORD'));
        $etudiant = new Application_Model_Etudiant();
        try {
            $user = $ldap->getEntry($dn, array("supanncodeine", "datenaissance", "supannmailperso", "sn", "givenname", "sapid", "uid", "passwordgrafic", "numerocartegrafic"), true);
            $etudiant->setDn($user["dn"]);
            if (strpos($user["dn"], "candidat"))
                $etudiant->setStatutLdap("Candidat");
            else if (strpos($user["dn"], "backup"))
                $etudiant->setStatutLdap("Ancien élève");
            else
                $etudiant->setStatutLdap("Elève");
            if (isset($user["sapid"])) {
                //$etudiant->setDateNaissance($this->getDate($user["sapid"][0]));
                $etudiant->setSapid($user["sapid"][0]);
            }
            if (isset($user["numerocartegrafic"]))
                $etudiant->setNumgrafic($user["numerocartegrafic"][0]);
            if (isset($user["supanncodeine"]))
                $etudiant->setIne($user["supanncodeine"][0]);
            if (isset($user["sn"]))
                $etudiant->setNom($user["sn"][0]);
            if (isset($user["givenname"]))
                $etudiant->setPrenom($user["givenname"][0]);
            if (isset($user["supannmailperso"]))
                $etudiant->setEmail($user["supannmailperso"][0]);
            if (isset($user["uid"]))
                $etudiant->setLogin($user["uid"][0]);
            if (isset($user["passwordgrafic"]))
                $etudiant->setPassword($user["passwordgrafic"][0]);
            if (isset($user["datenaissance"])) {
                $date = $user["datenaissance"][0];
                $date = substr($date, 6, 2) . '/' . substr($date, 4, 2) . '/' . substr($date, 0, 4);
                $etudiant->setDateNaissance($date);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            //mail("jc.barrez@cnam.fr","Erreur recherche étudiant ",$e->getMessage());
            //$logger->err($e->getMessage());
        }
        return $etudiant;
    }

    public function getDate($sapid) {
        $db2 = $this->config->db2->toArray();
        $db = new Zend_Db_Adapter_Pdo_Mysql($db2["params"]);
        $sapid = substr($sapid, 1);
        $r = $db->fetchAll("SELECT GBDAT FROM HRP1000 WHERE SHORT = ?", $sapid);
        $r = array_pop($r);
        $r = $r["GBDAT"];
        if ($r) $r = substr($r, 6, 2) . '/' . substr($r, 4, 2) . '/' . substr($r, 0, 4);
        return $r;
    }

    public function grep($idsap, $filename) {
        $path = realpath("../datas") . "/" . $filename;
        if (!file_exists($path))
            throw new Exception("Fichier $filename introuvable dans " . realpath("../datas") . " !");
        $id = str_repeat("0", 10 - strlen($idsap)) . $idsap;
        ob_start();
        $line = system("grep $id $path");
        ob_end_clean();
        if (strlen($line) > 0)
            return $id;
        else if (strlen($idsap) < 8) {
            $id = "01" . str_repeat("0", 8 - strlen($idsap)) . $idsap;
            ob_start();
            $line = system("grep $id $path");
            ob_end_clean();
            if (strlen($line) > 0)
                return $id;
        }
        return false;
    }

    public function grepAll($idsap, $filename) {
        $path = realpath("../datas") . "/" . $filename;
        if (!file_exists($path))
            throw new Exception("Fichier $filename introuvable dans " . realpath("../datas") . " !");
        $id = str_repeat("0", 10 - strlen($idsap)) . $idsap;
        ob_start();
        $line = system("grep $id $path");
        ob_end_clean();
        if (strlen($line) > 0)
            return $line;
        else if (strlen($idsap) < 8) {
            $id = "01" . str_repeat("0", 8 - strlen($idsap)) . $idsap;
            ob_start();
            $line = system("grep $id $path");
            ob_end_clean();
            if (strlen($line) > 0)
                return $line;
        }
        return false;
    }

    public function isInSiscol($sapid) {
        $options = array('login' => getenv('SISCOLIDENTIFIANT_SISCOL_WS_USER'), "password" => getenv('SISCOLIDENTIFIANT_SISCOL_WS_PASSWORD'), 'soap_version' => SOAP_1_1);
        $client = new Zend_Soap_Client(getenv('SISCOLIDENTIFIANT_SISCOL_WS_GETURI'), $options);
        $user = array('ACTIVITYGROUPS' => array(), 'ADDCOMREM' => array(), 'ADDFAX' => array(),
            'ADDPAG' => array(), 'ADDPRT' => array(), 'ADDRFC' => array(), 'ADDRML' => array(),
            'ADDSMTP' => array(), 'ADDSSF' => array(), 'ADDTEL' => array(), 'ADDTLX' => array(),
            'ADDTTX' => array(), 'ADDURI' => array(), 'ADDX400' => array(), 'EXTIDHEAD' => array(),
            'EXTIDPART' => array(), 'GROUPS' => array(), 'PARAMETER' => array(), 'PARAMETER1' => array(),
            'PROFILES' => array(), 'RETURN' => array(), 'SYSTEMS' => array(), 'UCLASSSYS' => array(),
            'USERNAME' => $sapid);
        try {
            //ini_set("memory_limit", "512M");
            $response = $client->Z_DTI_USER_GET_DETAIL($user);
//            if(isset($response->ADDRESS->PERS_NO) && $response->ADDRESS->PERS_NO!="") return true;
//            $user['USERNAME']=substr($sapid,1);
//            $response = $client->Z_DTI_USER_GET_DETAIL($user);
            //ini_set("memory_limit", "128M");
            return (isset($response->ADDRESS->PERS_NO) && $response->ADDRESS->PERS_NO != "");
        } catch (Exception $e) {
            //mail('jc.barrez@cnam.fr', 'EtudiantMapper 291', $e->getMessage());
            return -1;
        }
    }

    public function getMailInSiscol($sapid) {
        $options = array('login' => getenv('SISCOLIDENTIFIANT_SISCOL_WS_USER'), "password" => getenv('SISCOLIDENTIFIANT_SISCOL_WS_PASSWORD'), 'soap_version' => SOAP_1_1);
        $client = new Zend_Soap_Client(getenv('SISCOLIDENTIFIANT_SISCOL_WS_GETURI'), $options);
        $user = array('ACTIVITYGROUPS' => array(), 'ADDCOMREM' => array(), 'ADDFAX' => array(),
            'ADDPAG' => array(), 'ADDPRT' => array(), 'ADDRFC' => array(), 'ADDRML' => array(),
            'ADDSMTP' => array(), 'ADDSSF' => array(), 'ADDTEL' => array(), 'ADDTLX' => array(),
            'ADDTTX' => array(), 'ADDURI' => array(), 'ADDX400' => array(), 'EXTIDHEAD' => array(),
            'EXTIDPART' => array(), 'GROUPS' => array(), 'PARAMETER' => array(), 'PARAMETER1' => array(),
            'PROFILES' => array(), 'RETURN' => array(), 'SYSTEMS' => array(), 'UCLASSSYS' => array(),
            'USERNAME' => $sapid);
        try {
            $response = $client->Z_DTI_USER_GET_DETAIL($user);
            return $response->ADDRESS->E_MAIL;
        } catch (Exception $e) {
            echo str_replace('#', '<br>', $e);
            return -1;
        }
    }

    public function listSiscol($sapidMin, $sapidMax, $maxrow=1000000) {
        $options = array('login' => getenv('SISCOLIDENTIFIANT_SISCOL_WS_USER'), "password" => getenv('SISCOLIDENTIFIANT_SISCOL_WS_PASSWORD'), 'soap_version' => SOAP_1_1);
        $client = new Zend_Soap_Client(getenv('SISCOLIDENTIFIANT_SISCOL_WS_LISTURI'), $options);

        try {
            $response = $client->Z_DTI_USER_GETLIST(array('MAX_ROWS' => $maxrow,
                'RETURN' => array(),
                'ROWS' => array(),
                'SELECTION_EXP' => array(),
                'SELECTION_RANGE' => array(
                    array(
                        'PARAMETER' => 'USERNAME',
                        'FIELD' => '',
                        'SIGN' => 'I',
                        'OPTION' => 'BT',
                        'LOW' => $sapidMin,
                        'HIGH' => $sapidMax)
                ),
                'USERLIST' => array(),
                'WITH_USERNAME' => ''));
            if (isset($response->USERLIST->item))
                return $response->USERLIST->item;
            return null;
        } catch (Exception $e) {
            echo str_replace('#', '<br>', $e);
            return -1;
        }
    }

    public function setMailInSiscol($sapid, $email) {
        $options = array('login' => getenv('SISCOLIDENTIFIANT_SISCOL_WS_USER'), 'password' => getenv('SISCOLIDENTIFIANT_SISCOL_WS_PASSWORD'), 'soap_version' => SOAP_1_1);
        if (!$this->isInSiscol($sapid))
            return 0;
        try {
            $adress = new stdClass();
            $adressx = new stdClass();
            $client = new Zend_Soap_Client(getenv('SISCOLIDENTIFIANT_SISCOL_WS_SETURI'), $options);
            $adress->E_MAIL = $email;
            $adressx->E_MAIL = "X";
            $user = array('ADDCOMREM' => array(), 'ADDRESS' => $adress, 'ADDRESSX' => $adressx, 'ADDFAX' => array(),
                'ADDPAG' => array(), 'ADDPRT' => array(), 'ADDRFC' => array(), 'ADDRML' => array(),
                'ADDSMTP' => array('E_MAIL' => $adress), 'ADDSSF' => array(), 'ADDTEL' => array(), 'ADDTLX' => array(),
                'ADDTTX' => array(), 'ADDURI' => array(), 'ADDX400' => array(), 'EXTIDHEAD' => array(),
                'EXTIDPART' => array(), 'GROUPS' => array(), 'PARAMETER' => array(), 'PARAMETER1' => array(),
                'PROFILES' => array(), 'RETURN' => array(), 'SYSTEMS' => array(), 'UCLASSSYS' => array(),
                'USERNAME' => $sapid);
            $response = $client->Z_DTI_USER_CHANGE($user);
            $user["ADDRESSX"] = null;
            $response = $client->Z_DTI_USER_CHANGE($user);
        } catch (Exception $e) {
            echo str_replace('#', '<br>', $e);
            return -1;
        }
        if ($this->getMailInSiscol($sapid) != $email) {
            return 0;
        }
        return 1;
    }

}

