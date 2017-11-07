<?php

declare(strict_types = 1);

namespace User\Component\Security\Core\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Abstract default implementation of voter for CRUD.
 *
 * @package User\Component\Security\Core\Authorization\Voter
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
abstract class CrudVoter extends Voter
{
    const CREATE = 'create';
    const GET = 'get';
    const GET_LIST = 'getList';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * {@inheritdoc}
     */
    final protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [self::CREATE, self::GET, self::GET_LIST, self::UPDATE, self::DELETE])) {
            return false;
        }

        return $this->supportsSubject($subject);
    }

    /**
     * Checks if subject supported.
     *
     * @param object $subject
     *
     * @return bool
     */
    abstract protected function supportsSubject(object $subject): bool;
}