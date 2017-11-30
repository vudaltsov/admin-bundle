<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\Config\Model\Menu;

use Ruvents\AdminBundle\Config\Model\AbstractConfig;

/**
 * @property string[] $requiresGranted
 * @property string   $title
 * @property array    $attributes
 */
abstract class AbstractItemConfig extends AbstractConfig
{
}
