<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('class','login');

        $this->addElement('text', 'login', array(
                'label'      => 'Login :',
                'required'   => true
            )
        );

        $this->addElement('password', 'password', array(
                'label'      => 'Mot de passe :',
                'required'   => true
            )
        );
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Connexion',
        ));
    }


}

