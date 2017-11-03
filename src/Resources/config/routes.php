<?php

namespace Symfony\Component\Routing\Loader\Configurator;

use Ruvents\AdminBundle\Controller;

return function (RoutingConfigurator $configurator) {
    $configurator
        ->add('ruvents_admin', '')
        ->controller(Controller\IndexController::class);

    $configurator
        ->add('ruvents_admin_list', '/{ruvents_admin_entity}')
        ->requirements([
            'ruvents_admin_entity' => '[\w-]+',
        ])
        ->controller(Controller\ListController::class);

    $configurator
        ->add('ruvents_admin_create', '/{ruvents_admin_entity}/create')
        ->requirements([
            'ruvents_admin_entity' => '[\w-]+',
        ])
        ->controller(Controller\CreateController::class);

    $configurator
        ->add('ruvents_admin_edit', '/{ruvents_admin_entity}/edit/{id}')
        ->requirements([
            'ruvents_admin_entity' => '[\w-]+',
            'id' => '[\w-]+',
        ])
        ->controller(Controller\EditController::class);
};
