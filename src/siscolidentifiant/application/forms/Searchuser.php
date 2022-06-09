<?php
class Application_Form_Searchuser extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('class','ldap');

        $this->addElement('text', 'login', array(
                'label'      => 'Login :',
                'required'   => true
            )
        );
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Rechercher',
        ));
    }
}

