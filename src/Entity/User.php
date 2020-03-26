<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username", message="violation.username.not_unique")
 * @UniqueEntity("email", message="violation.email.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class User extends AbstractEntity implements UserInterface, EquatableInterface
{
    /**
     * @var string
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     * @Assert\NotBlank(message="violation.name.blank")
     * @Assert\Regex(
     *     pattern="/^[ a-zA-Z0-9éÉèÈêÊëËäÄâÂàÀïÏöÖôÔüÜûÛçÇ']+$/",
     *     message="violation.name.invalid_characters"
     * )
     * @Assert\Length(
     *     min=1,
     *     max=30,
     *     minMessage="violation.name.too_short",
     *     maxMessage="violation.name.too_long"
     * )
     */
    private ?string $username = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank(message="violation.email.blank")
     * @Assert\Email(message="violation.email.wrong_format")
     *
     * @var string|null
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     *
     * @Assert\Url(message="violation.uri.wrong_format")
     * @Assert\Length(
     *     min=1,
     *     max=500,
     *     minMessage="violation.uri.too_short",
     *     maxMessage="violation.uri.too_long"
     * )
     *
     * @var string|null
     */
    private ?string $avatar = null;

    /**
     * @ORM\Column(type="string", length=511, nullable=false)
     *
     * @var string|null
     */
    private ?string $password = null;

    /**
     * @Assert\NotBlank(message="violation.password.blank")
     * @Assert\Length(
     *     min=8,
     *     max=4000,
     *     minMessage="violation.password.too_short",
     *     maxMessage="violation.password.too_long"
     * )
     *
     * @var string|null
     */
    private ?string $plainPassword = null;

    /**
     * @ORM\Column(type="string", length=127, nullable=false)
     *
     * @var string|null
     */
    private ?string $salt = null;

    /**
     * @ORM\Column(type="json", length=31, nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @var string[]
     */
    private array $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"username"})
     *
     * @var string|null
     */
    public ?string $slug = null;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public bool $enabled = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user", cascade={"persist", "remove"})
     */
    private Collection $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Protagonist", mappedBy="user", cascade={"persist", "remove"})
     */
    private Collection $protagonists;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->protagonists = new ArrayCollection();
        $this->salt = $this->generateSalt();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->username;
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
     * @return void
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
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
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
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
     * @return void
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
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
     * @return void
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
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
     * @return void
     */
    public function setSalt(?string $salt): void
    {
        $this->salt = $salt;
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
     * @return void
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
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
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
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
     * @return void
     */
    public function addPost(Post $post): void
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }
    }

    /**
     * @param Post $post
     * @return void
     */
    public function removePost(Post $post): void
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }
    }

    /**
     * @return Collection|null
     */
    public function getProtagonists(): ?Collection
    {
        return $this->protagonists;
    }

    /**
     * @param Protagonist $protagonist
     * @return void
     */
    public function addProtagonist(Protagonist $protagonist): void
    {
        if (!$this->protagonists->contains($protagonist)) {
            $this->protagonists->add($protagonist);
            $protagonist->setUser($this);
        }
    }

    /**
     * @param Protagonist $protagonist
     * @return void
     */
    public function removeProtagonist(Protagonist $protagonist): void
    {
        if ($this->protagonists->contains($protagonist)) {
            $this->protagonists->removeElement($protagonist);
            if ($protagonist->getUser() === $this) {
                $protagonist->setUser(null);
            }
        }
    }

    /**
     * {@inheritdoc}
     * @return string[]
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('ROLE_ADMIN');
    }

    /**
     * @param string[] $roles
     * @return void
     */
    public function addRoles(iterable $roles): void
    {
        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    /**
     * @param string $role
     * @return void
     */
    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
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
     *
     * @throws \Exception
     */
    public function generateSalt(): string
    {
        return substr(base64_encode(random_bytes(64)), 8, 72);
    }

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     * @param array|null $payload
     *
     * @return void
     */
    public function validateRoles(ExecutionContextInterface $context, array $payload = null): void
    {
        foreach ($this->roles as $role) {
            if (strpos($role, 'ROLE_') !== 0) {
                $context
                    ->buildViolation('violation.roles.wrong_format')
                    ->atPath('roles')
                    ->addViolation();
            }
        }

        if (false === in_array('ROLE_USER', $this->roles, true)) {
            $context
                ->buildViolation('violation.roles.missing_user_role')
                ->atPath('roles')
                ->addViolation();
        }
    }
}
