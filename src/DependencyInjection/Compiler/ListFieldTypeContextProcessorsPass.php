<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\DependencyInjection\Compiler;

use Ruvents\AdminBundle\ListField\TypeContextProcessorInterface;
use Ruvents\AdminBundle\Twig\ListExtension;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ListFieldTypeContextProcessorsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ListExtension::class)) {
            return;
        }

        $tagged = $container->findTaggedServiceIds($tag = 'ruvents_admin.list_field_type_context_processor', true);
        $processors = [];

        foreach ($tagged as $id => $attributes) {
            $processor = $container->findDefinition($id);
            $class = $processor->getClass();

            if (!is_subclass_of($class, TypeContextProcessorInterface::class)) {
                throw new \InvalidArgumentException(sprintf('Service tagged with "%s" must implement "%s".', $tag, TypeContextProcessorInterface::class));
            }

            $type = call_user_func([$class, 'getType']);

            if (isset($processors[$type])) {
                continue;
            }

            $container->addResource(new FileResource((new \ReflectionClass($class))->getFileName()));
            $processors[$type] = new Reference($id);
        }

        $container->findDefinition(ListExtension::class)
            ->setArgument('$processors', ServiceLocatorTagPass::register($container, $processors));
    }
}
