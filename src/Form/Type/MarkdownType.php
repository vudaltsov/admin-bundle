<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MarkdownType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ruvents_admin_markdown';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextareaType::class;
    }
}
