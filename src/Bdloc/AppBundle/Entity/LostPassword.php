<?php

namespace Bdloc\AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class LostPassword
{

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez fournir l'email de la personne")
     * @Assert\Length(
     *          min="4",
     *          max="255",
     *          minMessage="L'email doit faire au moins {{ limit }} caractères",
     *          maxMessage="l'email ne peut pas être plus long que {{ limit }} caractères"
     *  )
     */
    private $email;

     /**
     * Set email
     *
     * @param string $email
     * @return LostPassword
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


    
}
