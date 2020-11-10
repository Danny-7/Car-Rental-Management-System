<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Entrez un type valide")
     */
    private ?string $type;

    /**
     * @ORM\Column(type="json")
     */
    private array $datasheet = [];

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(value = 0, message="Entrez un prix valide")
     */
    private ?float $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $rent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private ?User $idOwner;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value=0, message="Entrez une quantité supérieure à 0")
     */
    private ?int $quantity;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="car", orphanRemoval=true)
     */
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDatasheet(): ?array
    {
        return $this->datasheet;
    }

    public function setDatasheet(array $datasheet): self
    {
        $this->datasheet = $datasheet;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getRent(): ?string
    {
        return $this->rent;
    }

    public function setRent(string $rent): self
    {
        $this->rent = $rent;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIdOwner(): ?User
    {
        return $this->idOwner;
    }

    public function setIdOwner(?UserInterface $idOwner): self
    {
        $this->idOwner = $idOwner;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        $this->comments->add($comment);

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCar() === $this) {
                $comment->setCar(null);
            }
        }

        return $this;
    }

}
