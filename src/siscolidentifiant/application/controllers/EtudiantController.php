<?php

class EtudiantController extends Zend_Controller_Action {

    private $auth = null;

    public function init() {
        Application_Model_AccessControl::isAllowed($this);
        $this->auth = Zend_Auth::getInstance();
        if (Application_Model_AccessControl::inGroup("admin"))
            $this->view->admin = true;
        if ($this->auth->hasIdentity())
            $this->view->islogged = true;
        else
            $this->view->loginform = new Application_Form_Login();
    }

    public function indexAction() {
        $this->_helper->redirector('search');
    }
    
    public function diffMem (){
        $current = memory_get_usage()/1024;
        $dbt = debug_backtrace();
        echo "<b>".$dbt[0]["line"]."</b>:".number_format($current).' ko ('.number_format($current - $this->memory, 0, ',', ' ')." ko)<br>";
        $this->memory = $current;
    }
    public function showMem (){
        $current = memory_get_usage()/1024;
        $dbt = debug_backtrace();
        echo "<b>".$dbt[0]["line"]."</b>:".number_format($current, 0, ',', ' ')." ko<br>";
        $this->memory = $current;
    }
    public function searchAction() {
        
        $this->view->siscolmail = getenv('SISCOLIDENTIFIANT_SISCOL_EMAIL');
        $request = $this->getRequest();
        $form = new Application_Form_EtudiantSearch();
        $entries = array();
        $this->view->entries = array();
        $this->view->url = $this->view->baseUrl("img/icons");
        $this->view->alert = "";
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                try {
                    $mapper = new Application_Model_EtudiantMapper();
                    $entries = $mapper->findBy($form->getValues());
                    foreach ($entries as $etudiant) {
                        if ($mapper->isInSiscol($etudiant->getSapid()))
                            $etudiant->toSiscol();
                        $this->view->entries[strtoupper($etudiant->getNom() . $etudiant->getPrenom() . $etudiant->getSapid())] = $etudiant;
                        unset($etudiant);
                    }
                    unset($etudiant,$entries);
                    ksort($this->view->entries);
                    if (count($this->view->entries) == 0)
                        $this->view->message = "Aucun élève trouvé !";
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
        unset($request);
        $this->view->form = $form;        
    }

    public function viewAction() {
        $this->getView();
    }

    public function ajaxviewAction() {
        $this->_helper->layout->disableLayout();
        $this->getView();
    }

    public function getView() {
        if ($this->getRequest()->isGet() && $this->getRequest()->getParam("etudiant")) {
            $mapper = new Application_Model_EtudiantMapper();

            $this->view->etudiant = $mapper->find($this->getRequest()->getParam("etudiant"));
            $sapid = $this->view->etudiant->getSapid();
            $oldidsap = $this->view->etudiant->getSapid();
            $this->view->etudiant->setSapid($sapid);
            if (!Application_Model_AccessControl::inGroup("admin")
                    && !Application_Model_AccessControl::inGroup("superuser"))
                $this->view->etudiant->setPassword('********');
            $this->view->insiscolws = $mapper->isInSiscol($sapid);
            if (!$this->view->insiscolws) {
                $sapid = substr($sapid, 1);
                $this->view->insiscolws = $mapper->isInSiscol($sapid);
            }
            if ($this->view->insiscolws) {
                $siscolEmail = $mapper->getMailInSiscol($sapid);
                if (!$siscolEmail) {
                    if ($this->view->etudiant->getEmail() && $this->view->etudiant->getEmail() != getenv('SISCOLIDENTIFIANT_ANNUAIRE_DEFAULT_EMAIL'))
                        if (!$mapper->setMailInSiscol($sapid, $this->view->etudiant->getEmail()))
                            echo "Impossible d'insérer l'adresse mail dans siscol !";
                }
                $siscolEmail = strtolower($mapper->getMailInSiscol($sapid));
                if (strtolower($this->view->etudiant->getEmail()) != $siscolEmail) {
                    if (!$this->view->etudiant->getEmail() || $this->view->etudiant->getEmail() == getenv('SISCOLIDENTIFIANT_ANNUAIRE_DEFAULT_EMAIL'))
                        $mapper->changeEmail($this->getRequest()->getParam("etudiant"), $siscolEmail);
                    else
                        $this->view->mailsdifferent = "Ldap : " . $this->view->etudiant->getEmail() . "<br/>Siscol : $siscolEmail";
                }
                else
                    $this->view->mailsdifferent = false;
            }
            //$line = $mapper->grepAll($sapid, "Eleves_dans_SISCOL.csv");
//            ob_start();
//            $line = @system("grep ".$sapid." Eleves_dans_SISCOL.csv");
//            ob_end_clean();
            //if(strlen($line)>0) {
            //echo $this->view->etudiant->getDn();
            $this->view->name = $sapid;
            if ($this->view->insiscolws) {
                if ($sapid !== $oldidsap && substr($sapid, 0, 2) == "00")
                    $mapper->updateLdap($this->view->etudiant->getDn(), $sapid);
//                list($sapid,$name)=explode(";",$line);
                $this->view->insiscol = true;
                //$this->view->name="$name ($sapid)";
            }
            else {
                $this->view->insiscol = false;
                //$this->view->name=$sapid;
            }
            if (Application_Model_AccessControl::inGroup("admin")) {
//                $line = $mapper->grepAll($sapid, "Eleves_dans_SISCOL.csv");
//                ob_start();
//                $line = @system("grep ".$sapid." Candidats_dans_SISCOL.csv");
//                ob_end_clean();
                if ($this->view->insiscolws) {
                    if ($sapid !== $oldidsap) {
                        echo "<font color='red'>'$sapid' !== '$oldidsap'</font>";
                        $mapper->updateLdap($this->view->etudiant->getDn(), $sapid);
                    }
//                    list($sapid,$name)=explode(";",$line);
                    $this->view->candidat = true;
//                    $this->view->name="$name ($sapid)";
                } else {
                    $this->view->candidat = false;
                    //                  $this->view->name=$sapid;
                }
            }
        }
        else
            throw new Exception("Paramètre requis manquant !", 0);
    }

    public function checksapidAction() {
        if (Application_Model_AccessControl::inGroup("admin")) {
            $filename = realpath(dirname(__FILE__)) . '/../../datas/listesapid.txt';
            $etudiantMapper = new Application_Model_EtudiantMapper();
            if (file_exists($filename)) {
                $file = fopen($filename, 'r');
                while ($sapid = fgets($file)) {
                    $sapid = trim($sapid);
                    echo "<br>'$sapid'";
                    $etudiant = $etudiantMapper->findBySapid($sapid);
                    if (!$etudiant) {
                        echo " nouvelle recherche avec " . substr($sapid, 1);
                        $etudiant = $etudiantMapper->findBySapid(substr($sapid, 1));
                    }
                    if (!$etudiant)
                        echo " non trouvé dans ldap ";
                    else {
                        echo " recherche dans siscol ";
                        $insiscolws = $etudiantMapper->isInSiscol($sapid);
                        if (!$insiscolws) {
                            $siscolsapid = substr($sapid, 1);
                            echo " nouvelle recherche avec $siscolsapid ";
                            $insiscolws = $etudiantMapper->isInSiscol($siscolsapid);
                        } else {
                            echo " trouvé dans siscol ";
                            $siscolsapid = $sapid;
                        }
                        if ($insiscolws) {
                            if ($sapid !== $siscolsapid) {
                                //echo $etudiant->getDn();
                                $etudiantMapper->updateLdap($etudiant->getDn(), $siscolsapid);
                                echo " <font color='red'> mis à jour </font>";
                            }
                            else
                                echo " OK ";
                        } else {
                            echo " non trouvé dans siscol ";
                        }
                    }
                }
            }
        }
    }

    public function ajouteremailAction() {
        $this->_helper->layout->disableLayout();
        if ($this->_request->getParam("email")) {
            $etudiantMapper = new Application_Model_EtudiantMapper();
            $etudiantMapper->changeEmail($this->_request->getParam("dn"), $this->_request->getParam("email"));
            $etudiant = $etudiantMapper->find($this->_request->getParam("dn"));
            $this->view->email = $this->_request->getParam("email");
            $this->view->dn = $this->_request->getParam("dn");
            $etudiantMapper->setMailInSiscol($etudiant->getSapid(), $this->_request->getParam("email"));
        }
    }

    public function reinitAction() {
        $mapper = new Application_Model_EtudiantMapper();
        $user = $mapper->reinitPassword($this->getRequest()->getParam("dn"));
        $this->view->dn = $this->getRequest()->getParam("dn");
        if ($user) {
            $this->view->mail = $user["supannmailperso"][0];
            if ($user["supannmailperso"][0] != "siscol.mel@cnam.fr") {
                try {
                    if ($this->getRequest()->getParam("sendMail")) {
                        $corps = new Zend_View();
                        $corps->setScriptPath("../application/views/scripts/etudiant");
                        if (!array_key_exists("supannmailperso", $user))
                            throw new Exception("Pas de mail", 125);
                        $corps->login = $user["uid"][0];
                        $corps->password = $user["passwordgrafic"][0];
                        $corps->prenom_nom = ucwords($user["cn"][0]);
                        $corps->route = "https://comptes.cnam.fr/";
                        $corps->siscolmail = getenv('SISCOLIDENTIFIANT_SISCOL_EMAIL');
                        $mail = new Zend_Mail('UTF-8');
                        $mail->setBodyText($corps->render('emailInitPasswordTxt.php'));
                        $mail->setBodyHtml($corps->render('emailInitPasswordHtml.php'));
                        $mail->setFrom("info-formation@cnam.fr", 'Cnam DSI');
                        //            $mail->addTo("jc.barrez@cnam.fr", $user["cn"][0]);
                        $mail->addTo($user["supannmailperso"], $user["cn"][0]);
                        $mail->setSubject('Réinitialisation de votre mot de passe Cnam');
                        $transport = new Zend_Mail_Transport_Smtp ( getenv('SISCOLIDENTIFIANT_SMTP_HOST'), [
                            "port"      =>  25,
                        ]);
                        $mail->send($transport);
                        $this->view->mailsended = $user["supannmailperso"][0];
                    }
                    else
                        $this->view->mailsended = false;
                } catch (Exception $e) {
                    $this->view->mailsended = false;
                }
            }
            $this->view->reinit = true;
        }
        else
            $this->view->reinit = false;
    }

    public function ajaxreinitAction() {
        
        $this->_helper->layout->disableLayout();
        $mapper = new Application_Model_EtudiantMapper();
        $user = $mapper->reinitPassword($this->getRequest()->getParam("dn"));
        
        if ($user) {
            $this->view->mail = $user["supannmailperso"][0];
            if ($user["supannmailperso"][0] != "siscol.mel@cnam.fr") {
                try {
                    if ($this->getRequest()->getParam("sendmail") == 'true') {

                        if(strpos($user['dn'],'candidat'))
                        {
                            $corps = new Zend_View();
                            $corps->setScriptPath("../application/views/scripts/etudiant");
                            if (!array_key_exists("supannmailperso", $user))
                                throw new Exception("Pas de mail", 125);
                            $corps->login = $user["uid"][0];
                            $corps->prenom_nom = ucwords($user["cn"][0]);
                            $corps->route = "https://comptes.cnam.fr/";
                            $corps->password = $user["passwordgrafic"][0];
                            $corps->siscolmail = getenv('SISCOLIDENTIFIANT_SISCOL_EMAIL');
                            $mail = new Zend_Mail('UTF-8');
                            $mail->setBodyText($corps->render('emailInitPasswordTxtCandidat.php'));
                            $mail->setBodyHtml($corps->render('emailInitPasswordHtmlCandidat.php'));
                            $mail->setFrom("info-formation@cnam.fr", 'Cnam DSI');
                            $mail->addTo($user["supannmailperso"], $user["cn"][0]);
                            $mail->setSubject('Réinitialisation de votre mot de passe Cnam');
                            $transport = new Zend_Mail_Transport_Smtp ( getenv('SISCOLIDENTIFIANT_SMTP_HOST'), [
                                "port"      =>  25,
                            ]);
                            $mail->send($transport);
                            $this->view->mailsended = $user["supannmailperso"][0];
                        }
                        else
                        {
                            $corps = new Zend_View();
                            $corps->setScriptPath("../application/views/scripts/etudiant");
                            if (!array_key_exists("supannmailperso", $user))
                                throw new Exception("Pas de mail", 125);
                            $corps->login = $user["uid"][0];
                            $corps->prenom_nom = ucwords($user["cn"][0]);
                            $corps->route = "https://comptes.cnam.fr/";
                            $corps->password = $user["passwordgrafic"][0];
                            $corps->siscolmail = getenv('SISCOLIDENTIFIANT_SISCOL_EMAIL');
                            $mail = new Zend_Mail('UTF-8');
                            $mail->setBodyText($corps->render('emailInitPasswordTxtEleve.php'));
                            $mail->setBodyHtml($corps->render('emailInitPasswordHtmlEleve.php'));
                            $mail->setFrom("info-formation@cnam.fr", 'Cnam DSI');
                            $mail->addTo($user["supannmailperso"], $user["cn"][0]);
                            $mail->setSubject('Réinitialisation de votre mot de passe Cnam');
                            $transport = new Zend_Mail_Transport_Smtp ( getenv('SISCOLIDENTIFIANT_SMTP_HOST'), [
                                "port"      =>  25,
                            ]);
                            $mail->send($transport);
                            $this->view->mailsended = $user["supannmailperso"][0];
                        }
        
                        
                    }
                    else
                        $this->view->mailsended = false;
                } catch (Exception $e) {
                    $this->view->mailsended = false;
                }
            }
            $this->view->reinit = true;
        }
        else
            $this->view->reinit = false;
    }

    public function ajaxtestpasswordAction() {
        $this->_helper->layout->disableLayout();
        $mapper = new Application_Model_EtudiantMapper();
        if ($this->getRequest()->isGet() && $this->getRequest()->getParam("dn")
                && $this->getRequest()->getParam("password")) {
            $this->view->etudiant = $mapper->find($this->getRequest()->getParam("dn"));
            if ($mapper->testPassword($this->getRequest()->getParam("dn"), $this->getRequest()->getParam("password")))
                $this->view->initial = true;
            else {
                $this->view->initial = false;
            }
        }
    }

    public function insiscolAction() {
        $line = @system("grep 0000364532 listesiscolcomp.txt");
        if (strlen($line) > 0) {
            list($sapid, $name) = explode(":", $line);
            echo "NAME $name";
        }
    }

    public function ajaxalertAction() {
        $this->_helper->layout->disableLayout();
        //$this->getRequest()->getParam("etudiant")
        try {
            $mail = new Zend_Mail('UTF-8');
            $mail->setBodyText($this->getRequest()->getParam("etudiant"));
            $mail->setFrom("info-formation@cnam.fr", 'Cnam DSI');
            //            $mail->addTo("jc.barrez@cnam.fr", $user["cn"][0]);

            $mail->addTo(getenv('SISCOLIDENTIFIANT_SISCOL_EMAIL'), getenv('SISCOLIDENTIFIANT_SISCOL_NAME'));
            //$mail->addTo("jc.barrez@cnam.fr",$config->siscol->name);
            $mail->setSubject("Etudiant à recréer");
            $transport = new Zend_Mail_Transport_Smtp ( getenv('SISCOLIDENTIFIANT_SMTP_HOST'), [
                "port"      =>  25,
            ]);
            $mail->send($transport);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    public function listldapetudiantsAction() {
        if ($this->_request->getParam('sn')) {
            $this->_helper->layout->disableLayout();
            set_time_limit(3000);
            $mapper = new Application_Model_EtudiantMapper();
            $entries = $mapper->findEtudiantsBy(array("sn" => $this->_request->getParam('sn'), "supannmailperso" => "siscol.mel@cnam.fr"));
            echo "<h2>" . strtolower($this->_request->getParam('sn')) . "</h2>";
            foreach ($entries as $entry) {
                $eleve = $mapper->find($entry->dn);
                $emailSiscol = $mapper->getMailInSiscol($eleve->sapid);
                echo $eleve->getSapid() . ' ' . $emailSiscol . "<br>";
            }
            set_time_limit(30);
        }
    }

    public function ldapetudiantAction() {
        $this->_helper->layout->disableLayout();
        $mapper = new Application_Model_EtudiantMapper();
        $emailSiscol = $mapper->getMailInSiscol($this->_request->getParam("sapid"));
        echo $emailSiscol;
    }

    /*
     * Vérifie que les auditeurs dans siscol sont bien dans LDAP,
     * qu'il ont un email et que ce dernier est indentique à celui de LDAP
     */

    public function siscoletudiantAction() {
        $this->_helper->layout->disableLayout();
        $mapper = new Application_Model_EtudiantMapper();
        $email = $mapper->getMailInSiscol($this->_request->getParam('sapid'));
        echo $email;
    }

    /*
     * Liste les élève dans Siscol
     */

    public function listsiscoletudiantsAction() {

        if ($this->_request->getParam('sapidMin')) {
            $this->_helper->layout->disableLayout();
            $mapper = new Application_Model_EtudiantMapper();
            $this->view->etudiants = $mapper->listSiscol($this->_request->getParam("sapidMin"), $this->_request->getParam("sapidMax"));
        }
    }

    public function testwssiscolAction() {
        $options = array('login' => 'rfc_user', "password" => "test1234", 'soap_version' => SOAP_1_1);
        $user = array('ACTIVITYGROUPS' => array(), 'ADDCOMREM' => array(), 'ADDFAX' => array(),
            'ADDPAG' => array(), 'ADDPRT' => array(), 'ADDRFC' => array(), 'ADDRML' => array(),
            'ADDSMTP' => array(), 'ADDSSF' => array(), 'ADDTEL' => array(), 'ADDTLX' => array(),
            'ADDTTX' => array(), 'ADDURI' => array(), 'ADDX400' => array(), 'EXTIDHEAD' => array(),
            'EXTIDPART' => array(), 'GROUPS' => array(), 'PARAMETER' => array(), 'PARAMETER1' => array(),
            'PROFILES' => array(), 'RETURN' => array(), 'SYSTEMS' => array(), 'UCLASSSYS' => array(),
            'USERNAME' => "0000227287");
        try {
            echo "<h1> Test GET_DETAIL en dev </h1>";
            $client = new Zend_Soap_Client("http://siriusdev.cnam.fr:8000/sap/bc/soap/wsdl11?sap-client=100&services=ZBAPI_USER_GET_DETAIL", $options);
            $etudiant = $client->ZBAPI_USER_GET_DETAIL($user);
            echo($etudiant->ADDRESS->E_MAIL);
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
        }
        try {
            echo "<h1> Test LIST en dev </h1>";
            $client = new Zend_Soap_Client("http://siriusdev.cnam.fr:8000/sap/bc/soap/wsdl11?sap-client=100&services=Z_DTI_USER_GETLIST", $options);
            $liste = $client->Z_DTI_USER_GETLIST(array('MAX_ROWS' => 5, 'RETURN' => array(),
                'SELECTION_EXP' => array(),
                'SELECTION_RANGE' => array(),
                'USERLIST' => array()));
            echo $liste->ROWS;
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
        }
        try {
            echo "<h1> Test GET_DETAIL en test </h1>";
            $client = new Zend_Soap_Client("http://siriustest.cnam.fr:8000/sap/bc/soap/wsdl11?sap-client=210&services=Z_DTI_USER_GET_DETAIL", $options);
            $etudiant = $client->Z_DTI_USER_GET_DETAIL($user);
            echo($etudiant->ADDRESS->E_MAIL);
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
        }
        try {
            echo "<h1> Test LIST en test </h1>";
            $client = new Zend_Soap_Client("http://siriustest.cnam.fr:8000/sap/bc/soap/wsdl11?sap-client=210&services=Z_DTI_USER_GETLIST", $options);
            $liste = $client->Z_DTI_USER_GETLIST(array('MAX_ROWS' => 5, 'RETURN' => array(),
                'SELECTION_EXP' => array(),
                'SELECTION_RANGE' => array(),
                'USERLIST' => array()));
            echo $liste->ROWS;
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
        }
        try {
            echo "<h1> Test GET_DETAIL en integration </h1>";
            $client = new Zend_Soap_Client("http://siriusprod.cnam.fr:8000/sap/bc/soap/wsdl11?sap-client=400&services=Z_DTI_USER_GET_DETAIL", $options);
            $etudiant = $client->Z_DTI_USER_GET_DETAIL($user);
            echo($etudiant->ADDRESS->E_MAIL);
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
        }
        try {
            echo "<h1> Test LIST en int </h1>";
            $client = new Zend_Soap_Client("http://siriusprod.cnam.fr:8000/sap/bc/soap/wsdl11?sap-client=400&services=Z_DTI_USER_GETLIST", $options);
            $liste = $client->Z_DTI_USER_GETLIST(array('MAX_ROWS' => 5,
                'RETURN' => array(),
                'ROWS' => array(),
                'SELECTION_EXP' => array(),
                'SELECTION_RANGE' => array(
                    array(
                        'PARAMETER' => 'USERNAME',
                        'FIELD' => '',
                        'SIGN' => 'I',
                        'OPTION' => 'BT',
                        'LOW' => '0000000021',
                        'HIGH' => '0000000121')
                ),
                'USERLIST' => array(),
                'WITH_USERNAME' => ''));
            foreach ($liste->USERLIST->item as $etudiant) {
                echo $etudiant->USERNAME . " ";
                if (isset($etudiant->ADDRESS->E_MAIL)) {
                    echo($etudiant->ADDRESS->E_MAIL);
                } else {
//                    $client2 = new Zend_Soap_Client("http://siriusprod.cnam.fr:8000/sap/bc/soap/wsdl11?sap-client=400&services=Z_DTI_USER_GET_DETAIL",$options);
//                    $user['USERNAME']=$etudiant->USERNAME;
//                    $etudiant2 = $client2->Z_DTI_USER_GET_DETAIL($user);
//                    echo($etudiant2->ADDRESS->E_MAIL);
                }
                echo "<br>";
            }
        } catch (Exception $e) {
            echo $e->getMessage() . "<br>";
        }
    }

    public function reinitueAction() {
        $this->view->form = new Application_Form_Reinitue();
        if ($this->_request->isPost()) {
            $dao = new Application_Model_EtudiantMapper();
            $etudiants = $dao->findBy(array("UV" => $this->_request->getParam("ue")));
            $this->view->etudiants = $etudiants;
            $bouton = new Application_Form_Reinituesendmail();
            $bouton->populate(array("ue" => $this->_request->getParam("ue")));
            $this->view->bouton = $bouton;
            if ($this->_request->getParam("sendmail")) {
                foreach ($etudiants as $etudiant) {
                    if ($etudiant->getEmail() && $etudiant->getEmail() != "siscol.mel@cnam.fr" && $etudiant->getStatutLdap() != "backup") {
                        try {
                            $corps = new Zend_View();
                            $corps->setScriptPath("../application/views/scripts/etudiant");
                            $corps->login = $etudiant->getLogin();
                            $corps->password = $etudiant->getPassword();
                            $corps->prenom_nom = ucwords($etudiant->getPrenom() . ' ' . $etudiant->getNom());
                            $mail = new Zend_Mail('UTF-8');
                            $mail->setBodyText($corps->render('emailDefaultPasswordTxt.php'));
                            $mail->setBodyHtml($corps->render('emailDefaultPasswordHtml.php'));
                            $mail->setFrom("info-formation@cnam.fr", 'Cnam DSI');
//                            $mail->addTo("christophe.dumoulin@cnam.fr", $corps->prenom_nom);
//                            $mail->addTo("olivier.villin@cnam.fr", $corps->prenom_nom);
                            $mail->addTo($etudiant->getEmail(), $corps->prenom_nom);
                            $mail->setSubject('Vos paramètres initiaux de connexion au Cnam');
                            $transport = new Zend_Mail_Transport_Smtp ( getenv('SISCOLIDENTIFIANT_SMTP_HOST'), [
                                "port"      =>  25,
                            ]);
                            $mail->send($transport);
                        } catch (Exception $e) {
                            echo $e->getMessage() . '<br/>';
                        }
                    }
                }
                echo "<script>alert('Emails envoyé !')</script>";
            }
        }
    }

}

