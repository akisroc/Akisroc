<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProtagonistRepository")
 * @UniqueEntity("name", message="violation.name.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class Protagonist extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=31, nullable=false, unique=true)
     *
     * @Assert\NotBlank(message="violation.username.blank")
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
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private bool $anonymous = true;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="protagonists")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Blameable(on="create")
     *
     * @var User|null
     */
    private ?User $user = null;

    /**
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="protagonist")
     *
     * @var Collection|null
     */
    private ?Collection $episodes = null;

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
        $this->episodes = new ArrayCollection();
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
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isAnonymous(): bool
    {
        return $this->anonymous;
    }

    /**
     * @param bool $anonymous
     * @return void
     */
    public function setAnonymous(bool $anonymous): void
    {
        $this->anonymous = $anonymous;
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
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return void
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    /**
     * @param Episode $episode
     * @return void
     */
    public function addEpisode(Episode $episode): void
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes[] = $episode;
            $episode->setProtagonist($this);
        }
    }

    /**
     * @param Episode $episode
     * @return void
     */
    public function removeEpisode(Episode $episode): void
    {
        if ($this->episodes->contains($episode)) {
            $this->episodes->removeElement($episode);
            if ($episode->getProtagonist() === $this) {
                $episode->setProtagonist(null);
            }
        }
    }
}
