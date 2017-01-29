<?php

namespace Ruwork\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType as BaseDateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_admin_date_type';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseDateType::class;
    }
}
