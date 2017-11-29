<?php

namespace Ruvents\AdminBundle\DependencyInjection;

use Ruvents\AdminBundle\Config\ConfigManager;
use Ruvents\AdminBundle\Config\Pass\PassInterface;
use Ruvents\AdminBundle\ListField\TypeContextProcessor\TypeContextProcessorInterface;
use Ruvents\AdminBundle\ListField\TypeGuesser\TypeGuesserInterface;
use Ruvents\AdminBundle\Twig\ListExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class RuventsAdminExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        (new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.php');

        $container->setParameter('ruvents_admin.routing.entities_requirement',
            implode('|', array_keys($config['entities'])) ?: 'no-entities');

        $container->findDefinition(ConfigManager::class)
            ->setArgument('$data', $config)
            ->setArgument('$debug', $config['debug']);

        $container->registerForAutoconfiguration(PassInterface::class)
            ->addTag('ruvents_admin.config_pass');

        $container->registerForAutoconfiguration(TypeGuesserInterface::class)
            ->addTag('ruvents_admin.list_field_type_guesser');

        $container->registerForAutoconfiguration(TypeContextProcessorInterface::class)
            ->addTag('ruvents_admin.list_field_type_context_processor');

        $container->findDefinition(ListExtension::class)
            ->setArgument('$typesTemplate', $config['list']['types_template']);
    }
}
