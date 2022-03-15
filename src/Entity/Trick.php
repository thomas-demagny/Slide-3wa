<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TrickRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[UniqueEntity("title", message: "Ce nom est déjà utilisé")]
class Trick
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Avec un titre c'est mieux ;)")]
    #[Assert\Length(
        min: 10,
        max: 100,
        minMessage: "Le titre doit contenir minimum {{ limit }} caractères",
        maxMessage: "Le titre ne peut contenir que {{ limit }} caractères maximum"
    )]
    private ?string $title;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Merci de remplir une description")]
    #[Assert\Length(
        min: 20,
        minMessage: "Un minimum de {{ limit }} caractères est requis"
    )]
    private ?string $description;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface|null $createdAt;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    /**
     * @var ArrayCollection|Collection
     */
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'tricks', cascade: ['persist'])]
    private Collection|ArrayCollection $categories;

    /**
     * @var ArrayCollection|Collection
     */
    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection|ArrayCollection $comments;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $slug;

    /**
     * @var Collection|Image
     */
    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Image::class, cascade: ['persist'], orphanRemoval: true)]
    private Image|Collection $images;


    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return Trick
     */
    public function setUser(?User $user): Trick
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addTrick($this);
        }

        return $this;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeTrick($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     * @return $this
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Image
     */
    public function getImages(): Image
    {
        return $this->images;
    }

    /**
     * @param Image $images
     * @return $this
     */
    public function addImages(Image $images): self
    {
        if (!$this->images->contains($images)) {
            $this->images[] = $images;
            $images->setTrick($this);
        }

        return $this;
    }

    /**
     * @param Image $images
     * @return $this
     */
    public function removeImage(Image $images): self
    {
        if ($this->images->removeElement($images)) {
            // set the owning side to null (unless already changed)
            if ($images->getTrick() === $this) {
                $images->setTrick(null);
            }
        }

        return $this;
    }
}