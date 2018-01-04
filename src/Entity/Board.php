<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoardRepository")
 */
class Board
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=63, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="boards", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="board", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    private $topics;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @param string|null $description
     * @param Category|null $category
     * @param iterable|null $topics
     * @return Board
     */
    static public function create(string $title, string $description = null,
                                  Category $category = null, iterable $topics = []
    ): Board {
        $board = new Board();
        $board->setTitle($title);
        $board->setDescription($description);
        $board->setCategory($category);
        $board->addTopics($topics);

        return $board;
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return self
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getTopics(): ?Collection
    {
        return $this->topics;
    }

    /**
     * @return Topic|null
     */
    public function getLastTopic(): ?Topic
    {
        return $this->getTopics()->last();
    }

    /**
     * @param Collection|null $topics
     * @return self
     */
    public function setTopics(?Collection $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * @param Topic|null $topic
     * @return self
     */
    public function addTopic(?Topic $topic): self
    {
        if ($topic && !$this->topics->contains($topic)) {
            $this->topics->add($topic);
        }

        return $this;
    }

    /**
     * @param iterable $topics
     * @return self
     */
    public function addTopics(?iterable $topics): self
    {
        if ($topics) {
            foreach ($topics as $topic) {
                $this->addTopic($topic);
            }
        }

        return $this;
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
