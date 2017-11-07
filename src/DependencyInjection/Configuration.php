<?php

namespace Ruvents\AdminBundle\DependencyInjection;

use Ruvents\AdminBundle\Form\Type\FieldsFormType;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        return (new TreeBuilder())
            ->root('ruvents_admin')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('debug')
                        ->defaultValue('%kernel.debug%')
                    ->end()
                    ->append($this->form())
                    ->append($this->menu())
                    ->append($this->entities())
                ->end()
            ->end();
    }

    private function menu(string $name = 'menu', bool $withChildren = true): ArrayNodeDefinition
    {
        $definition = (new TreeBuilder())
            ->root($name);

        $prototype = $definition
            ->arrayPrototype()
                ->children()
                    ->append($this->requiresGranted())
                    ->scalarNode('title')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->append($this->attributes())
                    ->scalarNode('url')->end()
                    ->scalarNode('route')->end()
                    ->variableNode('route_params')
                        ->defaultValue([])
                        ->validate()
                            ->ifTrue(function ($value) {
                                return !is_array($value);
                            })
                            ->thenInvalid('The "attributes" value must be an array, "%s" given.')
                        ->end()
                    ->end()
                    ->scalarNode('active')->defaultNull()->end()
                    ->scalarNode('entity')->end();

        if ($withChildren) {
            $prototype->append($this->menu('children', false));
        }

        return $definition;
    }

    private function form(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('form')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('default_theme')
                        ->cannotBeEmpty()
                        ->defaultValue('@RuventsAdmin/forms.html.twig')
                    ->end()
                    ->arrayNode('type_aliases')
                        ->scalarPrototype()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end();
    }

    private function entities(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('entities')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->append($this->requiresGranted())
                        ->append($this->listAction('List'))
                        ->append($this->formAction('create', 'Create'))
                        ->append($this->formAction('edit', 'Edit'))
                        ->append($this->deleteAction())
                    ->end()
                ->end();
    }

    private function listAction(string $defaultTitle): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('list')
                ->canBeDisabled()
                ->children()
                    ->append($this->requiresGranted())
                    ->scalarNode('title')
                        ->defaultValue($defaultTitle)
                        ->cannotBeEmpty()
                    ->end()
                    ->arrayNode('fields')
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('name')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('type')
                                    ->defaultNull()
                                ->end()
                                ->scalarNode('title')
                                    ->defaultNull()
                                ->end()
                                ->append($this->attributes())
                            ->end()
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function ($value) {
                                    if (!preg_match('/^(?<name>[\w-]+)(?>\@(?<type>[\w-\\\]+))?(?>\{(?<title>.*)\})?$/', $value, $matches)) {
                                        throw new \InvalidArgumentException(sprintf('"%s" is not a valid field definition.', $value));
                                    }

                                    return [
                                        'name' => $matches['name'],
                                        'type' => $matches['type'] ?? null,
                                        'title' => $matches['title'] ?? null,
                                    ];
                                })
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }

    private function formAction(string $name, string $defaultTitle): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root($name)
                ->canBeDisabled()
                ->children()
                    ->append($this->requiresGranted())
                    ->scalarNode('title')
                        ->defaultValue($defaultTitle)
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('type')
                        ->defaultValue(FieldsFormType::class)
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('theme')
                        ->defaultNull()
                    ->end()
                    ->append($this->fields())
                ->end();
    }

    private function deleteAction(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('delete')
                ->canBeDisabled()
                ->append($this->requiresGranted());
    }

    private function fields(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('fields')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('name')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('type')
                            ->defaultNull()
                        ->end()
                        ->variableNode('options')
                            ->defaultValue([])
                        ->end()
                        ->append($this->requiresGranted())
                    ->end()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($value) {
                            static $groupI = 1;

                            if (!preg_match('/^(?<name>[\w-]+)?(?>\@(?<type>[\w-\\\]+))?(?<attr_class>(?>\.[\w-]+)+)?(?>\{(?<label>.*)\})?$/', $value, $matches)) {
                                throw new \InvalidArgumentException(sprintf('"%s" is not a valid field definition.', $value));
                            }

                            $name = $matches['name'] ?? null;
                            $type = $matches['type'] ?? null;

                            if (!$name) {
                                if ($type === 'group') {
                                    $name = '__group'.($groupI++);
                                } else {
                                    throw new \InvalidArgumentException(sprintf('"%s" is not a valid field definition. Not specifying name is allowed only for the "group" type.', $value));
                                }
                            }

                            return [
                                'name' => $name,
                                'type' => $type,
                                'options' => [
                                    'mapped' => $type !== 'group',
                                    'label' => $matches['label'] ?? null,
                                    'attr' => [
                                        'class' => strtr($matches['attr_class'] ?? '', '.', ' '),
                                    ],
                                ],
                            ];
                        })
                    ->end()
                ->end();
    }

    private function requiresGranted(): ArrayNodeDefinition
    {
        return (new TreeBuilder())
            ->root('requires_granted')
                ->scalarPrototype()
                    ->cannotBeEmpty()
                ->end();
    }

    private function attributes(): VariableNodeDefinition
    {
        /** @var VariableNodeDefinition $definition */
        $definition = (new TreeBuilder())
            ->root('attributes', 'variable')
                ->defaultValue([])
                ->validate()
                    ->ifTrue(function ($value) {
                        return !is_array($value);
                    })
                    ->thenInvalid('The "attributes" value must be an array, "%s" given.')
                ->end();

        return $definition;
    }
}
