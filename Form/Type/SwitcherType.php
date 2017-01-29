<?php

namespace Ruwork\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwitcherType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['label_yes'] = $options['label_yes'];
        $view->vars['label_no'] = $options['label_no'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label_yes' => 'Да',
            'label_no' => 'Нет',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruwork_admin_switcher';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CheckboxType::class;
    }
}
