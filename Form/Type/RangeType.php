<?php

namespace Ruwork\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\RangeType as BaseRangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RangeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new CallbackTransformer(
            function ($data) {
                return $data;
            },
            function ($data) {
                return (float)$data;
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['min'] = $options['min'];
        $view->vars['max'] = $options['max'];
        $view->vars['step'] = $options['step'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'min',
                'max',
                'step',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_admin_range';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseRangeType::class;
    }
}
