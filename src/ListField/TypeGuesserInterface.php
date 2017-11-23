<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\ListField;

interface TypeGuesserInterface
{
    public function guess(string $class, string $propertyPath): ?string;
}
