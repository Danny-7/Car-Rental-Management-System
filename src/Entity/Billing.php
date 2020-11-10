<?php

namespace App\Entity;

use App\Repository\BillingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BillingRepository::class)
 */
class Billing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $idUser;

    /**
     * @ORM\ManyToOne(targetEntity=Car::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Car $idCar;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThanOrEqual("today UTC", message="La date de début de location doit être supérieure ou égale à celle d'aujourdhui")
     */
    private ?\DateTimeInterface $startDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\GreaterThan("today UTC", message="La date de fin de location doit être supérieure à celle du début")
     */
    private ?\DateTimeInterface $endDate;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $paid;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $returned;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?UserInterface $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdCar(): ?Car
    {
        return $this->idCar;
    }

    public function setIdCar(?Car $idCar): self
    {
        $this->idCar = $idCar;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getReturned(): ?bool
    {
        return $this->returned;
    }

    public function setReturned(bool $returned): self
    {
        $this->returned = $returned;

        return $this;
    }
}
