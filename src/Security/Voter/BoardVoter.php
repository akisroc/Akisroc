<?php

namespace App\Security\Voter;

use App\Entity\Board;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BoardVoter extends Voter
{
    const SEE = 'board.see';
    const ADD_TOPIC = 'board.add_topic';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::SEE, self::ADD_TOPIC])) {
            return false;
        }

        if (!$subject instanceof Board) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        /** @var Board $board */
        $board = $subject;

        switch ($attribute) {
            case self::SEE:
                return $this->canSee($board, $user);
            case self::ADD_TOPIC:
                return $this->canAddPost($board, $user);
        }

        throw new \LogicException('This code should not be reached.');
    }

    /**
     * @param Board $board
     * @param User|string|null $user
     * @return bool
     */
    private function canSee(Board $board, $user): bool
    {
        return true;
    }

    /**
     * @param Board $board
     * @param User|string|null $user
     * @return bool
     */
    private function canAddPost(Board $board, $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        $type = $board->getType();
        if ($type === Category::TYPE_HRP) {
            return true;
        } else if ($type === Category::TYPE_RP && $user->getProtagonists()->count() > 0) {
            return true;
        }

        return false;
    }
}