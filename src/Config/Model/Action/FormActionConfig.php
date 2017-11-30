<?php

namespace Ruvents\AdminBundle\Config\Model\Action;

use Ruvents\AdminBundle\Config\Model\AbstractConfig;
use Ruvents\AdminBundle\Config\Model\Field\FormFieldConfig;

/**
 * @property bool              $enabled
 * @property string[]          $requiresGranted
 * @property string            $title
 * @property string            $type
 * @property array             $options
 * @property string            $theme
 * @property FormFieldConfig[] $fields
 */
class FormActionConfig extends AbstractConfig
{
}
