<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\ListField;

interface TypeContextProcessorInterface
{
    public static function getType(): string;

    public function process(array &$context);
}
