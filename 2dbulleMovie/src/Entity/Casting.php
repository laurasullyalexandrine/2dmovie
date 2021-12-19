<?php

namespace App\Entity;

use App\Repository\CastingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Ignore;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CastingRepository::class)
 */
class Casting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $personage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $creditOrder;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="castings")
     * @ORM\JoinColumn(nullable=true)
     * @Ignore()
     */
    private $movie;

    /**
     * @ORM\ManyToMany(targetEntity=Person::class, inversedBy="castings")
     * @ORM\JoinColumn(nullable=true)
     * @Ignore()
     */
    private $person;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->person = new ArrayCollection();
    }

    // cette méthode permet de définir le comportement à adopter lorsque l'objet est traité comme une chaine de caractère
    public function __toString()
    {   
           return $this->personage;
    }    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonage(): ?string
    {
        return $this->personage;
    }

    public function setPersonage(string $personage): self
    {
        $this->personage = $personage;

        return $this;
    }

    public function getCreditOrder(): ?int
    {
        return $this->creditOrder;
    }

    public function setCreditOrder(?int $creditOrder): self
    {
        $this->creditOrder = $creditOrder;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPerson(): Collection
    {
        return $this->person;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->person->contains($person)) {
            $this->person[] = $person;
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        $this->person->removeElement($person);

        return $this;
    }
}
