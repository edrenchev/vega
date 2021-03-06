<?php
namespace Client\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single post in a blog.
 * @ORM\Entity(repositoryClass="\Client\Repository\ClientRepository")
 * @ORM\Table(name="clients")
 */
class Client {

    // User status constants.
    const STATUS_ACTIVE        = 1; // Active user.
    const STATUS_INACTIVE      = 2; // Inactive user.

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @ORM\Column(name="phone")
     */
    protected $phone;

    /**
     * @ORM\ManyToOne(targetEntity="\City\Entity\City", fetch="EAGER")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    protected $cityId;

    /**
     * @ORM\Column(name="address")
     */
    protected $address;

    /**
     * @ORM\Column(name="mbps")
     */
    protected $mbps;

    /**
     * @ORM\Column(name="monthly_price")
     */
    protected $monthlyPrice;

    /**
     * @ORM\Column(name="payday")
     */
    protected $payday;


    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="join_date")
     */
    protected $joinDate;

    /**
     * @ORM\Column(name="notes")
     */
    protected $notes;

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
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone) {
        $this->phone = $phone;
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
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address) {
        $this->address = $address;
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
    public function getMonthlyPrice() {
        return $this->monthlyPrice;
    }

    /**
     * @param mixed $monthlyPrice
     */
    public function setMonthlyPrice($monthlyPrice) {
        $this->monthlyPrice = $monthlyPrice;
    }

    /**
     * @return mixed
     */
    public function getPayday() {
        return $this->payday;
    }

    /**
     * @param mixed $payday
     */
    public function setPayday($payday) {
        $this->payday = $payday;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }


    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive'
        ];
    }

    /**
     * Returns user status as string.
     * @return string
     */
	public function getStatusAsString() {
		$list = self::getStatusList();
		if (isset($list[$this->status]))
			return $list[$this->status];

		return 'Unknown';
	}

    /**
     * @param mixed $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getJoinDate() {
        return $this->joinDate;
    }

    /**
     * @return mixed
     */
    public function getJoinDateBGFormat() {
    	if($this->joinDate == '0000-00-00') return '';
        return date('d.m.Y', strtotime($this->joinDate));
    }

    /**
     * @param mixed $joinDate
     */
    public function setJoinDate($joinDate) {
        $this->joinDate = $joinDate;
    }

	/**
	 * @return mixed
	 */
	public function getNotes() {
		return $this->notes;
	}

	/**
	 * @param mixed $notes
	 */
	public function setNotes($notes) {
		$this->notes = $notes;
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