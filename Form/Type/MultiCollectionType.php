<?php

namespace Ruwork\AdminBundle\Form\Type;

use Ruwork\AdminBundle\Form\EventSubscriber\MultiCollectionSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultiCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['allow_add'] && $options['prototype']) {
            static $nameIncrement = 0;
            $prototypes = [];

            foreach ($options['entries_config'] as $name => $config) {
                $prototypeOptions = array_replace([
                    'required' => $options['required'],
                ], $config['options']);

                if (null !== $config['data']) {
                    $prototypeOptions['data'] = $config['data'];
                }

                $childName = '__name_'.$nameIncrement.'_'.$name.'__';

                $prototypes[$name] = $builder
                    ->create($childName, $config['type'], $prototypeOptions)
                    ->add('_prototype_name', HiddenType::class, [
                        'mapped' => false,
                        'data' => $name,
                    ])
                    ->getForm();

                $nameIncrement++;
            }

            $builder->setAttribute('prototypes', $prototypes);
        }

        $builder->addEventSubscriber(new MultiCollectionSubscriber(
            $options['entries_config'],
            $options['pre_set_data_resolver'],
            $options['pre_submit_resolver'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['delete_empty']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'allow_add' => $options['allow_add'],
            'allow_delete' => $options['allow_delete'],
        ]);
        if ($form->getConfig()->hasAttribute('prototypes')) {
            foreach ($form->getConfig()->getAttribute('prototypes') as $name => $prototype) {
                /** @var FormInterface $prototype */
                $view->vars['prototypes'][$name] = $prototype->setParent($form)->createView($view);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getConfig()->hasAttribute('prototypes')) {
            foreach ($view->vars['prototypes'] as $prototype) {
                if ($prototype->vars['multipart']) {
                    $view->vars['multipart'] = true;
                    break;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        /** @noinspection PhpUnusedParameterInspection */
        $configNormalizer = function (Options $options, $configs) {
            foreach ($configs as $name => &$config) {
                $config['options']['block_name'] = $name.'_entry';

                if (!isset($config['type'])) {
                    throw new MissingOptionsException(sprintf(
                        'entries_config[%s][type] option is missing.', $name
                    ));
                }

                if (!isset($config['data'])) {
                    $config['data'] = null;
                }
            }

            return $configs;
        };

        $resolver
            ->setDefaults([
                'allow_add' => false,
                'allow_delete' => false,
                'prototype' => true,
                'delete_empty' => false,
                'pre_submit_resolver' => function (array $data) {
                    return $data['_prototype_name'];
                },
            ])
            ->setRequired([
                'entries_config',
                'pre_set_data_resolver',
            ])
            ->setAllowedTypes('allow_add', 'bool')
            ->setAllowedTypes('allow_delete', 'bool')
            ->setAllowedTypes('prototype', 'bool')
            ->setAllowedTypes('delete_empty', 'bool')
            ->setAllowedTypes('entries_config', 'array')
            ->setAllowedTypes('pre_set_data_resolver', ['array', 'callable'])
            ->setAllowedTypes('pre_submit_resolver', 'callable')
            ->setNormalizer('entries_config', $configNormalizer);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_admin_multi_collection';
    }
}
