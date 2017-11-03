<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruvents\AdminBundle\Config;
use Ruvents\AdminBundle\Controller;
use Ruvents\AdminBundle\Controller\ArgumentValueResolver;
use Ruvents\AdminBundle\Form\Type\FieldsFormType;
use Ruvents\AdminBundle\Menu\MenuResolver;
use Ruvents\AdminBundle\Routing\AdminRouteLoader;
use Ruvents\AdminBundle\Twig\AdminExtension;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

return function (ContainerConfigurator $container) {
    $services = $container->services()
        ->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(Config\Manager::class)
        ->args([
            '$cache' => ref('ruvents_admin.config.cache'),
            '$passes' => tagged('ruvents_admin.config_pass'),
            '$debug' => '%kernel.debug%',
        ]);

    $services
        ->set(Config\Pass\BuildEntitiesPass::class)
        ->tag('ruvents_admin.config_pass', ['priority' => 1024]);

    $services
        ->set(Config\Pass\BuildMenuPass::class)
        ->tag('ruvents_admin.config_pass', ['priority' => 1000]);

    $services
        ->set(Config\Pass\FormFieldsPass::class)
        ->tag('ruvents_admin.config_pass', ['priority' => 512]);

    $services
        ->set(Config\Pass\ListFieldsPass::class)
        ->tag('ruvents_admin.config_pass', ['priority' => 512]);

    $services
        ->set(Config\Pass\ResolveFormThemePass::class)
        ->tag('ruvents_admin.config_pass', ['priority' => 256]);

    $services
        ->set('ruvents_admin.config.cache', FilesystemCache::class)
        ->args(['ruvents_admin', 0, '%kernel.cache_dir%']);

    $services
        ->set(Config\Model\Config::class)
        ->factory([ref(Config\Manager::class), 'getConfig']);

    $services->set(Controller\IndexController::class)->public();
    $services->set(Controller\ListController::class)->public();
    $services->set(Controller\CreateController::class)->public();
    $services->set(Controller\EditController::class)->public();

    $services->set(FieldsFormType::class);

    $services->set(AdminExtension::class);

    $services->set(ArgumentValueResolver\EntityConfigResolver::class)
        ->tag('controller.argument_value_resolver', ['priority' => 150]);

    $services->set(AdminRouteLoader::class)
        ->tag('routing.loader');

    $services->set('ruvents_admin.menu.language', ExpressionLanguage::class);

    $services->set(MenuResolver::class)
        ->args([
            '$language' => ref('ruvents_admin.menu.language'),
        ]);
};
