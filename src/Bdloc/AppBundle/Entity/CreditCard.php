<?php

namespace Bdloc\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CreditCard
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Bdloc\AppBundle\Entity\CreditCardRepository")
 */
class CreditCard
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="paypalid", type="string", length=255)
     */
    private $paypalid;

    /**
     * @var \integer
     *
     * @ORM\Column(name="monthValidUntil", type="integer")
     */
    private $monthValidUntil;

    /**
     * @var \integer
     *
     * @ORM\Column(name="yearValidUntil", type="integer")
     */
    private $yearValidUntil;

    /**
     * @var string
     *
     * @ORM\Column(name="cardNumber", type="string", length=16)
     */
    private $cardNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="userCard", type="string", length=255)
     */
    private $userCard;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModified", type="datetime")
     */
    private $dateModified;

    private $codecvv;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Bdloc\AppBundle\Entity\User", inversedBy="creditCard")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set paypalid
     *
     * @param string $paypalid
     * @return CreditCard
     */
    public function setPaypalid($paypalid)
    {
        $this->paypalid = $paypalid;

        return $this;
    }

    /**
     * Get paypalid
     *
     * @return string 
     */
    public function getPaypalid()
    {
        return $this->paypalid;
    }

    /**
     * Set validUntil
     *
     * @param \DateTime $validUntil
     * @return CreditCard
     */
    public function setValidUntil($validUntil)
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    /**
     * Get validUntil
     *
     * @return \DateTime 
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * Set cardNumber
     *
     * @param string $cardNumber
     * @return CreditCard
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string 
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set userCard
     *
     * @param string $userCard
     * @return CreditCard
     */
    public function setUserCard($userCard)
    {
        $this->userCard = $userCard;

        return $this;
    }

    /**
     * Get userCard
     *
     * @return string 
     */
    public function getUserCard()
    {
        return $this->userCard;
    }

    
    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return CreditCard
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     * @return CreditCard
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    public function setCodecvv($codecvv) {
        $this->codecvv = $codecvv;
    }

    public function getCodecvv() {
        return $this->codecvv;
    }

    /**
     * Set user
     *
     * @param \Bdloc\AppBundle\Entity\User $user
     * @return CreditCard
     */
    public function setUser(\Bdloc\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Bdloc\AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set monthValidUntil
     *
     * @param integer $monthValidUntil
     * @return CreditCard
     */
    public function setMonthValidUntil($monthValidUntil)
    {
        $this->monthValidUntil = $monthValidUntil;

        return $this;
    }

    /**
     * Get monthValidUntil
     *
     * @return integer 
     */
    public function getMonthValidUntil()
    {
        return $this->monthValidUntil;
    }

    /**
     * Set yearValidUntil
     *
     * @param integer $yearValidUntil
     * @return CreditCard
     */
    public function setYearValidUntil($yearValidUntil)
    {
        $this->yearValidUntil = $yearValidUntil;

        return $this;
    }

    /**
     * Get yearValidUntil
     *
     * @return integer 
     */
    public function getYearValidUntil()
    {
        return $this->yearValidUntil;
    }

    /**
     * @ORM\PrePersist
     */
    public function beforeInsert() {
        $this->setDateCreated(new \DateTime());
        $this->setDateModified(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function beforeEdit() {
        $this->setDateModified(new \DateTime());
    }
}
