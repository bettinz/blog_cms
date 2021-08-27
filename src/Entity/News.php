<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="news")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="news")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startPublicationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endPublicationDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $publicationStatus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="news")
     */
    private $author;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getStartPublicationDate(): ?\DateTimeInterface
    {
        return $this->startPublicationDate;
    }

    public function setStartPublicationDate(\DateTimeInterface $startPublicationDate): self
    {
        $this->startPublicationDate = $startPublicationDate;

        return $this;
    }

    public function getEndPublicationDate(): ?\DateTimeInterface
    {
        return $this->endPublicationDate;
    }

    public function setEndPublicationDate(?\DateTimeInterface $endPublicationDate): self
    {
        $this->endPublicationDate = $endPublicationDate;

        return $this;
    }

    public function getPublicationStatus(): ?string
    {
        return $this->publicationStatus;
    }

    public function setPublicationStatus(?string $publicationStatus): self
    {
        $this->publicationStatus = $publicationStatus;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
