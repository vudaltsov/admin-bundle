<?php

namespace Ruwork\AdminBundle\Form;

class FormHelper
{
    private function __construct()
    {
    }

    /**
     * @param array $attr
     * @param       $class
     */
    public static function addAttrClass(array &$attr, $class)
    {
        $attr['class'] = ltrim((empty($attr['class']) ? '' : $attr['class']).' '.$class);
    }
}
