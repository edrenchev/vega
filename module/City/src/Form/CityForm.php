<?php
namespace City\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class CityForm extends Form {

    public function __construct() {

        parent::__construct('city-form');

        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();

    }

    protected function addElements() {

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'attributes' => [
                'id' => 'name'
            ],
            'options' => [
                'label' => 'Име',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create',
                'id' => 'submitbutton',
            ],
        ]);

    }

    private function addInputFilter() {

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 3,
                        'max' => 255
                    ],
                ],
            ],
        ]);

    }
}
