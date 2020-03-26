<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=8191, nullable=false)
     */
    private ?string $content = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Topic", inversedBy="posts", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ?Topic $topic = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private ?User $user = null;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param null|string $content
     * @return self
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Topic|null
     */
    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    /**
     * @param Topic|null $topic
     * @return self
     */
    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

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
     * @return Board|null
     */
    public function getBoard(): ?Board
    {
        if ($topic = $this->getTopic()) {
            return $topic->getBoard();
        }

        return null;
    }
}
