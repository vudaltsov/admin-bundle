<?php

namespace Ruwork\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRangeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($options['start_child'], DateType::class)
            ->add($options['finish_child'], DateType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'inherit_data' => true,
                'start_child' => 'start',
                'finish_child' => 'finish',
            ])
            ->setAllowedTypes('start_child', 'string')
            ->setAllowedTypes('finish_child', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_admin_date_range';
    }
}
