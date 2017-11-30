<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\Config\Model\Action;

use Ruvents\AdminBundle\Config\Model\AbstractConfig;
use Ruvents\AdminBundle\Config\Model\Field\ListFieldConfig;

/**
 * @property bool              $enabled
 * @property int               $perPage
 * @property string[]          $requiresGranted
 * @property string            $title
 * @property ListFieldConfig[] $fields
 */
class ListActionConfig extends AbstractConfig
{
}
