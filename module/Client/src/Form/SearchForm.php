<?php
namespace Client\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SearchForm extends Form implements ObjectManagerAwareInterface {

    protected $objectManager;

    public function __construct($entityManager) {

        parent::__construct('client-search-form');

        $this->setObjectManager($entityManager);

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
            'type' => 'text',
            'name' => 'phone',
            'attributes' => [
                'id' => 'phone'
            ],
            'options' => [
                'label' => 'Телефон',
            ],
        ]);

        $this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'city_id',
            'options' => [
                'object_manager'        => $this->getObjectManager(),
                'target_class'          => 'City\Entity\City',
                'property'              => 'name',
				'find_method' => [
					'name'   => 'findBy',
					'params' => [
						'criteria' => [],
						'orderBy'  => ['name' => 'ASC',],
					],
				],
                'label'                 => 'Град',
                'display_empty_item'    => true,
                'empty_item_label'      => '---',
            ],
        ]);

        // Add "status" field
        $this->add([
            'type'  => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Статус',
                'value_options' => [
                    0 => '---',
                    1 => 'Активен',
                    2 => 'Неактивен',
                ]
            ],
        ]);

        // Add "join_date_from" field
        $this->add([
			'type' => 'text',
			'name' => 'join_date_from',
			'attributes' => [
				'id' => 'joinDateFrom'
			],
			'options' => [
				'label' => 'Дата на вкл. от',
			],
        ]);

        // Add "join_date_to" field
        $this->add([
			'type' => 'text',
			'name' => 'join_date_to',
			'attributes' => [
				'id' => 'joinDateTo'
			],
			'options' => [
				'label' => 'Дата на вкл. до',
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


    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }
}
