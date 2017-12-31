<?php

namespace App\Security\Voter;

use App\Entity\Category;
use App\Entity\Topic;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TopicVoter extends Voter
{
    const SEE = 'topic.see';
    const ADD_POST = 'topic.add_post';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::SEE, self::ADD_POST])) {
            return false;
        }

        if (!$subject instanceof Topic) {
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

        /** @var Topic $topic */
        $topic = $subject;

        switch ($attribute) {
            case self::SEE:
                return $this->canSee($topic, $user);
            case self::ADD_POST:
                return $this->canAddPost($topic, $user);
        }

        throw new \LogicException('This code should not be reached.');
    }

    /**
     * @param Topic $topic
     * @param User|string|null $user
     * @return bool
     */
    private function canSee(Topic $topic, $user): bool
    {
        return true;
    }

    /**
     * @param Topic $topic
     * @param User|string|null $user
     * @return bool
     */
    private function canAddPost(Topic $topic, $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        $type = $topic->getType();
        if ($type === Category::TYPE_HRP) {
            return true;
        } else if ($type === Category::TYPE_RP && $user->getProtagonists()->count() > 0) {
            return true;
        }

        return false;
    }
}