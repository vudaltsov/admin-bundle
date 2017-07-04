<?php

namespace Ruvents\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class MarkdownType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-provide'] = 'markdown';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'markdown';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextareaType::class;
    }
}
