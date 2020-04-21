<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"user_id", "story_id"})
 *     }
 * )
 */
class Vote extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="votes", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @var User
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="Story", inversedBy="votes", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @var Story
     */
    private Story $story;

    /**
     * Vote constructor.
     * @param User $user
     * @param Story $story
     */
    public function __construct(User $user, Story $story)
    {
        $this->user = $user;
        $this->story = $story;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Story
     */
    public function getStory(): Story
    {
        return $this->story;
    }
}
