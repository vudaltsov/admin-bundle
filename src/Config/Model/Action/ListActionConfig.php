<?php

namespace Ruvents\AdminBundle\Config\Model\Action;

use Ruvents\AdminBundle\Config\Model\AbstractConfig;
use Ruvents\AdminBundle\Config\Model\Field\ListFieldConfig;

/**
 * @property bool              $enabled
 * @property string[]          $requiresGranted
 * @property string            $title
 * @property ListFieldConfig[] $fields
 */
class ListActionConfig extends AbstractConfig
{
}
