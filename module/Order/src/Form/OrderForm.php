<?php
namespace Order\Form;

use Client\Entity\Client;
use Order\Entity\Order;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class OrderForm extends Form implements ObjectManagerAwareInterface {
	protected $objectManager;
	protected $typeOrderForm;

	public function __construct($entityManager, $typeOrderForm = 'single') {
		parent::__construct('order-form');
		$this->setObjectManager($entityManager);
		$this->typeOrderForm = $typeOrderForm;
		$this->setAttribute('method', 'post');
		$this->addElements();
		$this->addInputFilter();
	}

	protected function addElements() {
		$this->add([
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'name' => 'client_id',
			'options' => [
				'object_manager' => $this->getObjectManager(),
				'target_class' => 'Client\Entity\Client',
				'property' => 'name',
				'find_method' => [
					'name'   => 'findBy',
					'params' => [
						'criteria' => ['status' => Client::STATUS_ACTIVE],
						'orderBy'  => ['name' => 'ASC',],
					],
				],
				'label' => 'Клиент',
				'display_empty_item' => true,
				'empty_item_label' => '---',
			],
		]);

		$this->add([
			'type' => 'hidden',
			'name' => 'city_id',
			'attributes' => [
				'id' => 'cityId'
			],
			'options' => [
				'label' => 'Град',
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
				'id' => 'price'],
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
					Order::PAYMENT_METHOD_CASH => 'Кеш',
					Order::PAYMENT_METHOD_BANK => 'Банков',
				]
			],
		]);

		if ($this->typeOrderForm == 'single') {
			$this->add([
				'type' => 'text',
				'name' => 'paid_at',
				'attributes' => [
					'id' => 'paidAt'],
				'options' => [
					'label' => 'Дата на плащане',
				],
			]);
		} elseif ($this->typeOrderForm == 'multiple') {
			$this->add([
				'type' => 'text',
				'name' => 'paid_at_from',
				'attributes' => [
					'id' => 'paidAtFrom'],
				'options' => [
					'label' => 'Дата на плащане от',
				],
			]);
			$this->add([
				'type' => 'text',
				'name' => 'paid_at_to',
				'attributes' => [
					'id' => 'paidAtTo'],
				'options' => [
					'label' => 'Дата на плащане до',
				],
			]);
		}

		$this->add([
			'type' => Csrf::class,
			'name' => 'order_form_csrf',
			'options' => [
				'csrf_options' => [
					'timeout' => 600
				]
			],
		]);

		$this->add([
			'type' => 'textarea',
			'name' => 'note',
			'attributes' => [
				'id' => 'note'
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
		if ($this->typeOrderForm == 'multiple') {
			$inputFilter->add([
				'name' => 'paid_at_from',
				'required' => true,
			]);
			$inputFilter->add([
				'name' => 'paid_at_to',
				'required' => true,
			]);
		}
		$this->setInputFilter($inputFilter);
	}

	public function setObjectManager(ObjectManager $objectManager) {
		$this->objectManager = $objectManager;
	}

	public function getObjectManager() {
		return $this->objectManager;
	}
}