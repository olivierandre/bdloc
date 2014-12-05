<?php

namespace Bdloc\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Bdloc\AppBundle\Entity\CartRepository")
 */
class Cart
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
     * @ORM\Column(name="status", type="string", length=50)
     */
    private $status;

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
     * @ORM\OneToMany(targetEntity="Bdloc\AppBundle\Entity\CartItem", mappedBy="cart")
     */
    private $cartItem;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Bdloc\AppBundle\Entity\User", inversedBy="cart")
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
     * Set status
     *
     * @param string $status
     * @return Cart
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Cart
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
     * @return Cart
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cartItem = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cartItem
     *
     * @param \Bdloc\AppBundle\Entity\CartItem $cartItem
     * @return Cart
     */
    public function addCartItem(\Bdloc\AppBundle\Entity\CartItem $cartItem)
    {
        $this->cartItem[] = $cartItem;

        return $this;
    }

    /**
     * Remove cartItem
     *
     * @param \Bdloc\AppBundle\Entity\CartItem $cartItem
     */
    public function removeCartItem(\Bdloc\AppBundle\Entity\CartItem $cartItem)
    {
        $this->cartItem->removeElement($cartItem);
    }

    /**
     * Get cartItem
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCartItem()
    {
        return $this->cartItem;
    }

    /**
     * Set user
     *
     * @param \Bdloc\AppBundle\Entity\User $user
     * @return Cart
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
