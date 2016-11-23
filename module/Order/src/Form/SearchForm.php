<?php
namespace Order\Form;

use Order\Entity\Order;
use Zend\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SearchForm extends Form implements ObjectManagerAwareInterface {
    protected $objectManager;

    public function __construct($entityManager) {
        parent::__construct('order-search-form');
        $this->setObjectManager($entityManager);
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-compact');
        $this->addElements();
    }

    protected function addElements() {
        $this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'client_id',
            'options' => [
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Client\Entity\Client',
                'property' => 'fullName',
                'is_method' => true,
				'find_method' => [
					'name'   => 'findBy',
					'params' => [
						'criteria' => [],
						'orderBy'  => ['firstName' => 'ASC', 'lastName' => 'ASC'],
					],
				],
                'label' => 'Клиент',
                'display_empty_item' => true,
                'empty_item_label' => '---',
            ],
        ]);

        $this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'city_id',
            'options' => [
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'City\Entity\City',
                'property' => 'name',
                'label' => 'Град',
                'display_empty_item' => true,
                'empty_item_label' => '---',
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
            'name' => 'price',
            'attributes' => [
                'id' => 'price'
            ],
            'options' => [
                'label' => 'Цена',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'is_pay',
            'options' => [
                'label' => 'Платил',
                'value_options' => [
                    '' => '---',
                    1 => 'Да',
                    0 => 'Не',
                ]
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'payment_method',
            'options' => [
                'label' => 'Метод на плащане',
                'value_options' => [
                    '' => '---',
                    Order::PAYMENT_METHOD_CASH => 'Кеш',
                    Order::PAYMENT_METHOD_BANK => 'Бенков',
                ]
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'paid_at_to',
            'attributes' => [
                'id' => 'paidAtTo'
            ],
            'options' => [
                'label' => 'Дата на плащане от',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'paid_at_from',
            'attributes' => [
                'id' => 'paidAtFrom'
            ],
            'options' => [
                'label' => 'Дата на плащане до',
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

    public function setObjectManager(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

    public function getObjectManager() {
        return $this->objectManager;
    }
}