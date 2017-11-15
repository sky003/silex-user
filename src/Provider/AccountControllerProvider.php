<?php

declare(strict_types = 1);

namespace User\Provider;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;
use User\Controller\AccountController;
use User\Service\AccountCrudService;

/**
 * Provides the account CRUD routes.
 *
 * @package User\Provider
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class AccountControllerProvider implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $app['service.account_crud'] = function () use ($app) {
            return new AccountCrudService(
                $app['security.encoder_factory'],
                $app['orm.em'],
                $app['logger']
            );
        };
        $app['controller.account_controller'] = function () use ($app) {
            return new AccountController(
                $app['serializer'],
                $app['validator'],
                $app['service.account_crud'],
                $app['logger']
            );
        };

        /**
         * @var ControllerCollection $controllers
         */
        $controllers = $app['controllers_factory'];

        $controllers->post('/', 'controller.account_controller:create')
            ->bind('accounts/create');
        $controllers->get('/{id}', 'controller.account_controller:get')
            ->bind('accounts/get');
        $controllers->get('/', 'controller.account_controller:getList')
            ->bind('accounts/get-list');
        $controllers->put('/{id}', 'controller.account_controller:update')
            ->bind('accounts/update');
        $controllers->delete('/{id}', 'controller.account_controller:delete')
            ->bind('accounts/delete');

        return $controllers;
    }
}
