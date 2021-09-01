<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\NewsStatus\InvalidateNews;
use App\Controller\NewsStatus\MoveToDraftsNews;
use App\Controller\NewsStatus\PrePublicateNews;
use App\Controller\NewsStatus\PublishNews;
use App\Controller\NewsStatus\UnpublishNews;
use App\Controller\NewsStatus\ValidateNews;
use App\Repository\NewsRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={
 *          "groups"={"news"}
 *     },
 *     itemOperations={
 *          "get",
 *          "patch_status_validate" = {
 *              "method" = "GET",
 *              "path" = "/news/{id}/validate",
 *              "controller" = ValidateNews::class,
 *              "denormalization_context" = {"groups" = {"status_update"}},
 *              "security" = "is_granted('ROLE_EDITOR') or is_granted('ROLE_ADMIN')"
 *          },
 *          "patch_status_invalidate" = {
 *              "method" = "GET",
 *              "path" = "/news/{id}/invalidate",
 *              "controller" = InvalidateNews::class,
 *              "denormalization_context" = {"groups" = {"status_update"}},
 *              "security" = "is_granted('ROLE_REVIEWER') or is_granted('ROLE_ADMIN')"
 *          },
 *          "patch_status_move_to_drafts" = {
 *              "method" = "GET",
 *              "path" = "/news/{id}/move-to-drafts",
 *              "controller" = MoveToDraftsNews::class,
 *              "denormalization_context" = {"groups" = {"status_update"}},
 *              "security" = "is_granted('ROLE_PUBLISHER') or is_granted('ROLE_ADMIN')"
 *          },
 *          "patch_status_pre_publicate" = {
 *              "method" = "GET",
 *              "path" = "/news/{id}/pre-publicate",
 *              "controller" = PrePublicateNews::class,
 *              "denormalization_context" = {"groups" = {"status_update"}},
 *              "security" = "is_granted('ROLE_REVIEWER') or is_granted('ROLE_ADMIN')"
 *          },
 *          "patch_status_publish" = {
 *              "method" = "GET",
 *              "path" = "/news/{id}/publish",
 *              "controller" = PublishNews::class,
 *              "denormalization_context" = {"groups" = {"status_update"}},
 *              "security" = "is_granted('ROLE_PUBLISHER') or is_granted('ROLE_ADMIN')"
 *          },
 *          "patch_status_unpublish" = {
 *              "method" = "GET",
 *              "path" = "/news/{id}/unpublish",
 *              "controller" = UnpublishNews::class,
 *              "denormalization_context" = {"groups" = {"status_update"}},
 *              "security" = "is_granted('ROLE_PUBLISHER') or is_granted('ROLE_ADMIN')"
 *          },
 *     },
 *     collectionOperations={
 *          "get",
 *          "post" = {
 *              "security" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_EDITOR') or is_granted('ROLE_PUBLISHER') or is_granted('ROLE_REVIEWER')",
 *              "messenger" = true,
 *              "output" = false,
 *              "status" = 201
 *          },
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News
{
    public const STATUS_IDEA = 'idea';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_READY_TO_PUBLISH = 'ready_to_publish';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_UNPUBLISHED = 'unpublished';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("news")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Groups("news")
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank()
     * @Groups("news")
     */
    private string $description;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="news")
     * @Groups("news")
     * @var Collection|Tag[]
     */
    private Collection $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="news")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     * @Groups("news")
     */
    private ?Category $category;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("news")
     */
    private DateTimeInterface $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("news")
     */
    private ?DateTimeInterface $startPublicationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("news")
     */
    private ?DateTimeInterface $endPublicationDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("news")
     */
    private ?string $publicationStatus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="news")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private User $author;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("news")
     */
    private ?DateTimeInterface $updateDate;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     */
    public function onAdd(): void
    {
        $this->setPublicationStatus(self::STATUS_IDEA);
        $this->setCreationDate(new \DateTime('now'));
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function onUpdate(): void
    {
        $this->setUpdateDate(new \DateTime('now'));
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

    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getStartPublicationDate(): ?DateTimeInterface
    {
        return $this->startPublicationDate;
    }

    public function setStartPublicationDate(?DateTimeInterface $startPublicationDate): self
    {
        $this->startPublicationDate = $startPublicationDate;

        return $this;
    }

    public function getEndPublicationDate(): ?DateTimeInterface
    {
        return $this->endPublicationDate;
    }

    public function setEndPublicationDate(?DateTimeInterface $endPublicationDate): self
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

    public function getUpdateDate(): ?DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

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
