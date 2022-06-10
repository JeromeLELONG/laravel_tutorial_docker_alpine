<?php

class Application_Form_AdminCredential extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('class','admincredential');

        $this->addElement('text', 'login', array(
                'label'      => 'INE :'
            )
        );

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Rechercher',
        ));

        /* Form Elements & Other Definitions Here ... */
    }


}

