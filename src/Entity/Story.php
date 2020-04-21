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
 * @ORM\Entity(repositoryClass="App\Repository\StoryRepository")
 * @UniqueEntity("title", message="violation.title.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class Story extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Assert\NotBlank(message="violation.description.blank")
     * @Assert\Length(
     *     min=1,
     *     max=60,
     *     minMessage="violation.title.too_short",
     *     maxMessage="violation.title.too_long"
     * )
     *
     * @var string|null
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string|null
     */
    private ?string $slug = null;

    /**
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="story", cascade={"remove"})
     *
     * @Assert\Count(min=1, minMessage="violation.story.no_episode")
     *
     * @var Collection|null
     */
    private ?Collection $episodes = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="user", cascade={"persist", "remove"})
     *
     * @var Collection|null
     */
    private ?Collection $votes = null;

    /**
     * Story constructor.
     */
    public function __construct()
    {
        $this->episodes = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title ?: '';
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
            $episode->setStory($this);
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
            if ($episode->getStory() === $this) {
                $episode->setStory(null);
            }
        }
    }

    /**
     * @return Collection|null
     */
    public function getVotes(): ?Collection
    {
        return $this->votes;
    }

    /**
     * @param Vote $vote
     * @return void
     */
    public function addVote(Vote $vote): void
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
        }
    }

    /**
     * @param Vote $vote
     * @return void
     */
    public function removeVote(Vote $vote): void
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
        }
    }
}
