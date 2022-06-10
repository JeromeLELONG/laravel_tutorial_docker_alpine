<?php

class Application_Form_Adduseringroup extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('class','usergroup');

        $this->addElement ('select','group',array(
            'label'=> 'Groupe : ',
            'id'   => 'ingroup'
        ));
        
        $this->addElement('button', 'button', array(
            'ignore'   => true,
            'id'       => 'addingroup',
            'label'    => 'Ajouter'
        ));
        /* Form Elements & Other Definitions Here ... */
    }


}

