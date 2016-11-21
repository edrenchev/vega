<?php
namespace City\Form;

use Zend\Form\Form;

class SearchForm extends Form {

    public function __construct() {

        parent::__construct('city-search-form');


        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-compact');

        $this->addElements();

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
            'name' => 'searchSubmit',
            'attributes' => [
                'value' => 'Search',
                'id' => 'searchButton',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'clearSubmit',
            'attributes' => [
                'value' => 'Clear',
                'id' => 'clearButton',
            ],
        ]);
    }
}
