<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProtagonistRepository")
 */
class Protagonist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    private ?string $name = null;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private bool $secretAuthor = true;


    /**
     * @var string
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private ?string $avatar = null;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\User",
     *     inversedBy="protagonists",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private ?User $user = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="protagonist", cascade={"persist", "remove"})
     */
    private ?Collection $posts = null;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Protagonist constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSecretAuthor(): bool
    {
        return $this->secretAuthor;
    }

    /**
     * @param bool $secretAuthor
     * @return self
     */
    public function setSecretAuthor(bool $secretAuthor): self
    {
        $this->secretAuthor = $secretAuthor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param null|string $avatar
     * @return self
     */
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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
     * @return self
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getPosts(): ?Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     * @return self
     */
    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setProtagonist($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     * @return self
     */
    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            if ($post->getProtagonist() === $this) {
                $post->setProtagonist(null);
            }
        }
    }
}
