<?php

namespace Ruwork\AdminBundle\Twig\Extension;

use Ruwork\AdminBundle\Form\FormHelper;

class FormExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('attr_add_class', function (array $attr, $class) {
                FormHelper::addAttrClass($attr, $class);

                return $attr;
            }),
        ];
    }
}
