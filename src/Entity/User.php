<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, EquatableInterface
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
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=63, nullable=true)
     * @Assert\Url()
     */
    private $avatar;

    /**
     * @var string
     * @ORM\Column(type="string", length=511, nullable=false)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=12, max=4096)
     */
    private $plainPassword;

    /**
     * @var string
     * @ORM\Column(type="string", length=123, nullable=false)
     */
    private $salt;

    /**
     * @var array
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user", cascade={"persist", "remove"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Protagonist", mappedBy="user", cascade={"persist", "remove"})
     */
    private $protagonists;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->protagonists = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string|null $avatar
     * @param iterable $posts
     * @param iterable $protagonists
     * @return User
     */
    static public function create(string $username, string $email, string $avatar = null,
                                  iterable $posts = [], iterable $protagonists = []
    ): User {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setAvatar($avatar);
        $user->addPosts($posts);
        $user->addProtagonists($protagonists);

        return $user;
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
     * {@inheritdoc}
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     * @return self
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     * @param null|string $email
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

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
     * {@inheritdoc}
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param null|string $password
     * @return self
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param null|string $salt
     * @return self
     */
    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param null|string $plainPassword
     * @return self
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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

    /**
     * @return Collection|null
     */
    public function getProtagonists(): ?Collection
    {
        return $this->protagonists;
    }

    /**
     * @param Collection|null $protagonists
     * @return self
     */
    public function setProtagonists(?Collection $protagonists): self
    {
        $this->protagonists = $protagonists;

        return $this;
    }

    /**
     * @param Post $protagonist
     * @return self
     */
    public function addProtagonist(?Post $protagonist): self
    {
        if ($protagonist && !$this->protagonists->contains($protagonist)) {
            $this->protagonists->add($protagonist);
        }

        return $this;
    }

    /**
     * @param iterable $protagonists
     * @return self
     */
    public function addProtagonists(?iterable $protagonists): self
    {
        if ($protagonists) {
            foreach ($protagonists as $protagonist) {
                $this->addPost($protagonist);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * {@inheritdoc}
     * @return void
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user): bool
    {
        if ($this->username !== $user->getUsername()) {
            return false;
        }
        if ($this->password !== $user->getPassword()) {
            return false;
        }
        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    static public function generateSalt(): string
    {
        return substr(base64_encode(random_bytes(64)), 8, 72);
    }
}
