<?php

namespace Ruvents\AdminBundle\DependencyInjection;

use Ruvents\AdminBundle\Config\ConfigManager;
use Ruvents\AdminBundle\Config\Pass\PassInterface;
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
    }
}
