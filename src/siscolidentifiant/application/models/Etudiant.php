<?php
class Application_Model_Etudiant {
    private $dn;
    private $statutLdap;
    private $sapid;
    private $numgrafic;
    private $ine;
    private $nom;
    private $prenom;
    private $email;
    private $login;
    private $password;
    private $insiscol=false;
    private $candidat = false;
    private $arecreer=false;
    private $dateNaissance;
    private $datefin;


    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid etudiant property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid etudiant property');
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function setDn($dn) {
        $this->dn = $dn;
    }

    public function getDn() {
        return $this->dn;
    }
    
    public function setNom($text)
    {
        $this->nom = (string) ucwords(strtolower($text));
        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setPrenom($text)
    {
        $this->prenom = (string) ucwords(strtolower($text));
        return $this;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setEmail($email)
    {
        $this->email = (string) strtolower($email);
        return $this;
    }

    public function getEmail()
    {
        return strtolower($this->email);
    }

    public function setSapid($sapid)
    {
        $this->sapid = $sapid;
        return $this;
    }

    public function getSapid()
    {
        return $this->sapid;
    }

    public function setIne($ine)
    {
        $this->ine = $ine;
        return $this;
    }

    public function getIne()
    {
        return $this->ine;
    }

    public function setNumgrafic($numgrafic)
    {
        $this->numgrafic = $numgrafic;
        return $this;
    }

    public function getNumgrafic()
    {
        return $this->numgrafic;
    }

    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

        public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function toSiscol(){
        $this->insiscol=true;
    }

    public function inSiscol(){
        return $this->insiscol;
    }

    public function toRecreer(){
        $this->arecreer=true;
    }

    public function aRecreer(){
        return $this->arecreer;
    }

    public function candidate(){
        $this->candidat=true;
    }

    public function isCandidat(){
        return $this->candidat;
    }

    public function setDateNaissance($dateNaissance){
        $this->dateNaissance = $dateNaissance;
    }

    public function getDateNaissance(){
        return $this->dateNaissance;
    }
    
    public function getStatutLdap() {
        return $this->statutLdap;
    }

    public function setStatutLdap($statutLdap) {
        $this->statutLdap = $statutLdap;
    }

    public function getDatefin() {
        return $this->datefin;
    }

    public function setDatefin($datefin) {
        $this->datefin = $datefin;
    }


}
?>
