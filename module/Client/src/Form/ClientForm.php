<?php
namespace Client\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ClientForm extends Form implements ObjectManagerAwareInterface {

    protected $objectManager;

    public function __construct($entityManager) {

        parent::__construct('client-form');

        $this->setObjectManager($entityManager);

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
            'type' => 'text',
            'name' => 'email',
            'attributes' => [
                'id' => 'email'
            ],
            'options' => [
                'label' => 'Email',
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
                'label'                 => 'Град',
                'display_empty_item'    => true,
                'empty_item_label'      => '---',
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'address',
            'attributes' => [
                'id' => 'address'
            ],
            'options' => [
                'label' => 'Адрес',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'mbps',
            'attributes' => [
                'id' => 'mbps'
            ],
            'options' => [
                'label' => 'Mbps',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'monthly_price',
            'attributes' => [
                'id' => 'monthlyPrice'
            ],
            'options' => [
                'label' => 'Месечна такса',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'payday',
            'attributes' => [
                'id' => 'payday'
            ],
            'options' => [
                'label' => 'Ден от месеца за плащане',
            ],
        ]);

        // Add "status" field
        $this->add([
            'type'  => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    1 => 'Активен',
                    2 => 'Неактивен',
                ]
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'join_date',
            'attributes' => [
                'id' => 'joinDate'
            ],
            'options' => [
                'label' => 'Дата на включване',
            ],
        ]);

		$this->add([
			'type' => 'textarea',
			'name' => 'notes',
			'attributes' => [
				'id' => 'notes'
			],
			'options' => [
				'label' => 'Бележки',
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

    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }
}
