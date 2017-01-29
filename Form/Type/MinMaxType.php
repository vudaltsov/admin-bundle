<?php

namespace Ruwork\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinMaxType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($options['min_child'], HiddenType::class, [
                'error_bubbling' => false,
            ])
            ->add($options['max_child'], HiddenType::class, [
                'error_bubbling' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['min'] = $options['min'];
        $view->vars['max'] = $options['max'];
        $view->vars['step'] = $options['step'];
        $view->vars['min_child'] = $options['min_child'];
        $view->vars['max_child'] = $options['max_child'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'inherit_data' => true,
                'min_child' => 'min',
                'max_child' => 'max',
                'min' => 0,
                'max' => 1,
                'step' => 1,
            ])
            ->setAllowedTypes('min_child', 'string')
            ->setAllowedTypes('max_child', 'string')
            ->setAllowedTypes('min', 'int')
            ->setAllowedTypes('max', 'int')
            ->setAllowedTypes('step', 'int');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_admin_min_max';
    }
}
