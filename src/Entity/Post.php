<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8191, nullable=false)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Topic", inversedBy="posts", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Protagonist", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $protagonist;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @param Topic|null $topic
     * @param User|null $user
     * @param Protagonist|null $protagonist
     * @return Post
     */
    static public function create(string $content, Topic $topic = null,
                                  User $user = null, Protagonist $protagonist = null
    ): Post {
        $post = new Post();
        $post->setContent($content);
        $post->setTopic($topic);
        $post->setUser($user);
        $post->setProtagonist($protagonist);

        return $post;
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
     * @return Protagonist|null
     */
    public function getProtagonist(): ?Protagonist
    {
        return $this->protagonist;
    }

    /**
     * @param Protagonist|null $protagonist
     * @return self
     */
    public function setProtagonist(?Protagonist $protagonist): self
    {
        $this->protagonist = $protagonist;

        return $this;
    }
}
