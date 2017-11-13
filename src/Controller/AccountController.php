<?php

declare(strict_types = 1);

namespace User\Controller;

use User\Dto\Request\Account;

/**
 * Simple account CRUD.
 *
 * @package User\Controller
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class AccountController extends AbstractCrudController
{
    /**
     * {@inheritdoc}
     */
    protected function getDtoType(): string
    {
        return Account::class;
    }
}
