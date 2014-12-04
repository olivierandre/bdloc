<?php

namespace Bdloc\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Bdloc\AppBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("email", message="Cet email existe déjà")
 * @UniqueEntity("username", message="Ce pseudo est déjà existant")
 */
class User implements UserInterface
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
     * @Assert\NotBlank(message="Veuillez fournir un pseudo")
     * @Assert\Length(
     *          min="4",
     *          max="255",
     *          minMessage="Le pseudo doit faire au moins {{ limit }} caractères",
     *          maxMessage="le pseudo ne peut pas être plus long que {{ limit }} caractères"
     * )
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     * @Assert\Email(message = "'{{ value }}' n'est pas un email valide.", checkMX = true)
     * @Assert\NotBlank(message="Veuillez fournir l'email de la personne")
     * @Assert\Length(
     *          min="4",
     *          max="255",
     *          minMessage="L'email doit faire au moins {{ limit }} caractères",
     *          maxMessage="l'email ne peut pas être plus long que {{ limit }} caractères"
     *  )
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez fournir un mot de passe")
     * @Assert\Length(
     *          min="8",
     *          max="255",
     *          minMessage="Le mot de passe doit faire au moins {{ limit }} caractères",
     *          maxMessage="le mot de passe ne peut pas être plus long que {{ limit }} caractères"
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     * 
     * @ORM\Column(name="zip", type="string", length=5, nullable=true)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isEnabled", type="boolean", nullable=true)
     */
    private $isEnabled;

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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return User
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     * @return User
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean 
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return User
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
     * @return User
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

    //  Appel des méthodes de l'interface 'UserInterface'
    public function eraseCredentials() {
        $this->password = null;
    }

    /**
     * @ORM\PrePersist
     */
    public function beforeInsert() {
        $this->setIsEnabled(false);
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
