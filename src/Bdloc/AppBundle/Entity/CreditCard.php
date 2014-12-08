<?php

namespace Bdloc\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CreditCard
 *
 * @ORM\Table()
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
     * @var \DateTime
     *
     * @ORM\Column(name="validUntil", type="date")
     */
    private $validUntil;

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
     * @ORM\Column(name="expirationCard", type="date")
     */
    private $expirationCard;

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
     * Set expirationCard
     *
     * @param \DateTime $expirationCard
     * @return CreditCard
     */
    public function setExpirationCard($expirationCard)
    {
        $this->expirationCard = $expirationCard;

        return $this;
    }

    /**
     * Get expirationCard
     *
     * @return \DateTime 
     */
    public function getExpirationCard()
    {
        return $this->expirationCard;
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
}
