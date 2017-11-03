<?php

namespace Ruvents\AdminBundle\DependencyInjection;

use Ruvents\AdminBundle\Config\Pass\PassInterface;
use Ruvents\AdminBundle\Config\Manager;
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

        $container->findDefinition(Manager::class)
            ->setArgument('$data', $config);

        $container->registerForAutoconfiguration(PassInterface::class)
            ->addTag('ruvents_admin.config_pass');
    }
}
