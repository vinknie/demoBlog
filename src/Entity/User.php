<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 * fields = {"email"},
 * message="Un compte est déjà existant à cette adresse Email !!"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères")
     * @Assert\EqualTo(propertyPath="confirm_password", message="Les mots de passe ne correspondent pas")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe ne correspondent pas")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="json")
     */
    private $Roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /*
        Pour pouvoir encoder le mot de passe, il faut que notre classe (entité) User implémente de la classe UserInterface
        Il faut absolument déclarer les méthodes getRoles(), getSalt(), eraseCredentials(), getUsername(), getPassword()
    */

    // cette méthode est uniquement destinée à nettoyer les mots de passes en texte brut éventuellemtn stockés
    public function eraseCredentials()
    {
    }

    // renvoie la chaine de caractères non encodé que l'utilisateur a saisi, qui a été utiliser à l'origin pour coder le mot de passe
    public function getSalt()
    {
    }

    // cette méthode renvoi un tableau de chaine de caractères où sont stockés les rôles accordés à l'utilisateur
    public function getRoles()
    {
        // return ['ROLE_USER'];
        return $this->Roles;
    }
    public function __toString()
    {
        return $this->email;
    }

    public function setRoles(array $Roles): self
    {
        $this->Roles = $Roles;

        return $this;
    }
}
