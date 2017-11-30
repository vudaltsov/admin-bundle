<?php
declare(strict_types=1);

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

        $listableEntities = [];
        $creatableEntities = [];
        $editableEntities = [];
        $deletableEntities = [];

        foreach ($config['entities'] as $name => $entityConfig) {
            if ($entityConfig['list']['enabled']) {
                $listableEntities[] = $name;
            }
            if ($entityConfig['create']['enabled']) {
                $creatableEntities[] = $name;
            }
            if ($entityConfig['edit']['enabled']) {
                $editableEntities[] = $name;
            }
            if ($entityConfig['delete']['enabled']) {
                $deletableEntities[] = $name;
            }
        }

        $container->setParameter('ruvents_admin.routing.entities_requirement',
            $this->createRouteRequirement(array_keys($config['entities'])));

        $container->setParameter('ruvents_admin.routing.list.entities_requirement',
            $this->createRouteRequirement($listableEntities));

        $container->setParameter('ruvents_admin.routing.create.entities_requirement',
            $this->createRouteRequirement($creatableEntities));

        $container->setParameter('ruvents_admin.routing.edit.entities_requirement',
            $this->createRouteRequirement($editableEntities));

        $container->setParameter('ruvents_admin.routing.delete.entities_requirement',
            $this->createRouteRequirement($deletableEntities));

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

    private function createRouteRequirement(array $entityNames): string
    {
        return implode('|', $entityNames) ?: 'no-entities';
    }
}
