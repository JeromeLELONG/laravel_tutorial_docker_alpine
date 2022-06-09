<?php
class Application_Form_EtudiantSearch extends Zend_Form {

    public function init() {
        
        $this->setMethod('post');
        $this->setAttrib('class','search');

        $this->addElement('text', 'sapid', array(
                'label'      => 'SapId :'
            )
        );

        $this->addElement('text', 'supanncodeine', array(
                'label'      => 'INE :'
            )
        );

        $this->addElement('text', 'numerocartegrafic', array(
                'label'      => 'N° Grafic ou Intec :'
            )
        );

        $this->addElement('text', 'uid', array(
                'label'      => 'Login LDAP :'
            )
        );

        $this->addElement('text', 'sn', array(
                'label'      => 'Nom :'
            )
        );

        $this->addElement('text', 'givenname', array(
                'label'      => 'Prénom :'
            )
        );

        $this->addElement('text', 'supannmailperso', array(
                'label'      => 'Email personnel :'
            )
        );
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Rechercher',
        ));
    }
}

