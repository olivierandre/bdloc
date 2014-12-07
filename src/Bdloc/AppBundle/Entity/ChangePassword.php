<?php

namespace Bdloc\AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePassword
{

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez fournir votre ancien mot de passe")
     * 
     * @SecurityAssert\UserPassword(
     *     message = "Votre ancien mot de passe est incorrect"
     * )
     */
    private $oldPassword;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez fournir un nouveau mot de passe")
     * @Assert\Length(
     *          min="8",
     *          max="255",
     *          minMessage="Le mot de passe doit faire au moins {{ limit }} caractères",
     *          maxMessage="le mot de passe ne peut pas être plus long que {{ limit }} caractères"
     *  )
     * 
     */
    private $newPassword;

    public function getOldPassword() {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword) {
        $this->oldPassword = $oldPassword;
    }

    public function getNewPassword() {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword) {
        $this->newPassword = $newPassword;
    }

}
