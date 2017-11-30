<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\Config\Model;

use Ruvents\AdminBundle\Config\Model\Action\DeleteActionConfig;
use Ruvents\AdminBundle\Config\Model\Action\FormActionConfig;
use Ruvents\AdminBundle\Config\Model\Action\ListActionConfig;

/**
 * @property string             $name
 * @property string             $class
 * @property string[]           $requiresGranted
 * @property ListActionConfig   $list
 * @property FormActionConfig   $create
 * @property FormActionConfig   $edit
 * @property DeleteActionConfig $delete
 */
class EntityConfig extends AbstractConfig
{
}
