<?php

namespace Ruvents\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class ButtonGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruvents_admin_button_group';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return GroupType::class;
    }
}
