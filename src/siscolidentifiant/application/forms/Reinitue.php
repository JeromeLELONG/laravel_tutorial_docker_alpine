<?php
class Application_Form_Reinitue extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('class','ldap');

        $this->addElement('text', 'ue', array(
                'label'      => 'Code Ue :',
                'required'   => true
            )
        );
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Rechercher',
        ));
    }
}

