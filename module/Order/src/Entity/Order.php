<?php
namespace Order\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single post in a blog.
 * @ORM\Entity(repositoryClass="\Order\Repository\OrderRepository")
 * @ORM\Table(name="orders") */
class Order {
    const IS_PAY = 1;
    const IS_NOT_PAY = 0;
    const PAYMENT_METHOD_CASH = 1;
    const PAYMENT_METHOD_BANK = 2;
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Client\Entity\Client", fetch="EAGER")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $clientId;

    /**
     * @ORM\Column(name="city_id")
     */
    protected $cityId;

    /**
     * @ORM\Column(name="mbps")
     */
    protected $mbps;

    /**
     * @ORM\Column(name="price")
     */
    protected $price;

    /**
     * @ORM\Column(name="is_pay")
     */
    protected $isPay;

    /**
     * @ORM\Column(name="paid_at")
     */
    protected $paidAt;

    /**
     * @ORM\Column(name="payment_method")
     */
    protected $paymentMethod;

    /**
     * @ORM\Column(name="note")
     */
    protected $note;

    /**
     * @ORM\Column(name="created_at")
     */
    protected $createdAt;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getClientId() {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId) {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getCityId() {
        return $this->cityId;
    }

    /**
     * @param mixed $cityId
     */
    public function setCityId($cityId) {
        $this->cityId = $cityId;
    }

    /**
     * @return mixed
     */
    public function getMbps() {
        return $this->mbps;
    }

    /**
     * @param mixed $mbps
     */
    public function setMbps($mbps) {
        $this->mbps = $mbps;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getIsPay() {
        return $this->isPay;
    }

    public static function getPayList() {
        return [self::IS_PAY => 'Да', self::IS_NOT_PAY => 'Не'];
    }

    public function getIsPayAsString() {
        $list = self::getPayList();
        if (isset($list[$this->isPay])) return $list[$this->isPay];
        return 'Unknown';
    }

    /**
     * @param mixed $isPay
     */
    public function setIsPay($isPay) {
        $this->isPay = $isPay;
    }

    /**
     * @return mixed
     */
    public function getPaidAt() {
        return $this->paidAt;
    }

    /**
     * @param mixed $paidAt
     */
    public function setPaidAt($paidAt) {
        $this->paidAt = $paidAt;
    }

    public function getPayAtBGFormat() {
        return date('d.m.Y', strtotime($this->paidAt));
    }

    /**
     * @return mixed
     */
    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    public static function getPaymentMethodList() {
        return [self::PAYMENT_METHOD_CASH => 'Кеш', self::PAYMENT_METHOD_BANK => 'Банков',];
    }

    public function getPaymentMethodAsString() {
        $list = self::getPaymentMethodList();
        if (isset($list[$this->paymentMethod])) return $list[$this->paymentMethod];
        return 'Unknown';
    }

    /**
     * @param mixed $paymentMethod
     */
    public function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return mixed
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note) {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }
}