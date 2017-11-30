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
            'ruvents_admin_entity' => '%ruvents_admin.routing.list.entities_requirement%',
        ])
        ->controller(Controller\ListController::class);

    $configurator
        ->add('ruvents_admin_create', '/{ruvents_admin_entity}/create')
        ->requirements([
            'ruvents_admin_entity' => '%ruvents_admin.routing.create.entities_requirement%',
        ])
        ->controller(Controller\CreateController::class);

    $configurator
        ->add('ruvents_admin_edit', '/{ruvents_admin_entity}/edit/{id}')
        ->requirements([
            'ruvents_admin_entity' => '%ruvents_admin.routing.edit.entities_requirement%',
            'id' => '[\w-]+',
        ])
        ->controller(Controller\EditController::class);

    $configurator
        ->add('ruvents_admin_delete', '/{ruvents_admin_entity}/delete/{id}')
        ->requirements([
            'ruvents_admin_entity' => '%ruvents_admin.routing.delete.entities_requirement%',
            'id' => '[\w-]+',
        ])
        ->controller(Controller\DeleteController::class);
};
