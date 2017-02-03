<?php

 namespace Api\Form;
 
 use Zend\Form\Form;
 
 class LoginForm extends Form
 {
      public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('User');

         $this->add(array(
             'name' => 'username',
             'type' => 'Text',
           
         ));
         $this->add(array(
             'name' => 'password',
             'type' => 'password',
            
         ));
         
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
     }
     
 }
