<?php

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
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $secretAuthor;


    /**
     * @var string
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\User",
     *     inversedBy="protagonists",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="protagonist", cascade={"persist", "remove"})
     */
    private $posts;

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
     * @param string $name
     * @param bool $secretAuthor
     * @param string|null $avatar
     * @param User|null $user
     * @return Protagonist
     */
    static public function create(string $name, bool $secretAuthor, string $avatar = null, User $user = null): Protagonist
    {
        $protagonist = new Protagonist();
        $protagonist->setName($name);
        $protagonist->setSecretAuthor($secretAuthor);
        $protagonist->setAvatar($avatar);
        $protagonist->setUser($user);

        return $protagonist;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
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
     * @return bool|null
     */
    public function getSecretAuthor(): ?bool
    {
        return $this->secretAuthor;
    }

    /**
     * @param bool|null $secretAuthor
     * @return self
     */
    public function setSecretAuthor(?bool $secretAuthor): self
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
     * @param Collection|null $posts
     * @return self
     */
    public function setPosts(?Collection $posts): self
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @param Post $post
     * @return self
     */
    public function addPost(?Post $post): self
    {
        if ($post && !$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setProtagonist($this);
        }

        return $this;
    }

    /**
     * @param iterable $posts
     * @return self
     */
    public function addPosts(?iterable $posts): self
    {
        if ($posts) {
            foreach ($posts as $post) {
                $this->addPost($post);
            }
        }

        return $this;
    }
}
