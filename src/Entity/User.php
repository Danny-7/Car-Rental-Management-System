<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min = 2,
     *     max = 255,
     *     minMessage = "Votre nom est trop court il doit contenir au moins 2 caractères",
     *     maxMessage = "Votre nom est trop long il doit faire au maximum 255 caractères"
     * )
     * @Assert\Regex(pattern="/\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+/", message="Veuillez entrer un nom valide")
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Entrez une adresse email valide")
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", message="La chaine de caractères doit contenir des caractères miniscules et majuscules, un chiffre et avoir une longueur de 8 caractères ")
     */
    private ?string $password;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private ?string $role;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return [$this->getRole()];
    }

    public function getSalt(){}

    public function getUsername()
    {
        return $this->getName();
    }

    public function eraseCredentials(){}
}
