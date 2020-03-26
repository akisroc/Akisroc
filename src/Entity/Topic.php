<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicRepository")
 */
class Topic extends AbstractEntity
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
     * @ORM\ManyToOne(targetEntity="Board", inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank(message="violation.board.blank")
     *
     * @var Board|null
     */
    private ?Board $board = null;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Post",
     *     mappedBy="topic",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     * @ORM\OrderBy({
     *     "id"="ASC"
     * })
     * @Assert\NotBlank()
     */
    private ?Collection $posts = null;

    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     * @return void
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
     * @return Board|null
     */
    public function getBoard(): ?Board
    {
        return $this->board;
    }

    /**
     * @param Board|null $board
     * @return void
     */
    public function setBoard(?Board $board): void
    {
        $this->board = $board;
    }

    /**
     * @return Collection|null
     */
    public function getPosts(): ?Collection
    {
        return $this->posts;
    }

    /**
     * @return Post|null
     */
    public function getLastPost(): ?Post
    {
        return $this->getPosts()->last();
    }

    /**
     * @param Post $post
     * @return void
     */
    public function addPost(Post $post): void
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setTopic($this);
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
            if ($post->getTopic() === $this) {
                $post->setTopic(null);
            }
        }
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        if ($board = $this->getBoard()) {
            return $board->getCategory();
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        if ($category = $this->getCategory()) {
            return $category->getType();
        }

        return null;
    }
}
