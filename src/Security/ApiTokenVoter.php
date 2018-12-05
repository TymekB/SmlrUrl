<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 22.11.18
 * Time: 20:19
 */

namespace App\Security;


use App\Entity\ApiToken;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ApiTokenVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject): bool
    {
        if(!in_array($attribute, [self::VIEW, self::EDIT], true)) {
            return false;
        }

        if(!($subject instanceof ApiToken)) {
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
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token):bool
    {
        $user = $token->getUser();

        if(!($user instanceof User)) {
            return false;
        }

        /** @var ApiToken $shortUrl */
        $apiToken = $subject;

        switch($attribute) {
            case self::VIEW:
                return $this->canView($apiToken, $user);
            case self::EDIT:
                return $this->canEdit($apiToken, $user);
        }

        return false;
    }

    /**
     * @param ApiToken $apiToken
     * @param User $user
     * @return bool
     */
    public function canView(ApiToken $apiToken, User $user): bool
    {
        return $this->canEdit($apiToken, $user);
    }

    /**
     * @param ApiToken $apiToken
     * @param User $user
     * @return bool
     */
    public function canEdit(ApiToken $apiToken, User $user): bool
    {
        return $user === $apiToken->getUser();
    }
}