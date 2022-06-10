<?php
class Application_Form_Reinituesendmail extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('class','ldap');

        $this->addElement('hidden', 'ue', array(
                'required'   => true
            )
        );

        $this->addElement('submit', 'sendmail', array(
            'ignore'   => true,
            'label'    => 'Envoyer leur donc leurs paramètres de connexion',
        ));
    }
}

