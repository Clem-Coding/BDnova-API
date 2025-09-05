<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "authors")]
#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pseudonym = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $biography = null;

    #[ORM\Column(length: 255)]
    private ?string $photUrl = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, AuthorLinks>
     */
    #[ORM\OneToMany(targetEntity: AuthorLinks::class, mappedBy: 'author')]
    private Collection $authorLinks;

    public function __construct()
    {
        $this->authorLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPseudonym(): ?string
    {
        return $this->pseudonym;
    }

    public function setPseudonym(?string $pseudonym): static
    {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getPhotUrl(): ?string
    {
        return $this->photUrl;
    }

    public function setPhotUrl(string $photUrl): static
    {
        $this->photUrl = $photUrl;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, AuthorLinks>
     */
    public function getAuthorLinks(): Collection
    {
        return $this->authorLinks;
    }

    public function addAuthorLink(AuthorLinks $authorLink): static
    {
        if (!$this->authorLinks->contains($authorLink)) {
            $this->authorLinks->add($authorLink);
            $authorLink->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthorLink(AuthorLinks $authorLink): static
    {
        if ($this->authorLinks->removeElement($authorLink)) {
            // set the owning side to null (unless already changed)
            if ($authorLink->getAuthor() === $this) {
                $authorLink->setAuthor(null);
            }
        }

        return $this;
    }
}
