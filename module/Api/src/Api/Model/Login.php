<?php

namespace Api\Model;

 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;
 
 class Login implements InputFilterAwareInterface
 {
    protected $inputFilter;
     
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
         throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
        if(!$this->inputFilter) {
            $inputFilter = new InputFilter();    
        
        
            $inputFilter->add(array(
                'name' => 'username',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
               ),
            ));
        
        
            $inputFilter->add(array(
                'name' => 'password',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));
        
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
    
    
 }

