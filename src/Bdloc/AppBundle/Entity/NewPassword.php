<?php

namespace Bdloc\AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class NewPassword
{

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez fournir un mot de passe")
     * @Assert\Length(
     *          min="8",
     *          max="255",
     *          minMessage="Le mot de passe doit faire au moins {{ limit }} caractères",
     *          maxMessage="le mot de passe ne peut pas être plus long que {{ limit }} caractères"
     *  )
     * 
     */
    private $password;

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

}
