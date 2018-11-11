<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 06.11.18
 * Time: 18:09
 */

namespace App\Security;


use App\Entity\ShortUrl as ShortUrlAlias;
use App\Entity\ShortUrl;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ShortUrlVoter extends Voter
{
     const VIEW = 'view';
     const EDIT = 'edit';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if(!($subject instanceof ShortUrlAlias)) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if(!($user instanceof User)) {
            return false;
        }

        /** @var ShortUrl $shortUrl */
        $shortUrl = $subject;

        switch($attribute) {
            case self::VIEW:
                return $this->canView($shortUrl, $user);
            case self::EDIT:
                return $this->canEdit($shortUrl, $user);
        }
    }

    public function canView(ShortUrl $shortUrl, User $user)
    {
        return $this->canEdit($shortUrl, $user);
    }

    public function canEdit(ShortUrl $shortUrl, User $user)
    {
        return $user === $shortUrl->getUser();
    }
}